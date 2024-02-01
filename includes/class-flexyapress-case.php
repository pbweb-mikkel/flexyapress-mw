<?php

class Flexyapress_Case{

	private $postID;
	private $caseKey;
	private $caseType;
	private $caseNumber;
	private $description;
	private $description1;
	private $description2;
	private $description3;
	private $description4;
	private $description5;
	private $status;
	private $reserved;
	private $noAdvertisement;
	private $propertyType;
	private $propertyClass;
	private $publishedDate;
	private $soldDate;
	private $underSaleDate;
	private $realtor;
	private $realtorEmail;
	private $realtorName;
	private $realtorPhone;
	private $realtorMobile;
	private $realtorImage;
	private $realtorTitle;
	private $address;
	private $addressFreetext;
	private $floor;
	private $door;
	private $zipcode;
	private $city;
	private $placename;
	private $municipality;
	private $latitude;
	private $longitude;
	private $sizeArea;
	private $sizeAreaTotal;
	private $sizeLand;
	private $sizeLandHa;
	private $sizeBasement;
	private $sizeOtherbuildingsTotal;
	private $sizePatio;
	private $sizeCarport;
	private $sizeGarage;
	private $sizeCommercial;
	private $numberRooms;
	private $numberBedrooms;
	private $numberBathrooms;
	private $numberLivingRooms;
	private $numberFloors;
	private $title;
	private $teaser;
	private $tag;
	private $keywords;
	private $price;
	private $downPayment;
	private $monthlyOwnerExpenses;
	private $monthlyNetPayment;
	private $monthlyGrossPayment;
	private $priceReductionPercent;
	private $priceReductionDate;
	private $primaryPhoto;
	private $primaryPhoto1000;
	private $photos;
    private $imagesExternal;
    private $floorplansExternal;
    private $videosExternal;
	private $photos1000;
	private $thumbnails;
	private $drawings;
	private $videos;
	private $documents;
	private $constructionYear;
	private $reconstructionYear;
	private $energyBrand;
	private $heatingInstallation;
	private $heatingInstallationSuppl;
	private $daysForSale;
	private $attachments;
	private $openhouseActive;
	private $openhouseSignupRequired;
	private $openHouseDate;
	private $openhouseSignupDate;
	private $roadname;
	private $roadnumber;
	private $content;
	private $saleType;
	private $officeId;
	private $imageOrder;
	private $photoTexts;
	private $drawingOrder;
	private $hash;
	private $imageHash;
    private $options;
    private $hidden;
    private $gallery;
    private $drawingsGallery;
    private $openhouseDatesTotal;
    private $shopNo;
    private $presentationUrl;
    private $monthlyRent;
    private $yearlyRent;
    private $yearlyRentPrArea;
    private $connectionFee;
	private $buildings;
    private $customFields;

	/**
	 * Flexyapress_Case constructor.
	 *
	 * @param $id "Post ID (int) or Casekey (string)"
	 *
	 */
	public function __construct($id = null) {

		if(is_numeric($id)){
			$this->setPostID($id);
		}else if(is_string($id)){
			$this->setCaseKey($id);
			$this->setPostID($this->findPostId());
		}
        $this->fetch();
        $this->options = get_option('flexyapress');

	}

	public function fetch(){
		if(!$this->getPostID()){
			return false;
		}

		$meta = get_post_meta($this->getPostID(), null, true);

		foreach ($meta as $key => $value){

            if($key == 'openhouseDatesTotal'){
                $this->setOpenhouseDatesTotal(get_field('openhouseDatesTotal', $this->getPostID()));
                continue;
            }

            if($key == 'imagesExternal'){
                $this->setImagesExternal(get_field('imagesExternal', $this->getPostID()));
                continue;
            }

            if($key == 'floorplansExternal'){
                $this->setFloorplansExternal(get_field('floorplansExternal', $this->getPostID()));
                continue;
            }

            if($key == 'videosExternal'){
                $this->setVideosExternal(get_field('videosExternal', $this->getPostID()));
                continue;
            }

            if($key == 'customCaseFields'){
                $this->setCustomFields(get_field('customCaseFields', $this->getPostID()));
                continue;
            }


			$value = array_shift($value);
			//Go through the easy properties fast
			if(property_exists($this, $key)){
				$funcName = "set".ucfirst($key);
				$this->$funcName($value);
			}
		}

		$this->setDescription(get_post_field('post_content', $this->getPostID()));

	}


    public function get_save_images_locally(){

        return get_option('flexyapress')['save-images-locally'];

    }

