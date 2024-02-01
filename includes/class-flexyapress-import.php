<?php

class Flexyapress_Import{

	private $api;

	/**
	 * Flexyapress_Import constructor.
	 *
	 * @param $api
	 */
	public function __construct() {
		$this->setAPI(new Flexyapress_API());
	}

	public function import_cases($force = false, $debug = false, $status = '[ForSale, Sold, UnderSale, BeforeSale, FinallyTrade]'){
		//global $wpdb;
    	//$results = $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}flexyapress_log");

		ob_start();

        $this->update_lead_types();

		$existing_cases = $this->get_existing_cases_ID();
        $error = false;

        $count_before = count($existing_cases);
        $updated_since = $force ? '1999-01-01' : (get_transient('flexyapress_last_update') ?: '1999-01-01');
        $amount = $force ? 9999 : 20;

        $api_cases = $this->getAPI()->get_cases($amount, $updated_since);
        echo 'getting cases ('.count($api_cases).')<br>';
        $api_rental_cases = $this->getAPI()->get_rental_cases($amount, $updated_since);
        echo 'getting rental cases ('.count($api_rental_cases).')<br>';
        $api_business_cases = $this->getAPI()->get_business_cases($amount, $updated_since);
        echo 'getting business cases ('.count($api_business_cases).')<br>';
        $api_business_rental_cases = $this->getAPI()->get_business_rental_cases($amount, $updated_since);
        echo 'getting business rental cases ('.count($api_business_rental_cases).')<br>';

        if($api_rental_cases){
            $api_cases = array_merge($api_cases, $api_rental_cases);
        }

        if($api_business_cases){
            $api_cases = array_merge($api_cases, $api_business_cases);
        }

        if($api_business_rental_cases){
            $api_cases = array_merge($api_cases, $api_business_rental_cases);
        }

		$limit = -1;
		$count = 1;

		if(is_array($api_cases) && count($api_cases) > 0){

			//new Flexyapress_Log('import_started', 'test');
			foreach($api_cases as $case){
                if(!is_object($case)){
                    echo 'not an object. Skipping<br>';
                    $error = true;
                    continue;
                }
                if(empty($case->id)){
                    echo 'No caseid. Skipping<br>';
                    $error = true;
                    continue;
                }

                if(in_array( $case->status, ['Return', 'LostCommission', 'Valuation', 'BeforeSale'])){
                    continue;
                }

                if($case->shopNo == 'MW001' || $case->shopNo == 'MW002'){
                    echo 'from test shop. Skipping <br>';
                    continue;
                }

				echo "updating case: $case->id<br>";
				$this->import_case($case->id, $case->__typename, $force);
                flush();
                ob_flush();
				$ex_case = array_search($case->id, $existing_cases);
				if($ex_case){unset($existing_cases[$ex_case]);}
				$count++;
				if($count === $limit){
					echo 'Reached Limit.. Terminating';
					die();
				}
				echo '<br>';
			}

            /*if($this->getAPI()->get_save_images_locally()){
                $queue = new Flexyapress_Media_Queue();
                $queue->run();
            }*/

			$c = ob_get_clean();

            if($force && !empty($existing_cases) && count($existing_cases) < $count_before){
                echo 'deleting cases<br>';
                $this->delete_cases($existing_cases);
            }else{
                echo 'no cases to delete<br>';
            }

            echo 'Updating Realtors: <br>';
            $this->import_realtors();

			if($debug) {
			 echo "<pre>$c</pre>";
			}
			echo 'done';

            set_transient('flexyapress_last_update', date('c', time()));

		}else{
			die('No cases to import!');

		}
	}

    public function update_lead_types(){
        echo 'Saving lead types<br>';
        $lead_types = $this->getAPI()->get_lead_types();

        if(!empty($lead_types)) {
            $formatted_lead_types = [];
            foreach ($lead_types as $lt) {

                if (empty($lt->id)) {
                    echo 'no id. Skipping<br>';
                    continue;
                }
                echo 'getting consents for ' . $lt->name . '<br>';
                $consents = $this->getAPI()->get_lead_consents_by_typeid($lt->id);

                if ( ! empty($consents)) {
                    foreach ($consents as $c) {

                        if (empty($c->isCurrent) || empty($c->leadType)) {
                            continue;
                        }

                        $formatted_lead_types[$c->leadType->name] = [
                            'heading' => $c->heading,
                            'id'      => $c->id,
                            'text'    => $c->purposeText
                        ];

                    }
                } else {
                    echo 'no consents<br>';
                }

            }

            if (get_option('flexyapress_consents') === false) {
                add_option('flexyapress_consents', $formatted_lead_types, null, false);
            } else {
                update_option('flexyapress_consents', $formatted_lead_types);
            }
        }
    }