	public function save(){

		$postarr = array(

			'ID'			    => $this->getPostID(),
			'post_title'	    => Flexyapress_Helpers::create_post_title($this->getRoadname(), $this->getRoadnumber(), $this->getFloor(), $this->getDoor(), $this->getZipcode(), $this->getCity()),
			'post_name'		    => Flexyapress_Helpers::create_post_slug($this->getRoadname(), $this->getRoadnumber(), $this->getFloor(), $this->getDoor(), $this->getZipcode(), $this->getCity(), $this->getCaseNumber()),
			'post_author'		=>	1,
			'post_type'		    =>	'sag',
			'post_status'	    =>	'publish',
			'post_date'		    =>	date('Y-m-d H:i:s', strtotime($this->getPublishedDate())),
			'post_content'      =>  $this->getDescription(),
			'meta_input'	    =>	array(
										'caseKey'	            => $this->getCaseKey(),
										'caseNumber'            => $this->getCaseNumber(),
										'caseType'              => $this->getCaseType(),
										'status'	            => $this->getStatus(),
										'reserved'		        => $this->getReserved(),
										'noAdvertisement'		=> $this->getNoAdvertisement(),
										'publishedDate'	        => $this->getPublishedDate(),
										'publishedDateEpoch'	=> strtotime($this->getPublishedDate()),
										'soldDate'	            => $this->getSoldDate(),
										'underSaleDate'	            => $this->getUnderSaleDate(),
										'realtor'               => $this->getRealtor(),
										'realtorName'           => $this->getRealtorName(),
										'realtorEmail'           => $this->getRealtorEmail(),
										'realtorPhone'           => $this->getRealtorPhone(),
										'realtorMobile'           => $this->getRealtorMobile(),
										'realtorImage'           => $this->getRealtorImage(),
										'realtorTitle'           => $this->getRealtorTitle(),
										'roadname'		        => $this->getRoadname(),
										'roadnumber'		    => $this->getRoadnumber(),
										'addressFreetext'	    => $this->getAddressFreetext(),
										'address'	            => $this->getAddress(),
										'floor'                 => $this->getFloor(),
										'door'                  => $this->getDoor(),
										'zipcode'               => $this->getZipcode(),
										'city'		            => $this->getCity(),
										'placename'             => $this->getPlacename(),
										'municipality'          => $this->getMunicipality(),
										'latitude'              => $this->getLatitude(),
										'longitude'             => $this->getLongitude(),
										'sizeArea'	            => $this->getSizeArea(),
										'sizeAreaTotal'         => $this->getSizeAreaTotal(),
										'sizeLand'	        => $this->getSizeLand(),
										'sizeLandHa'        => $this->getSizeLandHa(),
										'sizeBasement'	    => $this->getSizeBasement(),
										'sizeOtherbuildingsTotal' => $this->getSizeOtherbuildingsTotal(),
										'sizePatio'             => $this->getSizePatio(),
										'sizeCarport'           => $this->getSizeCarport(),
										'sizeGarage'            => $this->getSizeGarage(),
										'sizeCommercial'        => $this->getSizeCommercial(),
										'numberRooms'           => $this->getNumberRooms(),
										'numberBedrooms'        => $this->getNumberBedrooms(),
										'numberBathrooms'       => $this->getNumberBathrooms(),
										'numberLivingRooms'     => $this->getNumberLivingRooms(),
										'numberFloors'          => $this->getNumberFloors(),
										'title'                 => $this->getTitle(),
										'teaser'                => $this->getTeaser(),
										'tag'                   => $this->getTag(),
										'price'	                => $this->getPrice(),
										'downPayment'           => $this->getDownPayment(),
										'monthlyOwnerExpenses'	=> $this->getMonthlyOwnerExpenses(),
										'monthlyNetPayment'     => $this->getMonthlyNetPayment(),
										'monthlyGrossPayment'   => $this->getMonthlyGrossPayment(),
										'priceReductionPercent' => $this->getPriceReductionPercent(),
										'priceReductionDate'    => $this->getPriceReductionDate(),
										'primaryPhoto'          => $this->getPrimaryPhoto(),
										'primaryPhoto1000'      => $this->getPrimaryPhoto1000(),
										'photos'                => $this->getPhotos(),
                                        'photos1000'            => $this->getPhotos1000(),
										'thumbnails'            => $this->getThumbnails(),
										'drawings'              => $this->getDrawings(),
										'videos'                => $this->getVideos(),
										'documents'             => $this->getDocuments(),
										'constructionYear'	    => $this->getConstructionYear(),
										'reconstructionYear'    => $this->getReconstructionYear(),
										'energyBrand'           => $this->getEnergyBrand(),
										'heatingInstallation'   => $this->getHeatingInstallation(),
										'heatingInstallationSuppl' => $this->getHeatingInstallationSuppl(),
										'daysForSale'           => $this->getDaysForSale(),
										'openhouseActive'       => $this->getOpenhouseActive(),
										'openhouseSignupRequired' => $this->getOpenhouseSignupRequired(),
										'openHouseDate'         => $this->getOpenHouseDate(),
										'openhouseSignupDate'         => $this->getOpenhouseSignupDate(),
										'saleType'              => $this->getSaleType(),
										'propertyType'          => $this->getPropertyType(),
										'propertyClass'          => $this->getPropertyClass(),
										'description1'          => $this->getDescription1(),
										'description2'          => $this->getDescription2(),
										'description3'          => $this->getDescription3(),
										'description4'          => $this->getDescription4(),
										'description5'          => $this->getDescription5(),
										'hash'                  => $this->getHash(),
										'imageHash'             => $this->getImageHash(),
										'photoTexts'             => $this->getPhotoTexts(),
										'shopNo'                => $this->getShopNo(),
										'presentationUrl'       => $this->getPresentationUrl(),
										'monthlyRent'           => $this->getMonthlyRent(),
										'yearlyRent'            => $this->getYearlyRent(),
                                        'yearlyRentPrArea'      => $this->getYearlyRentPrArea(),
                                        'connectionFee'         => $this->getConnectionFee(),
										'_yoast_wpseo_opengraph-image' => array($this->getPrimaryPhoto1000()),
									),
		);

        if($this->getImageOrder()){
            $postarr['meta_input']['imageOrder'] = maybe_unserialize($this->getImageOrder());


            if($this->get_save_images_locally()){
                $postarr['meta_input']['gallery'] = maybe_unserialize($this->getImageOrder());
                $images = maybe_unserialize($this->getImageOrder());
                $imageTexts = $this->getPhotoTexts();

                $featured_image_1 = !empty($images[0]) ? $images[0] : null;
                $featured_image_2 = !empty($images[1]) ? $images[1] : null;;
                $featured_image_3 = !empty($images[2]) ? $images[2] : null;;
                $featured_image_4 = !empty($images[3]) ? $images[3] : null;;
                $featured_image_5 = !empty($images[4]) ? $images[4] : null;;

                foreach ($images as $index => $id){

                    if($imageTexts[$index] === '1'){
                        $featured_image_1 = $id;
                    }else if($imageTexts[$index] === '2'){
                        $featured_image_2 = $id;
                    }else if($imageTexts[$index] === '3'){
                        $featured_image_3 = $id;
                    }else if($imageTexts[$index] === '4'){
                        $featured_image_4 = $id;
                    }else if($imageTexts[$index] === '5'){
                        $featured_image_5 = $id;
                    }
                }


                $postarr['meta_input']['featuredimage_1'] = $featured_image_1;
                $postarr['meta_input']['featuredimage_2'] = $featured_image_2;
                $postarr['meta_input']['featuredimage_3'] = $featured_image_3;
                $postarr['meta_input']['featuredimage_4'] = $featured_image_4;
                $postarr['meta_input']['featuredimage_5'] = $featured_image_5;
            }

        }

        if($this->getDrawingOrder()){
            $postarr['meta_input']['drawingOrder'] = maybe_unserialize($this->getDrawingOrder());
            if($this->get_save_images_locally()){
                $postarr['meta_input']['drawingsGallery'] = maybe_unserialize($this->getDrawingOrder());
            }
        }

		$id = wp_insert_post($postarr);

        update_field('openhouseDatesTotal', $this->getOpenhouseDatesTotal(), $id);
        update_field('buildings', $this->getBuildings(), $id);
        update_field('customCaseFields', $this->getCustomFields(), $id);

		return $id;

	}

	public function get_case_attachments($use_url = false){
		$attachements = get_attached_media( 'image', $this->getPostID() );
		$attachements_arr = array();

		foreach($attachements as $att){

			if($use_url){
				$attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
			}else{
				$attachements_arr[$att->ID] = $att->post_title;
			}

		}

		return $attachements_arr;
	}

	public function delete(){

		if(!$this->getPostID() || $this->getHidden()){
			return false;
		}
        $log = new Flexyapress_Log('delete_case', $this->getAddress());
		$this->deleteImages();
		$deleted_post = wp_delete_post( $this->getPostID(), true );
		return $deleted_post;

	}

	public function findPostId(){

		if($this->getPostID()){
			return $this->getPostID();
		}

		if($this->getCaseKey()){

			$search = Flexyapress_Helpers::get_post_by_meta_value('sag', 'caseKey', $this->getCaseKey());
			$id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;
			return $id;

		}

	}