    public function delete_cases($arr){

        foreach ($arr as $id => $key){
            $this->deleteCase($id);
        }

    }

	public function import_realtors(){

		$realtors = $this->getAPI()->get_employees();
        $exisiting_realtors = $this->get_existing_realtors();
        foreach ($realtors as $realtor){
            echo 'updating '.$realtor->firstName.' '.$realtor->lastName.'<br>';
            if($realtor->active == false || $realtor->visibleOnWebsite == false){
                echo 'not active or visible. Skipping<br>';
                continue;
            }

			$r = new Flexyapress_Realtor($realtor->email);
			$r->setRealtorName($realtor->firstName.' '.$realtor->lastName);
			$r->setRealtorId($realtor->id);
			$r->setRealtorTitle($realtor->title);
			$r->setRealtorOffice($realtor->shopNo);
			$r->setRealtorPhone($realtor->phoneNumber);
			$r->setRealtorMobilePhone($realtor->mobileNumber);
			$r->setRealtorPhoto($realtor->imageUrlAsset);
			$r->setRealtorLinkedin($realtor->linkedInLink);
			$r->setRealtorFacebook($realtor->facebookLink);
			$r->save();

			if(isset($exisiting_realtors[$r->getId()])){
				unset($exisiting_realtors[$r->getId()]);
			}

		}



	}

	public function import_offices(){
		$offices = $this->getAPI()->get_offices();
		$exisiting_offices = $this->get_existing_offices();
		foreach ($offices as $office){

			$r = new Flexyapress_Office(null, null, $office->officeKey);
			$r->setName($office->name);
			$r->setOfficeId($office->id);
			$r->setEmail($office->email);
			$r->setPhone($office->phone);
			$r->setAddress(Flexyapress_Helpers::create_address($office->address->roadname, $office->address->roadnumber, $office->address->floor, $office->address->door));
			$r->setCity($office->address->city);
			$r->setZipcode($office->address->zipcode);
			$r->setRealtorName($office->contactRealtor->name);
			$r->setRealtorPhone($office->contactRealtor->phone);
			$r->setRealtorEmail($office->contactRealtor->email);
			$r->setRealtorPhoto($office->contactRealtor->photoUrl);
			$r->save();

			if(isset($exisiting_offices[$r->getId()])){
				unset($exisiting_offices[$r->getId()]);
			}

		}



	}

	public function get_existing_realtors(){
			$args = array(
				'post_type' => 'realtor',
				'posts_per_page' => -1,
			);

			// The Query
			$the_query = new WP_Query( $args );
			$ex_realtors = $the_query->posts;
			$ids = array();

			foreach($ex_realtors as $realtor){
				$ids[$realtor->ID] = get_field('realtorId',$realtor->ID);
			}

		return $ids;
	}

	public function get_existing_offices(){
		$args = array(
			'post_type' => 'office',
			'posts_per_page' => -1,
		);

		// The Query
		$the_query = new WP_Query( $args );
		$ex_items = $the_query->posts;
		$ids = array();

		foreach($ex_items as $item){
			$ids[$item->ID] = get_field('officeKey',$item->ID);
		}

		return $ids;
	}

	private function deleteCase($id){
		$case = new Flexyapress_Case($id);
        if(get_post_meta($id, 'hidden', true)){
            return false;
        }
        $deleted = $case->delete();
        return $deleted;
	}