	public function deleteImages($keep_thumb = false){
		if(!$this->getPostID()){
			return false;
		}

		$attachments = get_children( array(
			'post_type' => 'attachment',
			'post_parent' => (int) $this->getPostID(),
			'numberposts' => -1
		));

        $thumb = get_post_thumbnail_id($this->getPostID());


		if(count($attachments) > 0){
			foreach($attachments as $attachment){

                if($keep_thumb && $thumb === $attachment->ID){
                    continue;
                }

				wp_delete_attachment( $attachment->ID, true );
			}

            update_post_meta($this->getPostID(), 'imageOrder', null);
            update_post_meta($this->getPostID(), 'drawingOrder', null);

            if($this->getCaseNumber() && !$keep_thumb){
                $upload_dir = wp_upload_dir();
                $folder = $upload_dir['basedir'].'/property-photos/'.$this->getCaseNumber();
                $exists = file_exists($folder);
                if($exists){
                    //Gets files from the directory
                    $files = glob($folder."/*.*");
                    if($files){
                        //deletes files
                        array_map('unlink', $files);
                    }
                    //Deletes folder
                    rmdir($folder);
                }
            }

			return true;
		}else{
			return false;
		}

	}

	public function getOldHash(){
		if($this->getPostID()){
			return get_field('hash', $this->getPostID());
		}
		return false;
	}

    public function getOldImageHash(){
        if($this->getPostID()){
            return get_post_meta($this->getPostID(), 'imageHash', true);
        }
        return false;
    }

	public function get_attachments($post_id, $use_url = false){
		$attachements = get_attached_media( 'image', $post_id );

		$attachements_arr = array();

		foreach($attachements as $att){

			if($use_url){
				$attachements_arr[$att->ID] = wp_get_attachment_image_src($att->ID, 'full')[0];
			}else{
				$attachements_arr[$att->ID] = $att->post_title;
			}

		}

		return $attachements_arr;

	}

    /**
     * @return mixed
     */
    public function getCaseType()
    {
        return $this->caseType;
    }

    /**
     * @param mixed $caseType
     */
    public function setCaseType($caseType)
    {
        $this->caseType = $caseType;
    }

    public function getPrettyCaseType(){
        $types = [
            'SalesCase' => 'Salg',
            'RentalCase' => 'Udlejning',
            'BusinessSalesCase' => 'Salg',
            'BusinessRentalCase' => 'Udlejning',
            'LoanCase'          => 'Udlån'
        ];
        if(!$this->getCaseType() || !array_key_exists($this->getCaseType(), $types)){
            return '';
        }

        return $types[$this->getCaseType()];

    }

    public function isRentalCase(){
        return in_array($this->getCaseType(), ['RentalCase', 'BusinessRentalCase']);
    }

    /**
     * @return mixed
     */
    public function getOpenhouseDatesTotal()
    {
        return $this->openhouseDatesTotal;
    }

    /**
     * @param mixed $openhouseDatesTotal
     */
    public function setOpenhouseDatesTotal($openhouseDatesTotal)
    {
        $this->openhouseDatesTotal = $openhouseDatesTotal;
    }

    public function getNextOpenHouse(){
        $return = null;
        if(!empty($this->getOpenhouseDatesTotal())){
            $return = $this->getOpenhouseDatesTotal()[0];
        }
        return $return;
    }

	/**
	 * @return mixed
	 */
	public function getPostID() {
		return $this->postID;
	}

	/**
	 * @param mixed $postID
	 */
	public function setPostID( $postID ) {
		$this->postID = $postID;
	}

	/**
	 * @return mixed
	 */
	public function getCaseKey() {
		return $this->caseKey;
	}

	/**
	 * @param mixed $caseKey
	 */
	public function setCaseKey( $caseKey ) {
		$this->caseKey = $caseKey;
	}

	/**
	 * @return mixed
	 */
	public function getCaseNumber() {
		return $this->caseNumber;
	}

	/**
	 * @param mixed $caseNumber
	 */
	public function setCaseNumber( $caseNumber ) {
		$this->caseNumber = $caseNumber;
	}

	/**
	 * @return mixed
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param mixed $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	public function getReserved() {
		return $this->reserved;
	}

	/**
	 * @param mixed $reserved
	 */
	public function setReserved( $reserved ) {
		$this->reserved = $reserved;
	}

    /**
     * @return mixed
     */
    public function getNoAdvertisement()
    {
        return $this->noAdvertisement;
    }

    /**
     * @param mixed $noAdvertisement
     */
    public function setNoAdvertisement($noAdvertisement)
    {
        $this->noAdvertisement = $noAdvertisement;
    }

	public function isActive(){
		return ($this->getStatus() == 'ACTIVE');
	}

	public function printFlag($is_single = false){

		/*
		 * Flag Priority:
		 * 1: Solgt
		 * 2: Købsaftale underskrevet
		 * 3: Åbent hus
		 * 3: Custom
		 * 4: Ny pris
		 * 5: Nyhed
		 */

		$c = '';
		if($this->getStatus() == 'SOLD'){
			$c .= '<div class="flag flag-sold">';
				$c .= (in_array($this->getCaseType(), ['RentalCase', 'BusinessRentalCase'])) ? __('Udlejet', 'flexyapress') : __('Solgt', 'flexyapress');
			$c .= '</div>';
		}else if($this->getReserved()) {
            $c .= '<div class="flag flag-reserved">';
            $c .= __('Købsaftale underskrevet', 'flexyapress');
            $c .= '</div>';
        }else if($is_single == false && $this->getOpenhouseActive() && $this->getOpenHouseDate() && apply_filters('pb_allow_oh_on_list', true)) {
            $c .= '<div class="flag flag-openhouse">';
            if($this->getOpenhouseSignupRequired() && apply_filters('pb_allow_oh_signuptext_on_list', true)){
                $c .= __('Åbent hus med tilmelding: ', 'flexyapress');
            }else{
                $c .= __('Åbent hus: ', 'flexyapress');
            }
            $c .= $this->getOpenHouseDate();
            $c .= '</div>';
		}else if($this->getTag()){
			$c .= '<div class="flag flag-custom">';
				$c .= $this->getTag();
			$c .= '</div>';
		}else if(strtotime($this->getPublishedDate()) > strtotime('-14 days')){
            $c .= '<div class="flag flag-new">';
            $c .= __('Nyhed', 'flexyapress');
            $c .= '</div>';
        }else if($this->getPriceReductionDate() && $this->getPriceReductionDate() <> $this->getPublishedDate() && strtotime($this->getPriceReductionDate()) > strtotime('-14 days')){
			$c .= '<div class="flag flag-newprice">';
				$c .= __('Ny pris', 'flexyapress');
			$c .= '</div>';
		}

		return $c;

	}

	public function printSpecItem($title, $value){

		if(method_exists($this, $value)){
			$value = $this->$value();
		}else{
			return;
		}
		if(!$value){
			return;
		}
		ob_start();
			echo '<div class="spec-item">';
				echo '<div class="spec-title">'.__($title,'flexyapress').'</div>';
				echo '<div class="spec-value">'.$value.'</div>';
            echo '</div>';
		$c = ob_get_clean();
		return $c;

	}

	public function printOpenHouseFlag($is_single = false){

		$c = '';
		if($this->isActive() && !$this->getReserved() && $this->getOpenhouseActive() && $this->getOpenHouseDate()){
			$c .= '<div class="flag flag-openhouse">';
                $c .= '<div class="open-house-title">'.__(($this->getOpenhouseSignupRequired() ? 'Åbent hus med tilmelding' : 'Åbent hus'), 'flexyapress').'</div>';
				$c .= '<div class="open-house-date">'.$this->getOpenHouseDate().'</div>';
				if($is_single && $this->getOpenhouseSignupRequired()){
					$c .= '<div class="open-house-signup popup-btn" data-target="order-openhouse-signup">Tilmeld</div>';
				}
			$c .= '</div>';
		}

		return $c;

	}