	private function import_case($id, $type, $force){

        switch ($type){
            case 'SalesCase':
                $c = $this->getAPI()->get_case($id);
                break;
            case 'BusinessSalesCase':
                $c = $this->getAPI()->get_business_case($id);
                break;
            case 'RentalCase':
                $c = $this->getAPI()->get_rental_case($id);
                break;
            case 'BusinessRentalCase':
                $c = $this->getAPI()->get_business_rental_case($id);
                break;
            default:
                echo 'No valid type: '.$type.'. Skipping<br>';
                return false;
        }

		$case = new Flexyapress_Case($c->id);

        if(!$case->getPostID()){
            echo 'Creating new post: '.$c->address.'<br>';
            new Flexyapress_Log('create_case', $c->caseNumber.': '.$c->address);
        }

        $statuses = [
            'ForSale' => 'ACTIVE',
            'UnderSale' => 'ACTIVE',
            'BeforeSale' => 'ACTIVE',
            'FinallyTrade' => 'SOLD',
            'ForRent' => 'ACTIVE',
            'Sold' => 'SOLD',
            'Rented' => 'SOLD'
        ];
        if(array_key_exists($c->status, $statuses)){
            $sale_status = $statuses[$c->status];
        }

        $broker = false;

        if(!empty($c->brokers) && is_array($c->brokers)){
            foreach ($c->brokers as $b){
                if($b->role == 'MarketingBroker' && $b->visibleOnWebsite){
                    $broker = $b;
                }
            }

            if(!$broker){
                foreach ($c->brokers as $b){
                    if($b->role == 'SellingBroker' && $b->visibleOnWebsite){
                        $broker = $b;
                    }
                }
            }

            if(!$broker){
                foreach ($c->brokers as $b){
                    if($b->role == 'CommissionBroker' && $b->visibleOnWebsite){
                        $broker = $b;
                    }
                }
            }

            if(!$broker){
                foreach ($c->brokers as $b){
                    if($b->role == 'AccountableBroker' && $b->visibleOnWebsite){
                        $broker = $b;
                    }
                }
            }

            if(!$broker){
                foreach ($c->brokers as $b){
                    if($b->role == 'Caseworker' && $b->visibleOnWebsite){
                        $broker = $b;
                    }
                }
            }

        }

        $published_date = !empty($c->firstListingDate) ? $c->firstListingDate : $c->createdDate;

        $custom_fields = null;
        if(!empty($c->solutionSpecificCaseFieldValues)){
            $custom_fields = [];
            foreach ($c->solutionSpecificCaseFieldValues as $cf){
                if(empty($cf->solutionSpecificCaseField)){
                    continue;
                }
                $field = $cf->solutionSpecificCaseField;
                $temp = [
                    'id' => $field->id,
                    'name' => $field->name,
                    'value' => $cf->value
                ];
                $custom_fields[] = $temp;
            }
        }



        $announceText1 = null;
        $announceText2 = null;
        $announceText3 = null;
        $announceText4 = null;
        $announceText5 = null;

        if(!empty($c->announceTexts)){
            foreach ($c->announceTexts as $t){
                switch ($t->textNumber){
                    case 1:
                        $announceText1 = $t->text;
                        break;
                    case 2:
                        $announceText2 = $t->text;
                        break;
                    case 3:
                        $announceText3 = $t->text;
                        break;
                    case 4:
                        $announceText4 = $t->text;
                        break;
                    case 5:
                        $announceText5 = $t->text;
                        break;
                }
            }
        }

        $case->setCaseKey($c->id);
        $case->setCaseNumber($c->caseNumber);
        $case->setCaseType($c->__typename);
        $case->setStatus($sale_status);
        $case->setReserved($c->status == 'UnderSale' ?: false);
        $case->setNoAdvertisement($c->noAdvertisement);
        $case->setPublishedDate($published_date);
        $case->setSoldDate($c->statusHistorySoldDate);
        $case->setUnderSaleDate($c->statusHistoryUnderSaleDate);
        if($broker){
            $case->setRealtor($broker->id);
            $case->setRealtorName($broker->firstName.' '.$broker->lastName);
            $case->setRealtorPhone($broker->phoneNumber);
            $case->setRealtorMobile($broker->mobileNumber);
            $case->setRealtorEmail($broker->email);
            $case->setRealtorImage($broker->imageUrlAsset);
            $case->setRealtorTitle($broker->title);
        }
        $case->setCustomFields($custom_fields);
        if(!empty($c->ConnectionFees)){
            $case->setConnectionFee($c->ConnectionFees);
        }else{
            $case->setConnectionFee(null);
        }

        $case->setRoadname($c->addressRoadName);
        $case->setRoadnumber($c->houseNumber);
        $case->setAddress($c->address);
        $case->setFloor($c->floor);
        $case->setDoor($c->doorLocation);
        $case->setZipcode($c->zipCode);
        $case->setCity($c->addressCityName);
        $case->setPlacename($c->locationCityName);
        $case->setMunicipality($c->municipalityName);
        $case->setLatitude($c->gpsCoordinatesLat);
        $case->setLongitude($c->gpsCoordinatesLong);
        $case->setSizeArea($c->totalLivableArea);
        $case->setSizeAreaTotal($c->totalBuildingArea);
        $case->setSizeLand($c->plotArea);
        $case->setSizeBasement($c->basementArea);
        $case->setSizePatio($c->totalOuthouseBuiltUpArea);
        $case->setSizeCarport($c->totalCarportBuiltUpArea);
        $case->setSizeGarage($c->totalGarageBuiltUpArea);
        //$case->setSizeLandHa();
        //$case->setSizeOtherbuildingsTotal();
        $case->setSizeCommercial($c->totalCommercialArea);
        $case->setNumberRooms($c->roomCount);
        $case->setNumberBedrooms($c->bedRoomCount);
        $case->setNumberBathrooms($c->bathRoomCount);
        $case->setNumberLivingRooms($c->livingRoomCount);
        $case->setNumberFloors($c->totalFloors);
        $case->setTitle($c->announceHeadlineInternet);
        $case->setDescription($c->announceTextInternet);
        //$case->setTeaser();
        //$case->setTag();
        $case->setPrice($c->cashPrice);
        $case->setDownPayment($c->payout);
        $case->setMonthlyOwnerExpenses($c->ownerCostsTotalMonthlyAmount);
        $case->setMonthlyNetPayment($c->net);
        $case->setMonthlyGrossPayment($c->gross);
        //$case->setPriceReductionPercent();
        $case->setPriceReductionDate($c->lastPriceAdjustmentDate);
        //$case->setPrimaryPhoto(class-flexyapress-case.php);
        //$case->setPrimaryPhoto1000();
        //$case->setPhotos();
        //$case->setPhotos1000();
        //$case->setThumbnails();
        //$case->setDrawings();
        //$case->setVideos();
        $case->setDocuments($c->publicDocuments);
        $case->setConstructionYear($c->yearBuilt);
        $case->setReconstructionYear($c->yearRenovated);
        $case->setEnergyBrand($c->energyMark);
        $case->setHeatingInstallation($c->centralHeating);
        //$case->setHeatingInstallationSuppl();
        $case->setDaysForSale($c->laytime);
        //$case->setOpenhouseActive();
        //$case->setOpenhouseSignupRequired();
        //$case->setOpenHouseDate();
        //$case->setOpenhouseSignupDate();
        //$case->setSaleType();
        $case->setPropertyType($c->propertyType);
        $case->setPropertyClass($this->formatPropertyClass($c->propertyType));
        $case->setDescription1($announceText1);
        $case->setDescription2($announceText2);
        $case->setDescription3($announceText3);
        $case->setDescription4($announceText4);
        $case->setDescription5($announceText5);
        $case->setShopNo($c->shopNo);
        $case->setPresentationUrl($c->presentationUrl);
        //$case->setPhotoTexts();

        if($case->getCaseType() == 'BusinessRentalCase'){
            $case->setMonthlyRent($c->monthlyRent);
            $case->setYearlyRent($c->yearlyRent);
            if(!empty($c->FloorAreaTotal)){
                $case->setYearlyRentPrArea($c->yearlyRent/$c->FloorAreaTotal);
            }
        }

        if($case->getCaseType() == 'RentalCase'){
            $case->setMonthlyRent($c->rentPerMonth);
        }

        if(!empty($c->openHouses)){

            foreach ($c->openHouses as $oh){

                if($oh->status != 'Planned'){
                    continue;
                }

                if(empty($oh->startDate) || strtotime($oh->startDate) < strtotime('now')){
                    continue;
                }

                date_default_timezone_set('Europe/Copenhagen');

                $temp = array(
                    'dateStart' => $oh->startDate,
                    'dateStartUnix' => strtotime($oh->startDate),
                    'dateEndUnix' => strtotime($oh->endDate),
                    'dateEnd' => $oh->endDate,
                    'signupRequired' => $oh->requiresRegistration,
                    'id' => $oh->id,
                    'description' => $oh->description

                );

                $sorted_appointments[] = $temp;

            }

            if(!empty($sorted_appointments)){
                $case->setOpenhouseActive(true);
                $day = Flexyapress_Helpers::get_pretty_day_name(date('w', strtotime($sorted_appointments[0]['dateStart'])));
                $case->setOpenHouseDate($day.' d. '.date('d/m', strtotime($sorted_appointments[0]['dateStart'])).' kl. '.date('H:i', strtotime($sorted_appointments[0]['dateStart'])).(!empty($sorted_appointments[0]['dateEndUnix']) ? ' - '.date('H:i', $sorted_appointments[0]['dateEndUnix']) : ''));
                $case->setOpenhouseSignupRequired($sorted_appointments[0]['signupRequired']);

            }else{
                $case->setOpenhouseActive(false);
            }

            $case->setOpenhouseDatesTotal($sorted_appointments);

        }else{
            $case->setOpenhouseActive(false);
            $case->setOpenhouseDatesTotal(array());
        }

		$buildings = [];
		if(!empty($c->buildings) && is_array($c->buildings)){
			foreach ($c->buildings as $b){
				$temp = [
					'description' => $b->description,
					'builtUpArea' => $b->builtUpArea,
					'mainBuilding' => $b->isMainBuilding
				];
				$buildings[] = $temp;
			}
		}

		$case->setBuildings($buildings);

		$saved_id = $case->save();

		if($saved_id){

			$case->setPostID($saved_id);
			$this->set_taxonomies($case);

            if(!empty($c->media->items)){
                $image_url = "";

                $image_arr = Flexyapress_Helpers::sort_mw_images_by_priority($c->media->items);

                foreach ($image_arr as $img){
                    if(!$img->published || $img->mediaType->name != 'Billede'){
                        continue;
                    }
                    $image_url = $img->resourceUrl;
                    break;
                }

                if($image_url) {
                    $this->setCaseThumbnail($case, $image_url);
                }else{
                    echo 'no image<br>';
                }

            }

            $this->setCaseImages($case, $c);
		}

	}

    public function formatPropertyClass($type){

        $class = 'HOUSE';

        if(in_array($type, ['VacationPlot', 'AllotmentPlot', 'AllYearRoundPlot', 'FreeLand'])){
            $class = 'LAND';
        }

        if(in_array($type, ['Condo', 'IdeelAnpartCondo', 'PartnershipOrLimitedPartnershipCondo'])){
            $class = 'APARTMENT';
        }

        if(in_array($type, ['TerracedHouse', 'IdeelAnpartTerracedHouse', 'PartnershipOrLimitedPartnershipTerracedHouse'])){
            $class = 'HOUSING_COOPERATIVE';
        }

        if(in_array($type, ['HobbyAgriculture'])){
            $class = 'LEISURETIME_FARM';
        }

        if(in_array($type, ['VacationCondo', 'AllotmentHut', 'VacationHousing'])){
            $class = 'RECREATIONAL';
        }


        return $class;

    }

    public function setCaseImages($case, $c){

        $images = [];
        $floorplans = [];
        $videos = [];

        if(!empty($c->media) && !empty($c->media->items)){

            $image_arr = Flexyapress_Helpers::sort_mw_images_by_priority($c->media->items);

            foreach ($image_arr as $img){

                if(!$img->published){
                    continue;
                }

                if($img->mediaType->name != 'Video'){
                    $temp = [
                        'url' => $this->getAPI()->get_external_image_url($img->id, $img->imageHash),
                        'url_presentation' => str_replace('/Assets/', '/Presentation/',$img->resourceUrl),
                        'url_thumbnail' => $this->getAPI()->get_external_image_url($img->id, $img->imageHash, 300),
                        'description' => $img->description,
                        'priority' => $img->priority
                    ];
                }

                if($img->mediaType->name == 'Plantegning'){
                    $floorplans[] = $temp;
                }else if($img->mediaType->name == 'Video'){
                    $videos[] = ['url' => $img->resourceUrl, 'mimeType' => $img->mimeType];
                }else{
                    $images[] = $temp;
                }

            }

        }

        if($images){
            update_field('primaryPhoto', $images[0]['url'], $case->getPostID());
            update_field('primaryPhoto1000', $images[0]['url_presentation'], $case->getPostID());
        }

        update_field('imagesExternal', $images, $case->getPostID());
        update_field('videosExternal', $videos, $case->getPostID());
        update_field('floorplansExternal', $floorplans, $case->getPostID());

    }