	/**
	 * @return mixed
	 */
	public function getPropertyType() {
		return $this->propertyType;
	}

    /**
     * @return mixed
     */
    public function getPrettyPropertyType() {
        if($this->propertyType){
            return Flexyapress_Helpers::property_type_nice_name($this->propertyType);
        }else{
            return '';
        }
    }

	/**
	 * @param mixed $propertyType
	 */
	public function setPropertyType( $propertyType ) {
		$this->propertyType = $propertyType;
	}

	/**
	 * @return mixed
	 */
	public function getPropertyClass() {
		return $this->propertyClass;
	}

	/**
	 * @param mixed $propertyClass
	 */
	public function setPropertyClass( $propertyClass ) {
		$this->propertyClass = $propertyClass;
	}


	/**
	 * @return mixed
	 */
	public function getPublishedDate() {
		if(!$this->publishedDate){
			return date('d-m-Y H:i:s', strtotime("now"));
		}
		return $this->publishedDate;
	}

	/**
	 * @param mixed $publishedDate
	 */
	public function setPublishedDate( $publishedDate ) {
		$this->publishedDate = $publishedDate;
	}

	/**
	 * @return mixed
	 */
	public function getSoldDate() {
		return $this->soldDate;
	}

	/**
	 * @param mixed $soldDate
	 */
	public function setSoldDate( $soldDate ) {
		$this->soldDate = $soldDate;
	}

    /**
     * @return mixed
     */
    public function getUnderSaleDate()
    {
        return $this->underSaleDate;
    }

    /**
     * @param mixed $underSaleDate
     */
    public function setUnderSaleDate($underSaleDate)
    {
        $this->underSaleDate = $underSaleDate;
    }


	/**
	 * @return mixed
	 */
	public function getRealtorEmail() {
		return $this->realtorEmail;
	}

	/**
	 * @param mixed $realtorEmail
	 */
	public function setRealtorEmail( $realtorEmail ) {
		$this->realtorEmail = $realtorEmail;
	}

    /**
     * @return mixed
     */
    public function getRealtorImage()
    {
        return $this->realtorImage;
    }

    /**
     * @param mixed $realtorImage
     */
    public function setRealtorImage($realtorImage)
    {
        $this->realtorImage = $realtorImage;
    }


	/**
	 * @return mixed
	 */
	public function getRealtorName() {
		return $this->realtorName;
	}

	/**
	 * @param mixed $realtorName
	 */
	public function setRealtorName( $realtorName ) {
		$this->realtorName = $realtorName;
	}

	/**
	 * @return mixed
	 */
	public function getRealtorPhone($divide = false) {
        if($divide && $this->realtorPhone){
            if(strlen($this->realtorPhone) == 8){
                return substr($this->realtorPhone, 0, 4).' '.substr($this->realtorPhone, 4, 4);
            }else if(strlen($this->realtorPhone) == 11){
                return substr($this->realtorPhone, 0, 3).' '.substr($this->realtorPhone, 3, 4).' '.substr($this->realtorPhone, 7, 4);
            }

        }else{
            return $this->realtorPhone;
        }
	}

	/**
	 * @param mixed $realtorPhone
	 */
	public function setRealtorPhone( $realtorPhone ) {
		$this->realtorPhone = $realtorPhone;
	}

    /**
     * @return mixed
     */
    public function getRealtorMobile()
    {
        return $this->realtorMobile;
    }

    /**
     * @param mixed $realtorMobile
     */
    public function setRealtorMobile($realtorMobile)
    {
        $this->realtorMobile = $realtorMobile;
    }


    /**
     * @return mixed
     */
    public function getRealtorTitle()
    {
        return $this->realtorTitle;
    }

    /**
     * @param mixed $realtorTitle
     */
    public function setRealtorTitle($realtorTitle)
    {
        $this->realtorTitle = $realtorTitle;
    }


	/**
	 * @return mixed
	 */
	public function getRealtor() {
		return $this->realtor;
	}

	public function getRealtorInfo(){

		$realtor = new Flexyapress_Realtor();
		$id = $this->getRealtor();

		if(!$id && $this->getRealtorEmail()){
			$id = Flexyapress_Realtor::findIdByEmail($this->getRealtorEmail());
		}
		if($id){
			$realtor = new Flexyapress_Realtor($id);
		}

		if(!$realtor->getId()){
			$realtor->setRealtorName($this->getRealtorName());
			$realtor->setRealtorEmail($this->getRealtorEmail());
			$realtor->setRealtorPhone($this->getRealtorPhone());
		}else{
			$realtor->fetch();
		}

		return $realtor;

	}