	public function setCaseThumbnail($case, $photo_url){
		$thumb = get_post_thumbnail_id( $case->getPostID());
        echo 'Setting Thumbnail: '.$photo_url.'<br>';
		//if(!$thumb || $this->thumb_has_changed($thumb, $id)){
		if(!$thumb || $thumb === 1 || !empty($_GET['force-images'])){
			echo 'no thumb<br>';
            //Check if remote image exists:
            $check = wp_remote_get($photo_url);

            if(is_wp_error($check) || !is_array($check) || wp_remote_retrieve_response_code($check) === 400){
                echo 'Invalid image. Aborting<br>';
                return false;
            }

            if(!empty($_GET['force-images']) && $thumb > 1){
                echo 'Deleting past image';
                self::deleteImage($thumb);
            }

            $img_id = $this->save_remote_image($photo_url, $case->getPostID(), $case->getCaseNumber());

            if($img_id){
                $old_thumb = get_post_thumbnail_id( $case->getPostID() );
                set_post_thumbnail( $case->getPostID(), $img_id );
            }else{

            }

		}else{
			echo 'no change in thumb<br>';

            if(!empty($_GET['clear-images']) && $thumb && $thumb > 1){
                echo 'Deleting featured image';
                self::deleteImage($thumb);
                set_post_thumbnail( $case->getPostID(), null);
            }

		}

	}

	public function setAPI($api){
		$this->api = $api;
	}

	public function getAPI(){
		return $this->api;
	}

	/* Get all existing cases as post_id. Returns array */
	private function get_existing_cases_ID(){

		if(!isset($this->ex_cases_id)){
			$args = array(

				'post_type' => 'sag',
				'posts_per_page' => -1,

			);

			// The Query
			$the_query = new WP_Query( $args );
			$ex_cases = $the_query->posts;
			$ids = array();

			foreach($ex_cases as $case){

				$ids[$case->ID] = get_field('caseKey',$case->ID);

			}

			$this->ex_cases_id = $ids;

		}

		return $this->ex_cases_id;

	}

	private function set_taxonomies($case){
		// Set taxonomies. Tax_input can't be used because of permissions.
		//$saletype_ids = $this->set_sale_type_taxonomy($case->getStatus(), $case->getSaleType());
		//wp_set_object_terms($case->getPostID(), $saletype_ids ,'saletype');
		//$zipcodes = $this->set_zipcode_taxonomy($case->getZipcode());
		//wp_set_object_terms($case->getPostID(), $zipcodes ,'zipcode');
		$types = $this->set_property_type_taxonomy($case->getPrettyPropertyType());
		wp_set_object_terms($case->getPostID(), $types ,'type');
		//$offices = $this->set_office_taxonomy($case->getOfficeId());
		//wp_set_object_terms($case->getPostID(), $offices ,'office');
	}

	private function set_property_type_taxonomy($type){
		$title = $type;
		$id = term_exists( $title, 'type' );

		if(!$id){
			$id = wp_insert_term( $title, 'type' );
		}

		$tax = array($title);

		return $tax;
	}

	private function set_office_taxonomy($officeId){
		$title = (string) 'office-'.$officeId;
		$id = term_exists( $title, 'office' );
		if(!$id){
			$id = wp_insert_term( $title, 'office' );
		}

		$tax = array($title);

		return $tax;
	}

	private function set_zipcode_taxonomy($zipcode){
		$zip = (string) $zipcode;

		$id = term_exists( $zip, 'zipcode' );

		if(!$id){
			$id = wp_insert_term( $zip, 'zipcode' );
		}

		$tax = array($zip);

		return $tax;
	}