	/**
	 * @param mixed $realtor
	 */
	public function setRealtor( $realtor ) {
		$this->realtor = $realtor;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @return mixed
	 */
	public function getFullAddress() {
		return $this->getAddress() .'<br>'.$this->getZipcode().' '.$this->getCity();
	}

    /**
     * @return mixed
     */
    public function getSimpleAddress() {
        return $this->getAddress();

        sprintf( '%s %s%s %s',
            $this->getRoadname(),
            $this->getRoadnumber(),
            ! empty( $this->getFloor() ) ? ', ' . str_replace('..', '.',strtolower( $this->getFloor() ).'.') : '',
            ! empty( $this->getDoor() ) ? str_replace('..', '.',strtolower( $this->getDoor() ).'.') : ''
        );

    }

    /**
     * @return mixed
     */
    public function getSimpleAddressWithoutCity() {

        return sprintf( '%s %s%s %s',
            $this->getRoadname(),
            $this->getRoadnumber(),
            ! empty( $this->getFloor() ) ? ', ' . str_replace('..', '.',strtolower( $this->getFloor() ).'.') : '',
            ! empty( $this->getDoor() ) ? str_replace('..', '.',strtolower( $this->getDoor() ).'.') : ''
        );

    }

	/**
	 * @return mixed
	 */
	public function getMapAddress() {
		return $this->getRoadname().' '.$this->getRoadnumber().' '.$this->getZipcode().' '.$this->getCity();
	}

    public function getMapIframe($lazyload = true, $id = ''){
        if($lazyload){
            return '<iframe '.(!empty($id) ? 'id="'.$id.'"' : '').' data-src="https://www.google.com/maps/embed/v1/place?key='. $this->options['maps-api-key'] .'&q='.str_replace(' ', '+', $this->getMapAddress()).'" style="border:0;"></iframe>';
        }else{
            return '<iframe '.(!empty($id) ? 'id="'.$id.'"' : '').' src="https://www.google.com/maps/embed/v1/place?key='. $this->options['maps-api-key'] .'&q='.str_replace(' ', '+', $this->getMapAddress()).'" style="border:0;"></iframe>';
        }
    }

	/**
	 * @param mixed $address
	 */
	public function setAddress( $address ) {
		$this->address = $address;
	}

	/**
	 * @return mixed
	 */
	public function getAddressFreetext() {
		return $this->addressFreetext;
	}

	/**
	 * @param mixed $addressFreetext
	 */
	public function setAddressFreetext( $addressFreetext ) {
		$this->addressFreetext = $addressFreetext;
	}

	/**
	 * @return mixed
	 */
	public function getFloor() {
		return $this->floor;
	}

	/**
	 * @param mixed $floor
	 */
	public function setFloor( $floor ) {
		$this->floor = $floor;
	}

	/**
	 * @return mixed
	 */
	public function getDoor() {
		return $this->door;
	}

	/**
	 * @param mixed $door
	 */
	public function setDoor( $door ) {
		$this->door = $door;
	}

	/**
	 * @return mixed
	 */
	public function getZipcode() {
		return $this->zipcode;
	}

	/**
	 * @param mixed $zipcode
	 */
	public function setZipcode( $zipcode ) {
		$this->zipcode = $zipcode;
	}

	/**
	 * @return mixed
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity( $city ) {
		$this->city = $city;
	}

	/**
	 * @return mixed
	 */
	public function getPlacename() {
		return $this->placename;
	}

	/**
	 * @param mixed $placename
	 */
	public function setPlacename( $placename ) {
		$this->placename = $placename;
	}

	/**
	 * @return mixed
	 */
	public function getMunicipality() {
		return $this->municipality;
	}

	/**
	 * @param mixed $municipality
	 */
	public function setMunicipality( $municipality ) {
		$this->municipality = $municipality;
	}

	/**
	 * @return mixed
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * @param mixed $latitude
	 */
	public function setLatitude( $latitude ) {
		$this->latitude = $latitude;
	}

	/**
	 * @return mixed
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * @param mixed $longitude
	 */
	public function setLongitude( $longitude ) {
		$this->longitude = $longitude;
	}

	/**
	 * @return mixed
	 */
	public function getSizeArea() {
		return $this->sizeArea;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeArea() {
		if(!$this->sizeArea){
			return;
		}
		return $this->sizeArea.' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeArea
	 */
	public function setSizeArea( $sizeArea ) {
		$this->sizeArea = $sizeArea;
	}

	/**
	 * @return mixed
	 */
	public function getSizeAreaTotal() {
		return $this->sizeAreaTotal;
	}

	/**
	 * @param mixed $sizeAreaTotal
	 */
	public function setSizeAreaTotal( $sizeAreaTotal ) {
		$this->sizeAreaTotal = $sizeAreaTotal;
	}

	/**
	 * @return mixed
	 */
	public function getSizeLand() {
		return $this->sizeLand;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeLand() {

		if(!$this->sizeLand){
			return;
		}
		return $this->sizeLand . ' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeLand
	 */
	public function setSizeLand( $sizeLand ) {
		$this->sizeLand = $sizeLand;
	}

	/**
	 * @return mixed
	 */
	public function getSizeLandHa() {
		return $this->sizeLandHa;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeLandHa() {
		if(!$this->sizeLandHa){
			return;
		}
		return $this->sizeLandHa . ' ha';
	}

	/**
	 * @param mixed $sizeLandHa
	 */
	public function setSizeLandHa( $sizeLandHa ) {
		$this->sizeLandHa = $sizeLandHa;
	}


	/**
	 * @return mixed
	 */
	public function getSizeBasement() {
		return $this->sizeBasement;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeBasement() {
		if(!$this->sizeBasement){
			return;
		}
		return $this->sizeBasement . ' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeBasement
	 */
	public function setSizeBasement( $sizeBasement ) {
		$this->sizeBasement = $sizeBasement;
	}

	/**
	 * @return mixed
	 */
	public function getSizeOtherbuildingsTotal() {
		return $this->sizeOtherbuildingsTotal;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyOtherbuildingsTotal() {
		if(!$this->sizeOtherbuildingsTotal){
			return;
		}
		return $this->sizeOtherbuildingsTotal.' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeOtherbuildingsTotal
	 */
	public function setSizeOtherbuildingsTotal( $sizeOtherbuildingsTotal ) {
		$this->sizeOtherbuildingsTotal = $sizeOtherbuildingsTotal;
	}

	/**
	 * @return mixed
	 */
	public function getSizePatio() {
		return $this->sizePatio;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizePatio() {
		if(!$this->sizePatio){
			return;
		}
		return $this->sizePatio.' m<sup>2</sup>';
	}


	/**
	 * @param mixed $sizePatio
	 */
	public function setSizePatio( $sizePatio ) {
		$this->sizePatio = $sizePatio;
	}

	/**
	 * @return mixed
	 */
	public function getSizeCarport() {
		return $this->sizeCarport;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeCarport() {
		if(!$this->sizeCarport){
			return;
		}
		return $this->sizeCarport.' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeCarport
	 */
	public function setSizeCarport( $sizeCarport ) {
		$this->sizeCarport = $sizeCarport;
	}

	/**
	 * @return mixed
	 */
	public function getSizeGarage() {
		return $this->sizeGarage;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeGarage() {
		if(!$this->sizeGarage){
			return;
		}
		return $this->sizeGarage.' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeGarage
	 */
	public function setSizeGarage( $sizeGarage ) {
		$this->sizeGarage = $sizeGarage;
	}

	/**
	 * @return mixed
	 */
	public function getSizeCommercial() {
		return $this->sizeCommercial;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySizeCommercial() {
		if(!$this->sizeCommercial){
			return;
		}
		return $this->sizeCommercial.' m<sup>2</sup>';
	}

	/**
	 * @param mixed $sizeCommercial
	 */
	public function setSizeCommercial( $sizeCommercial ) {
		$this->sizeCommercial = $sizeCommercial;
	}

	/**
	 * @return mixed
	 */
	public function getNumberRooms() {
		return $this->numberRooms;
	}

	/**
	 * @param mixed $numberRooms
	 */
	public function setNumberRooms( $numberRooms ) {
		$this->numberRooms = $numberRooms;
	}

	/**
	 * @return mixed
	 */
	public function getNumberBedrooms() {
		return $this->numberBedrooms;
	}

	/**
	 * @param mixed $numberBedrooms
	 */
	public function setNumberBedrooms( $numberBedrooms ) {
		$this->numberBedrooms = $numberBedrooms;
	}

	/**
	 * @return mixed
	 */
	public function getNumberBathrooms() {
		return $this->numberBathrooms;
	}

	/**
	 * @param mixed $numberBathrooms
	 */
	public function setNumberBathrooms( $numberBathrooms ) {
		$this->numberBathrooms = $numberBathrooms;
	}

	/**
	 * @return mixed
	 */
	public function getNumberLivingRooms() {
		return $this->numberLivingRooms;
	}

	/**
	 * @param mixed $numberLivingRooms
	 */
	public function setNumberLivingRooms( $numberLivingRooms ) {
		$this->numberLivingRooms = $numberLivingRooms;
	}

	/**
	 * @return mixed
	 */
	public function getNumberFloors() {
		return $this->numberFloors;
	}

	/**
	 * @param mixed $numberFloors
	 */
	public function setNumberFloors( $numberFloors ) {
		$this->numberFloors = $numberFloors;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle( $title ) {
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getTeaser() {
		return $this->teaser;
	}

	/**
	 * @param mixed $teaser
	 */
	public function setTeaser( $teaser ) {
		$this->teaser = $teaser;
	}

	/**
	 * @return mixed
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * @param mixed $tag
	 */
	public function setTag( $tag ) {
		$this->tag = $tag;
	}

	/**
	 * @return mixed
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * @param mixed $keywords
	 */
	public function setKeywords( $keywords ) {
		$this->keywords = $keywords;
	}

	/**
	 * @return mixed
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return string
	 */
	public function getPrettyPrice() {
		if(!$this->price){
			return;
		}
		return number_format($this->price, 0, ',', '.');
	}

	/**
	 * @param mixed $price
	 */
	public function setPrice( $price ) {
		$this->price = $price;
	}

	/**
	 * @return mixed
	 */
	public function getDownPayment() {
		return $this->downPayment;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyDownPayment() {
		if(!$this->downPayment){
			return;
		}
		return number_format($this->downPayment, 0, ',', '.');
	}

	/**
	 * @param mixed $downPayment
	 */
	public function setDownPayment( $downPayment ) {
		$this->downPayment = $downPayment;
	}

	/**
	 * @return mixed
	 */
	public function getMonthlyOwnerExpenses() {
		return $this->monthlyOwnerExpenses;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyMonthlyOwnerExpenses() {
		if(!$this->monthlyOwnerExpenses){
			return;
		}
		return number_format($this->monthlyOwnerExpenses, 0, ',', '.');
	}

	/**
	 * @return mixed
	 */
	public function getPrettyGrossNettoMonthlyOwnerExpenses() {
		if(!$this->monthlyNetPayment || !$this->monthlyGrossPayment){
			return;
		}
		return number_format($this->monthlyGrossPayment, 0, ',', '.') . ' / '. number_format($this->monthlyNetPayment, 0, ',', '.');
	}


	/**
	 * @param mixed $monthlyOwnerExpenses
	 */
	public function setMonthlyOwnerExpenses( $monthlyOwnerExpenses ) {
		$this->monthlyOwnerExpenses = $monthlyOwnerExpenses;
	}

	/**
	 * @return mixed
	 */
	public function getMonthlyNetPayment() {
		return $this->monthlyNetPayment;
	}

	/**
	 * @param mixed $monthlyNetPayment
	 */
	public function setMonthlyNetPayment( $monthlyNetPayment ) {
		$this->monthlyNetPayment = $monthlyNetPayment;
	}

	/**
	 * @return mixed
	 */
	public function getMonthlyGrossPayment() {
		return $this->monthlyGrossPayment;
	}

	/**
	 * @param mixed $monthlyGrossPayment
	 */
	public function setMonthlyGrossPayment( $monthlyGrossPayment ) {
		$this->monthlyGrossPayment = $monthlyGrossPayment;
	}

	/**
	 * @return mixed
	 */
	public function getPriceReductionPercent() {
		return $this->priceReductionPercent;
	}

	/**
	 * @param mixed $priceReductionPercent
	 */
	public function setPriceReductionPercent( $priceReductionPercent ) {
		$this->priceReductionPercent = $priceReductionPercent;
	}

	/**
	 * @return mixed
	 */
	public function getPriceReductionDate() {
		return $this->priceReductionDate;
	}

	/**
	 * @param mixed $priceReductionDate
	 */
	public function setPriceReductionDate( $priceReductionDate ) {
		$this->priceReductionDate = $priceReductionDate;
	}

	/**
	 * @return mixed
	 */
	public function getPrimaryPhoto() {
        if(!$this->primaryPhoto){
            return WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png';
        }
		return $this->primaryPhoto;
	}

	/**
	 * @param mixed $primaryPhoto
	 */
	public function setPrimaryPhoto( $primaryPhoto ) {
		$primaryPhoto = empty($primaryPhoto) ?  WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png' : $primaryPhoto;
		$this->primaryPhoto = $primaryPhoto;
	}

	/**
	 * @return mixed
	 */
	public function getPrimaryPhoto1000() {
        if(!$this->primaryPhoto1000){
            return WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png';
        }
		return $this->primaryPhoto1000;
	}

	/**
	 * @param mixed $primaryPhoto
	 */
	public function setPrimaryPhoto1000( $primaryPhoto ) {
		$primaryPhoto = empty($primaryPhoto) ?  WP_PLUGIN_URL.'/flexyapress-mw/public/img/billede-mangler.png' : $primaryPhoto;
		$this->primaryPhoto1000 = $primaryPhoto;
	}

	/**
	 * @return mixed
	 */
	public function getPhotos() {
		return $this->photos;
	}

	/**
	 * @return mixed
	 */
	public function getUnserializedPhotos() {
        $images = [];
		if($this->getImagesExternal()){
            foreach ($this->getImagesExternal() as $img){
                $images[] = $img['url'];
            }
		}
        return $images;
	}

    /**
     * @return mixed
     */
    public function getPhotos1000() {
        return $this->photos1000;
    }

    /**
     * @return mixed
     */
    public function getImagesExternal()
    {
        return $this->imagesExternal;
    }

    /**
     * @param mixed $images_external
     */
    public function setImagesExternal($imagesExternal)
    {
        $this->imagesExternal = $imagesExternal;
    }

    /**
     * @return mixed
     */
    public function getFloorplansExternal()
    {
        return $this->floorplansExternal;
    }

    /**
     * @param mixed $floorplans_external
     */
    public function setFloorplansExternal($floorplansExternal)
    {
        $this->floorplansExternal = $floorplansExternal;
    }

    /**
     * @return mixed
     */
    public function getVideosExternal()
    {
        return $this->videosExternal;
    }

    /**
     * @param mixed $videosExternal
     */
    public function setVideosExternal($videosExternal)
    {
        $this->videosExternal = $videosExternal;
    }


    /**
     * @return mixed
     */
    public function setPhotos1000($photos) {
        $this->photos1000 = $photos;
    }



    /**
     * @return mixed
     */
    public function getUnserializedPhotos1000() {
        $images = [];
        if($this->getImagesExternal()){
            foreach ($this->getImagesExternal() as $img){
                $images[] = $img['url_presentation'];
            }
        }
        return $images;
    }


	/**
	 * @param mixed $photos
	 */
	public function setPhotos( $photos ) {
		$this->photos = $photos;
	}

	/**
	 * @return mixed
	 */
	public function getThumbnails() {
		return $this->thumbnails;
	}


	/**
	 * @return mixed
	 */
	public function getUnserializedThumbnails() {
        $images = [];
        if($this->getImagesExternal()){
            foreach ($this->getImagesExternal() as $img){
                $images[] = $img['url_thumbnail'];
            }
        }
        return $images;
	}


	/**
	 * @param mixed $thumbnails
	 */
	public function setThumbnails( $thumbnails ) {
		$this->thumbnails = $thumbnails;
	}



	/**
	 * @return mixed
	 */
	public function getDrawings() {
		return $this->drawings;
	}

	/**
	 * @return mixed
	 */
	public function getUnserializedDrawings() {
        $images = [];
        if($this->getFloorplansExternal()){
            foreach ($this->getFloorplansExternal() as $img){
                $images[] = $img['url'];
            }
        }
        return $images;
	}


	/**
	 * @param mixed $drawings
	 */
	public function setDrawings( $drawings ) {
		$this->drawings = $drawings;
	}

	/**
	 * @return mixed
	 */
	public function getVideos() {
		return $this->videos;
	}

	/**
	 * @return mixed
	 */
	public function getUnserializedVideos() {
        if($this->getVideosExternal()){
            return $this->getVideosExternal();
        }
		if(is_serialized($this->videos)){
			return unserialize($this->videos);
		}if(is_array($this->videos)){
			return $this->videos;
		}else{
			return array();
		}
	}


	/**
	 * @param mixed $videos
	 */
	public function setVideos( $videos ) {
		$this->videos = $videos;
	}

    /**
     * @return mixed
     */
    public function getDocuments() {
        if(is_serialized($this->documents)){
            return unserialize($this->documents);
        }
        return $this->documents;
    }

	/**
	 * @param mixed $documents
	 */
	public function setDocuments( $documents ) {
		$this->documents = $documents;
	}

	/**
	 * @return mixed
	 */
	public function getConstructionYear() {
		return $this->constructionYear;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyConstructionYear() {
		if($this->getReconstructionYear()){
			return $this->getConstructionYear().'/'.$this->getReconstructionYear();
		}else{

		}
		return $this->getConstructionYear();
	}

	/**
	 * @param mixed $constructionYear
	 */
	public function setConstructionYear( $constructionYear ) {
		$this->constructionYear = $constructionYear;
	}

	/**
	 * @return mixed
	 */
	public function getReconstructionYear() {
		return $this->reconstructionYear;
	}

	/**
	 * @param mixed $reconstructionYear
	 */
	public function setReconstructionYear( $reconstructionYear ) {
		$this->reconstructionYear = $reconstructionYear;
	}

	/**
	 * @return mixed
	 */
	public function getEnergyBrand() {
		return $this->energyBrand;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyEnergyBrand() {
		return ($this->energyBrand) ?: __('Ikke oplyst', 'flexyapress');
	}

	/**
	 * @param mixed $energyBrand
	 */
	public function setEnergyBrand( $energyBrand ) {
		$this->energyBrand = $energyBrand;
	}

	/**
	 * @return mixed
	 */
	public function getHeatingInstallation() {
		return $this->heatingInstallation;
	}

	/**
	 * @param mixed $heatingInstallation
	 */
	public function setHeatingInstallation( $heatingInstallation ) {
		$this->heatingInstallation = $heatingInstallation;
	}

	/**
	 * @return mixed
	 */
	public function getHeatingInstallationSuppl() {
		return $this->heatingInstallationSuppl;
	}

	/**
	 * @param mixed $heatingInstallationSuppl
	 */
	public function setHeatingInstallationSuppl( $heatingInstallationSuppl ) {
		$this->heatingInstallationSuppl = $heatingInstallationSuppl;
	}

	/**
	 * @return mixed
	 */
	public function getDaysForSale() {
		return $this->daysForSale;
	}

	/**
	 * @param mixed $daysForSale
	 */
	public function setDaysForSale( $daysForSale ) {
		$this->daysForSale = $daysForSale;
	}

	/**
	 * @return mixed
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * @param mixed $attachments
	 */
	public function setAttachments( $attachments ) {
		$this->attachments = $attachments;
	}

	/**
	 * @return mixed
	 */
	public function getOpenhouseActive() {
		return $this->openhouseActive;
	}

	/**
	 * @param mixed $openHouseActive
	 */
	public function setOpenhouseActive( $openHouseActive ) {
		$this->openhouseActive = $openHouseActive;
	}

	/**
	 * @return mixed
	 */
	public function getOpenhouseSignupRequired() {
		return $this->openhouseSignupRequired;
	}

	/**
	 * @param mixed $openhouseSignupRequired
	 */
	public function setOpenhouseSignupRequired( $openhouseSignupRequired ) {
		$this->openhouseSignupRequired = $openhouseSignupRequired;
	}

	/**
	 * @return mixed
	 */
	public function getOpenHouseDate() {
		return $this->openHouseDate;
	}

	/**
	 * @param mixed $openHouseDate
	 */
	public function setOpenHouseDate( $openHouseDate ) {
		if(is_array($openHouseDate) && count($openHouseDate) > 0){
			$openHouseDate = $openHouseDate[0];
		}else if(!$openHouseDate){
            $openHouseDate = null;
        }
		$this->openHouseDate = $openHouseDate;
	}

    /**
     * @return mixed
     */
    public function getOpenhouseSignupDate()
    {
        return $this->openhouseSignupDate;
    }

    /**
     * @param mixed $openHouseSignupDate
     */
    public function setOpenhouseSignupDate($openHouseSignupDate)
    {
        $this->openhouseSignupDate = $openHouseSignupDate;
    }

    public function getPrettyOpenhouseSignupDate(){
        $date = $this->getOpenhouseSignupDate();
        date_default_timezone_set('Europe/Copenhagen');
        if(!$date){
            return '';
        }

        $days = [
            __('Søndag', 'pb'),
            __('Mandag', 'pb'),
            __('Tirsdag', 'pb'),
            __('Onsdag', 'pb'),
            __('Torsdag', 'pb'),
            __('Fredag', 'pb'),
            __('Lørdag', 'pb'),
        ];

        $datestring = strtotime($date);

        $day = date('w', $datestring);
        $date = date('d/m', $datestring);
        $time = date('H:i', $datestring);

        return sprintf('%s d. %s kl. %s', $days[$day], $date, $time);

    }

	/**
	 * @return mixed
	 */
	public function getRoadname() {
		return $this->roadname;
	}

	/**
	 * @param mixed $roadname
	 */
	public function setRoadname( $roadname ) {
		$this->roadname = $roadname;
	}

	/**
	 * @return mixed
	 */
	public function getRoadnumber() {
		return $this->roadnumber;
	}

	/**
	 * @param mixed $roadnumber
	 */
	public function setRoadnumber( $roadnumber ) {
		$this->roadnumber = $roadnumber;
	}

	/**
	 * @return mixed
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param mixed $content
	 */
	public function setContent( $content ) {
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	public function getSaleType() {
		return $this->saleType;
	}

	/**
	 * @return mixed
	 */
	public function getPrettySaleType() {
		if(!$this->saleType){
			return;
		}
		return Flexyapress_Helpers::sale_type_nice_name($this->saleType);
	}

	/**
	 * @param mixed $saleType
	 */
	public function setSaleType( $saleType ) {
		$this->saleType = $saleType;
	}

	/**
	 * @return mixed
	 */
	public function getOfficeId() {
		return $this->officeId;
	}

	/**
	 * @param mixed $officeId
	 */
	public function setOfficeId( $officeId ) {
		$this->officeId = $officeId;
	}

    /**
     * @return mixed
     */
    public function getDescription() {
        if($this->description == null){
            $this->description = '';
        }
        return $this->description;
    }

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @return mixed
	 */
	public function getDescription1() {
		return $this->description1;
	}

	/**
	 * @param mixed $description1
	 */
	public function setDescription1( $description1 ) {
		$this->description1 = $description1;
	}

	/**
	 * @return mixed
	 */
	public function getDescription2() {
		return $this->description2;
	}

	/**
	 * @param mixed $description2
	 */
	public function setDescription2( $description2 ) {
		$this->description2 = $description2;
	}

	/**
	 * @return mixed
	 */
	public function getDescription3() {
		return $this->description3;
	}

	/**
	 * @param mixed $description3
	 */
	public function setDescription3( $description3 ) {
		$this->description3 = $description3;
	}

    /**
     * @return mixed
     */
    public function getDescription4()
    {
        return $this->description4;
    }

    /**
     * @param mixed $description4
     */
    public function setDescription4($description4)
    {
        $this->description4 = $description4;
    }

    /**
     * @return mixed
     */
    public function getDescription5()
    {
        return $this->description5;
    }

    /**
     * @param mixed $description5
     */
    public function setDescription5($description5)
    {
        $this->description5 = $description5;
    }



	/**
	 * @return mixed
	 */
	public function getImageOrder() {
		return $this->imageOrder;
	}

	/**
	 * @param mixed $imageOrder
	 */
	public function setImageOrder( $imageOrder ) {
		$this->imageOrder = $imageOrder;
	}

    /**
     * @return mixed
     */
    public function getUnserializedImageOrder()
    {
        if(is_serialized($this->imageOrder)){
            return unserialize($this->imageOrder);
        }if(is_array($this->imageOrder)){
        return $this->imageOrder;
    }else{
        return array();
    }

    }

    /**
     * @return mixed
     */
    public function getDrawingOrder()
    {
        return $this->drawingOrder;
    }

    /**
     * @param mixed $drawingOrder
     */
    public function setDrawingOrder($drawingOrder)
    {
        $this->drawingOrder = $drawingOrder;
    }

    /**
     * @return mixed
     */
    public function getUnserializedDrawingOrder()
    {
        if(is_serialized($this->drawingOrder)){
            return unserialize($this->drawingOrder);
        }if(is_array($this->drawingOrder)){
            return $this->drawingOrder;
        }else{
            return array();
        }

    }

	/**
	 * @return mixed
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * @param mixed $hash
	 */
	public function setHash( $hash ) {
		$this->hash = $hash;
	}

    /**
     * @return mixed
     */
    public function getImageHash() {
        return $this->imageHash;
    }

    /**
     * @param mixed $hash
     */
    public function setImageHash( $imageHash ) {
        $this->imageHash = $imageHash;
    }

    /**
     * @return mixed
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param mixed $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return mixed
     */
    public function getGallery()
    {
        if(is_serialized($this->gallery)){
            return unserialize($this->gallery);
        }if(is_array($this->gallery)){
        return $this->gallery;
        }else{
            return array();
        }
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
    }

    /**
     * @return mixed
     */
    public function getDrawingsGallery()
    {
        if(is_serialized($this->drawingsGallery)){
            return unserialize($this->drawingsGallery);
        }if(is_array($this->drawingsGallery)){
            return $this->drawingsGallery;
        }else{
            return array();
        }
    }

    /**
     * @param mixed $drawingsGallery
     */
    public function setDrawingsGallery($drawingsGallery)
    {
        $this->drawingsGallery = $drawingsGallery;
    }

    /**
     * @return mixed
     */
    public function getPhotoTexts()
    {
        return $this->photoTexts;
    }

    /**
     * @param mixed $photoTexts
     */
    public function setPhotoTexts($photoTexts)
    {
        $this->photoTexts = $photoTexts;
    }

    public function getConsents($name = ''){

        $consents = get_option('flexyapress_consents');

        if(!$name){
            return $consents;
        }

        if(!$consents){
            return false;
        }

        if(array_key_exists($name, $consents)){
            return $consents[$name];
        }


    }

    /**
     * @return mixed
     */
    public function getShopNo()
    {
        return $this->shopNo;
    }

    /**
     * @param mixed $shopNo
     */
    public function setShopNo($shopNo)
    {
        $this->shopNo = $shopNo;
    }

    /**
     * @return mixed
     */
    public function getPresentationUrl()
    {
        return $this->presentationUrl;
    }

    /**
     * @param mixed $presentationUrl
     */
    public function setPresentationUrl($presentationUrl)
    {
        $this->presentationUrl = $presentationUrl;
    }

    /**
     * @return mixed
     */
    public function getMonthlyRent()
    {
        return $this->monthlyRent;
    }

    /**
     * @param mixed $monthlyRent
     */
    public function setMonthlyRent($monthlyRent)
    {
        $this->monthlyRent = $monthlyRent;
    }

    /**
     * @return string
     */
    public function getPrettyMonthlyRent() {
        if(!$this->monthlyRent){
            return;
        }
        return number_format($this->monthlyRent, 0, ',', '.');
    }

    /**
     * @return mixed
     */
    public function getYearlyRent()
    {
        return $this->yearlyRent;
    }

    /**
     * @param mixed $yearlyRent
     */
    public function setYearlyRent($yearlyRent)
    {
        $this->yearlyRent = $yearlyRent;
    }

    /**
     * @return mixed
     */
    public function getYearlyRentPrArea()
    {
        return $this->yearlyRentPrArea;
    }

    /**
     * @param mixed $yearlyRentPrArea
     */
    public function setYearlyRentPrArea($yearlyRentPrArea)
    {
        $this->yearlyRentPrArea = $yearlyRentPrArea;
    }


    public static function get_cases($only_active = false, $only_sold = false, $type = ''){

        $qargs = array(
            'post_type' => 'sag',
            'posts_per_page' => -1,
            'meta_key' => 'status',
            'meta_query' => array(
                'relation' => 'AND'
            ),
            'orderby' => array('meta_value' => 'ASC', 'date' => 'DESC'),
        );

        if($only_active){
            $qargs['meta_query'][] = [
                'key' => 'status',
                'value' => 'ACTIVE'
            ];
        }

        if($only_sold){
            $qargs['meta_query'][] = [
                'key' => 'status',
                'value' => 'SOLD'
            ];
        }

        if($type){
            if(is_array($type)){
                $temp = [
                    'relation' => 'OR'
                ];
                foreach ($type as $t){
                     $temp[] = [
                        'key' => 'caseType',
                        'value' => $type
                    ];
                }
                $qargs['meta_query'][] = $temp;
            }else{
                $qargs['meta_query'][] = [
                    'key' => 'caseType',
                    'value' => $type
                ];
            }
        }

        $query = new WP_Query($qargs);
        $cases = [];
        if($query->posts){
            foreach ($query->posts as $p){
                $cases[] = new Flexyapress_Case($p->ID);
            }
        }

        return $cases;
    }

    /**
     * @return mixed
     */
    public function getConnectionFee()
    {
        return $this->connectionFee;
    }

    /**
     * @param mixed $connectionFee
     */
    public function setConnectionFee($connectionFee)
    {
        $this->connectionFee = $connectionFee;
    }

	/**
	 * @return mixed
	 */
	public function getBuildings() {
		return $this->buildings;
	}

	/**
	 * @param mixed $buildings
	 */
	public function setBuildings( $buildings ) {
		$this->buildings = $buildings;
	}

    /**
     * @return mixed
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     * @param mixed $customFields
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;
    }

}