	private function set_sale_type_taxonomy($status, $type){

		if($status === 'ACTIVE'){

			switch($type){

				case 'PRIVATESALE':

					$parent = term_exists( 'Privat', 'saletype', 0 );

					if(!$parent){
						$parent = wp_insert_term( 'Privat', 'saletype', 0 );
					}

					$id = term_exists( 'Salg', 'saletype', $parent['term_taxonomy_id'] );

					if(!$id){
						$id = wp_insert_term( 'Salg', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'PRIVATERENTAL':

					$parent = term_exists( 'Privat', 'saletype', 0 );

					if(!$parent){
						$parent = wp_insert_term( 'Privat', 'saletype', 0 );
					}

					$id = term_exists( 'Leje', 'saletype', $parent['term_taxonomy_id'] );

					if(!$id){
						$id = wp_insert_term( 'Leje', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'PRIVATEINTERNATIONALSALE':

					$parent = term_exists( 'Privat', 'saletype', 0 );

					if(!$parent){
						$parent = wp_insert_term( 'Privat', 'saletype', 0 );
					}

					$id = term_exists( 'Salg International', 'saletype', $parent['term_taxonomy_id'] );

					if(!$id){
						$id = wp_insert_term( 'Salg International', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'BUSINESSSALE':

					$parent = term_exists( 'Erhverv', 'saletype', 0 );

					if(!$parent){
						$parent = wp_insert_term( 'Erhverv', 'saletype', 0 );
					}

					$id = term_exists( 'Salg', 'saletype', $parent['term_taxonomy_id'] );

					if(!$id){
						$id = wp_insert_term( 'Salg', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;
				case 'BUSINESSRENTAL':

					$parent = term_exists( 'Erhverv', 'saletype', 0 );

					if(!$parent){
						$parent = wp_insert_term( 'Erhverv', 'saletype', 0 );
					}

					$id = term_exists( 'Leje', 'saletype', $parent['term_taxonomy_id'] );

					if(!$id){
						$id = wp_insert_term( 'Leje', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
					}

					$tax = array(
						(int) $parent['term_taxonomy_id'],
						(int) $id['term_taxonomy_id'],
					);

					return $tax;

					break;

			}

		}else if($status === 'SOLD'){

			if(strpos($type, 'PRIVATE') !== false){

				$parent = term_exists( 'Privat', 'saletype', 0 );

				if(!$parent){
					$parent = wp_insert_term( 'Privat', 'saletype', 0 );
				}

				$id = term_exists( 'Solgt', 'saletype', $parent['term_taxonomy_id'] );

				if(!$id){
					$id = wp_insert_term( 'Solgt', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
				}

				$tax = array(
					(int) $parent['term_taxonomy_id'],
					(int) $id['term_taxonomy_id'],
				);

				return $tax;

			}else if(strpos($type, 'BUSINESS') !== false){

				$parent = term_exists( 'Erhverv', 'saletype', 0 );

				if(!$parent){
					$parent = wp_insert_term( 'Erhverv', 'saletype', 0 );
				}

				$id = term_exists( 'Solgt', 'saletype', $parent['term_taxonomy_id'] );

				if(!$id){
					$id = wp_insert_term( 'Solgt', 'saletype', array('parent' => $parent['term_taxonomy_id']) );
				}

				$tax = array(
					(int) $parent['term_taxonomy_id'],
					(int) $id['term_taxonomy_id'],
				);

				return $tax;

			}

		}

	}

	private function thumb_has_changed($thumb = false, $url){

		if(!$thumb){
			return false;
		}

		return (strtolower(pathinfo($thumb)['basename']) <> strtolower(pathinfo($url)['basename']));

	}

	public static function check_and_download_images($case, $url_arr, $drawings = false, $force = false){

		$order = array();

		if(count($url_arr) <= 0){
			return;
		}

		$attachements_arr = $case->get_case_attachments();

		foreach($url_arr as $url){

			if(strpos($url, 'maps.googleapis.com') === false){

				$a_id = array_search(basename($url), $attachements_arr);

				if($a_id){

					$order[] = $a_id;
					unset($attachements_arr[$a_id]);


				}else{

					if($force){
                        echo 'downloading image.';
						$img_id = self::save_remote_image($url,$case->getPostID(), $case->getCaseNumber());
					}else{
                        echo 'Adding image to queue';
						Flexyapress_Media_Queue::add((!$drawings) ? 'photo' : 'drawing',$url,$case->getPostID());
					}

					if($img_id){
						$order[] = $img_id;

						if($force){
							//new Flexyapress_Log('added_to_queue', array((!$drawings) ? 'photo' : 'drawing',$url,$case->getPostID()));
						}

					}else{
						if($force){
							//new Flexyapress_Log('added_to_queue_error', array((!$drawings) ? 'photo' : 'drawing',$url,$case->getPostID()));
						}
					}

				}


			}

		}

        if($drawings){
            $case->setDrawingOrder($order);
            return $case->save();
        }else{
            $case->setImageOrder($order);
            return $case->save();
        }

		if($force){

			if(count($attachements_arr) > 0){
				echo 'Der er billeder til overs.. Sletter<br>';
				echo 'Featured image id: '.get_post_thumbnail_id( $case->getPostID() );
				foreach($attachements_arr as $id => $name){

					if(get_post_thumbnail_id( $case->getPostID() ) != $id){
						if(self::deleteImage($id)){
							echo 'Billede '.$name.' blev slettet<br>';
						}
					}else{
						echo 'Billede er sat som featured.. Skipping<br>';
					}

				}

			}

		}

	}

	public static function check_and_download_single_image($case, $url, $drawings = false){

		$order = (!$drawings) ? $case->getUnserializedImageOrder() : $case->getUnserializedDrawingOrder();

        if(!is_array($order)){
            $order = [];
        }
		$attachements_arr = $case->get_case_attachments();

			if(strpos($url, 'maps.googleapis.com') === false && strpos($url, 'streetview') === false){

				$a_id = array_search(basename($url), $attachements_arr);

				if($a_id){

					$order[] = $a_id;
					unset($attachements_arr[$a_id]);

                    if($drawings){
                        $case->setDrawingOrder($order);
                        return $case->save();
                    }else{
                        $case->setImageOrder($order);
                        return $case->save();
                    }

                    return true;


				}else{

					$img_id = self::save_remote_image($url,$case->getPostID(), $case->getCaseNumber());

					if($img_id){
						$order[] = $img_id;

						if($drawings){
							$case->setDrawingOrder($order);
							return $case->save();
						}else{
							$case->setImageOrder($order);
							return $case->save();
						}


					}else{
						return false;
					}

				}


			}else{
				return true;
			}

	}


	private static function save_remote_image($url, $post_id, $folder_name){
		set_time_limit(30);
		/*
		How To Locally Mirror Remote Images With WordPress
		Source: http://forrst.com/posts/Locally_Mirror_Remote_Images_With_WordPress-XSE
		*/
		// URL of the image you want to mirror. Girl in pink underwear for instance.
		$image = $url;
		// GET request
		$get = wp_remote_get( $image );
		// Check content-type (eg image/png), might want to test & exit if applicable

		$type = pathinfo($image);
        $extension = 'jpg';

        if(strpos($extension, '?') > -1){
            $parts = explode('?', $extension);
            $extension = $parts[0];
        }


		if(empty($extension) || (strtolower($extension) != 'jpg' && strtolower($extension) != 'jpeg' && strtolower($extension) !== 'png')){
			//new Flexyapress_Log('download_fail_wrong_extension', array($image));
			echo 'wrong extension<br>';
			return false;
		}

		$mimetype = array(

			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'jpeg' => 'image/jpeg'

		);

        $_filter = true; // For the anonymous filter callback below.
        add_filter( 'upload_dir', function( $arr ) use( &$_filter, &$folder_name ){
            if ( $_filter ) {
                $folder = '/property-photos/'.$folder_name; // No trailing slash at the end.
                $arr['path'] = $arr['basedir'] . $folder;
                $arr['url'] .= $arr['baseurl'] . $folder;
                //$arr['subdir'] .= $folder;
            }

            return $arr;
        } );


        $filename = $folder_name . '-primary.jpg';

        // Mirror this image in your upload dir
        $mirror = wp_upload_bits(  $filename, '', wp_remote_retrieve_body( $get ) );

        $_filter = false; // Disables the filter.

		// Attachment options
		$attachment = array(
			'post_title'=> basename( $image ),
			'post_mime_type' => $mimetype[strtolower($extension)],
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_author'	 => 0
		);
		// Add the image to your media library (won't be attached to a post)
		$attach_id =  wp_insert_attachment( $attachment, $mirror['file'], $post_id );
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

        foreach ( get_intermediate_image_sizes() as $size ) {
            if ( !in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
                remove_image_size( $size );
            }
        }

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, get_attached_file($attach_id) );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		add_post_meta($attach_id,'flexya-image', true);

		if($attach_id){
			//new Flexyapress_Log('download_success', array($image));
		}else{
			//new Flexyapress_Log('download_fail', array($image, $attach_id));
		}

		return $attach_id;


	}

	private static function deleteImage($id){

		if(isset($id) && is_int($id)){
			return wp_delete_attachment( $id, true );
		}

	}



}