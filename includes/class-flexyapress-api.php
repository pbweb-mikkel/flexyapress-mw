<?php

class Flexyapress_API{
	
	private $base_url;
	private $client_realm;
    private $auth_token;
	private $client_id;
	private $client_secret;
    private $save_images_locally;
    private $captcha_site_key;
    private $captcha_secret_key;
    private $consents;


	public function __construct(){

        $this->client_id = $this->get_client_id();
        $this->client_realm = $this->get_client_realm();
        $this->client_secret = $this->get_client_secret();
        $this->base_url = $this->get_api_url();
        $this->shop_no = $this->get_shop_no();
        $this->auth_token = $this->get_auth_token();
        $this->save_images_locally = $this->get_save_images_locally();
        $this->captcha_site_key = get_option('flexyapress')['captcha-site-key'];
        $this->captcha_secret_key = get_option('flexyapress')['captcha-secret-key'];
	}

    public function get_case($id){
        $args = $this->get_header_args();

        $ql_string = 'CaseId: "'.$id.'"';

        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                     case('.$ql_string.') {
                        id
                        __typename
                        ... on SalesCase {
                          id
                          caseNumber
                          ConnectionFees
                          noAdvertisement
                          status
                          statusHistorySoldDate
                          statusHistoryUnderSaleDate
                          statusHistoryFinallyTradeDate
                          shopNo
                          shop {
                            name
                            address
                            phoneNumber
                            email
                          }
                          createdDate
                          lastUpdated
                          firstListingDate
                          statusChangeCollection {
                            to
                            from
                            timeOfChange
                          }
                          presentationUrl
                          brokers {
                            id
                            firstName
                            lastName
                            phoneNumber
                            mobileNumber
                            email
                            imageUrlAsset
                            visibleOnWebsite
                            role
                            title
                          }
                          municipalityNumber
                          propertyType
                          address
                          addressRoadName
                          houseNumber
                          door
                          doorLocation
                          floor
                          zipCode
                          addressCityName
                          locationCityName
                          municipalityName
                          offerAdditionalAccessories
                          roomCount
                          livingRoomCount
                          bathRoomCount
                          bedRoomCount
                          toiletCount
                          yearBuilt
                          totalLivableArea
                          atticArea
                          basementArea
                          totalCarportBuiltUpArea
                          totalGarageBuiltUpArea
                          totalOuthouseBuiltUpArea
                          plotArea
                          totalBuildingArea
                          totalCommercialArea
                          yearRenovated
                          buildings {
                            atticArea
                            isMainBuilding
                            registeredOtherArea
                            builtUpArea
                            commercialArea
                            basementArea
                            description
                            roofType
                            netRegisteredArea
                            registeredArea
                            buildingInclAreas {
                                area
                                buildingInclAreaType
                                description
                                isLivable
                            }
                          }
                          totalFloors
                          totalFullFloors
                          gpsCoordinates
                          gpsCoordinatesLat
                          gpsCoordinatesLong
                          energyMark
                          centralHeating
                          cashPrice
                          payout
                          publicAssessmentDate
                          publicAssessmentPropertyValue
                          publicAssessmentLandValue
                          ownerCostsTotalAnnualAmount
                          ownerCostsTotalMonthlyAmount
                          gross
                          net
                          lastPriceAdjustmentDate
                          laytime
                          publicDocuments {
                            documentType
                            documentName
                            fileName
                            downloadUrl
                          }
                          openHouses {
                            id
                            description
                            startDate
                            endDate
                            status
                            requiresRegistration
                          }
                          announceTextInternet
                          announceHeadlineInternet
                          salesPresentationDescription
                          solutionSpecificCaseFieldValues {
                                id
                                value
                                solutionSpecificCaseField {
                                id
                                name
                                description
                                dataType
                                group
                                order
                                __typename
                                }
                                value
                                __typename
                            }
                          announceTexts {
                            headline
                            introText
                            text
                            textNumber
                            __typename
                          }
                          primaryImage {
                            id
                            imageUrlAsset
                            imageUrlPresentation
                            imageUrlPreview
                            imageUrlThumbnail
                          }
                          media {
                            items {
                                id
                                fileName
                                published
                                priority
                                imageHash
                                fileType
                                mediaType {
                                  id
                                  name
                                }
                                description
                                mimeType
                                resourceUrl(resourceType: Assets)
                                customSizeResourceUrl(height: 1920, width: 1080)
                              }
                          }
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $case = json_decode($resp['body']);

            if(empty($case->errors)){
                $case = $case->data->case;
                if(!empty($case) && is_object($case)){
                    return $case;
                }else{
                    return array();
                }
            }else{
                var_dump($case->errors);
            }
            return [];
        }else{

            return $resp;

        }


    }

    public function get_business_case($id){
        $args = $this->get_header_args();

        $ql_string = 'CaseId: "'.$id.'"';

        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                     case('.$ql_string.') {
                        id
                        __typename
                        ... on BusinessSalesCase {
                          id
                          caseNumber
                          status
                          noAdvertisement
                          shopNo
                          shop {
                            name
                            address
                            phoneNumber
                            email
                          }
                          createdDate
                          lastUpdated
                          firstListingDate
                          statusChangeCollection {
                            to
                            from
                            timeOfChange
                          }
                          presentationUrl
                          brokers {
                            id
                            firstName
                            lastName
                            phoneNumber
                            mobileNumber
                            email
                            imageUrlAsset
                            visibleOnWebsite
                            role
                            title
                          }
                          municipalityNumber
                          propertyType
                          address
                          addressRoadName
                          houseNumber
                          door
                          doorLocation
                          floor
                          zipCode
                          addressCityName
                          locationCityName
                          municipalityName
                          roomCount
                          yearBuilt
                          totalLivableArea
                          atticArea
                          basementArea
                          totalCarportBuiltUpArea
                          totalGarageBuiltUpArea
                          totalOuthouseBuiltUpArea
                          plotArea
                          solutionSpecificCaseFieldValues {
                                id
                                value
                                solutionSpecificCaseField {
                                id
                                name
                                description
                                dataType
                                group
                                order
                                __typename
                                }
                                value
                                __typename
                            }
                         
                          totalBuildingArea
                          totalCommercialArea
                          yearRenovated
                          buildings {
                            atticArea
                            isMainBuilding
                            registeredOtherArea
                            builtUpArea
                            commercialArea
                            basementArea
                            description
                            roofType
                            netRegisteredArea
                            registeredArea
                            bathroomCount
                            businessBuildingType
                            elevator
                            livableArea
                            buildingInclAreas {
                                area
                                buildingInclAreaType
                                description
                                isLivable
                            }
                          }
                          totalFloors
                          totalFullFloors
                          gpsCoordinates
                          gpsCoordinatesLat
                          gpsCoordinatesLong
                          energyMark
                          centralHeating
                          cashPrice
                          publicAssessmentDate
                          publicAssessmentPropertyValue
                          publicAssessmentLandValue
                          lastPriceAdjustmentDate
                          laytime
                          publicDocuments {
                            documentType
                            documentName
                            fileName
                            downloadUrl
                          }
                          openHouses {
                            id
                            description
                            startDate
                            endDate
                            status
                            requiresRegistration
                          }
                          announceTextInternet
                          announceHeadlineInternet
                          salesPresentationDescription
                          announceTexts {
                            headline
                            introText
                            text
                            textNumber
                            __typename
                          }
                          primaryImage {
                            id
                            imageUrlAsset
                            imageUrlPresentation
                            imageUrlPreview
                            imageUrlThumbnail
                          }
                          media {
                            items {
                              id
                              fileName
                              published
                              priority
                              imageHash
                              fileType
                              mediaType {
                                id
                                name
                              }
                              description
                              mimeType
                              resourceUrl(resourceType: Assets)
                              customSizeResourceUrl(height: 1920, width: 1080)
                            }
                          }
                          FloorAreaTotal
                          IsCondo
                          investmentProperty
                          floorArea
                          propertyCategory
                          tenanciesYearlyRentTotal
                          tenanciesYearlyRentPerM2Total
                          tenanciesPayoutTotal
                          tenanciesMonthlyRentTotal
                          tenanciesAreaTotal
                          tenanciesDepositTotal
                          tenanciesRevenueIncreasePerYears {
                            Year
                            AnnualAmount
                          }
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $case = json_decode($resp['body']);

            if(empty($case->errors)){
                $case = $case->data->case;
                if(!empty($case) && is_object($case)){
                    return $case;
                }else{
                    return array();
                }
            }else{
                var_dump($case->errors);
            }
            return [];
        }else{

            return $resp;

        }


    }

    public function get_business_rental_case($id){
        $args = $this->get_header_args();

        $ql_string = 'CaseId: "'.$id.'"';

        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                     case('.$ql_string.') {
                        id
                        __typename
                        ... on BusinessRentalCase {
                          id
                          caseNumber
                          status
                          noAdvertisement
                          shopNo
                          shop {
                            name
                            address
                            phoneNumber
                            email
                          }
                          createdDate
                          lastUpdated
                          firstListingDate
                          statusChangeCollection {
                            to
                            from
                            timeOfChange
                          }
                          presentationUrl
                          brokers {
                            id
                            firstName
                            lastName
                            phoneNumber
                            mobileNumber
                            email
                            imageUrlAsset
                            visibleOnWebsite
                            role
                            title
                          }
                          municipalityNumber
                          propertyType
                          address
                          addressRoadName
                          houseNumber
                          door
                          doorLocation
                          floor
                          zipCode
                          addressCityName
                          locationCityName
                          municipalityName
                          roomCount
                          yearBuilt
                          solutionSpecificCaseFieldValues {
                                id
                                value
                                solutionSpecificCaseField {
                                id
                                name
                                description
                                dataType
                                group
                                order
                                __typename
                                }
                                value
                                __typename
                            }
                          totalLivableArea
                          atticArea
                          basementArea
                          totalCarportBuiltUpArea
                          totalGarageBuiltUpArea
                          totalOuthouseBuiltUpArea
                          plotArea
                          totalBuildingArea
                          totalCommercialArea
                          yearRenovated
                          buildings {
                            atticArea
                            isMainBuilding
                            registeredOtherArea
                            builtUpArea
                            commercialArea
                            basementArea
                            description
                            roofType
                            netRegisteredArea
                            registeredArea
                            bathroomCount
                            businessBuildingType
                            elevator
                            livableArea
                            buildingInclAreas {
                                area
                                buildingInclAreaType
                                description
                                isLivable
                            }
                          }
                          totalFloors
                          totalFullFloors
                          gpsCoordinates
                          gpsCoordinatesLat
                          gpsCoordinatesLong
                          energyMark
                          centralHeating
                          cashPrice
                          publicAssessmentDate
                          publicAssessmentPropertyValue
                          publicAssessmentLandValue
                          laytime
                          publicDocuments {
                            documentType
                            documentName
                            fileName
                            downloadUrl
                          }
                          openHouses {
                            id
                            description
                            startDate
                            endDate
                            status
                            requiresRegistration
                          }
                          announceTextInternet
                          announceHeadlineInternet
                          announceTexts {
                            headline
                            introText
                            text
                            textNumber
                            __typename
                          }
                          primaryImage {
                            id
                            imageUrlAsset
                            imageUrlPresentation
                            imageUrlPreview
                            imageUrlThumbnail
                          }
                          media {
                            items {
                              id
                              fileName
                              published
                              priority
                              imageHash
                              fileType
                              mediaType {
                                id
                                name
                              }
                              description
                              mimeType
                              resourceUrl(resourceType: Assets)
                              customSizeResourceUrl(height: 1920, width: 1080)
                            }
                          }
                          FloorAreaTotal
                          IsCondo
                          floorArea
                          propertyCategory
                          deposit
                          depositMonths
                          monthlyRent
                          parkingLots
                          prepaidRent
                          prepaidRentMonths
                          yearlyRent
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $case = json_decode($resp['body']);

            if(empty($case->errors)){
                $case = $case->data->case;
                if(!empty($case) && is_object($case)){
                    return $case;
                }else{
                    return array();
                }
            }else{
                var_dump($case->errors);
            }
            return [];
        }else{

            return $resp;

        }


    }

    public function get_rental_case($id){
        $args = $this->get_header_args();

        $ql_string = 'CaseId: "'.$id.'"';

        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                     case('.$ql_string.') {
                        id
                        __typename
                        ... on RentalCase {
                          id
                          caseNumber
                          status
                          shopNo
                          createdDate
                          noAdvertisement
                          lastUpdated
                          firstListingDate
                          statusChangeCollection {
                            to
                            from
                            timeOfChange
                          }
                          presentationUrl
                          brokers {
                            id
                            firstName
                            lastName
                            phoneNumber
                            mobileNumber
                            email
                            imageUrlAsset
                            visibleOnWebsite
                            role
                            title
                          }
                          municipalityNumber
                          propertyType
                          address
                          addressRoadName
                          houseNumber
                          door
                          doorLocation
                          floor
                          zipCode
                          addressCityName
                          locationCityName
                          municipalityName
                          offerAdditionalAccessories
                          roomCount
                          livingRoomCount
                          bathRoomCount
                          bedRoomCount
                          toiletCount
                          yearBuilt
                          totalLivableArea
                          atticArea
                          solutionSpecificCaseFieldValues {
                                id
                                value
                                solutionSpecificCaseField {
                                id
                                name
                                description
                                dataType
                                group
                                order
                                __typename
                                }
                                value
                                __typename
                            }
                          basementArea
                          totalCarportBuiltUpArea
                          totalGarageBuiltUpArea
                          totalOuthouseBuiltUpArea
                          plotArea
                          totalBuildingArea
                          totalCommercialArea
                          yearRenovated
                          buildings {
                            atticArea
                            isMainBuilding
                            registeredOtherArea
                            builtUpArea
                            commercialArea
                            basementArea
                            description
                            roofType
                            netRegisteredArea
                            registeredArea
                            buildingInclAreas {
                                area
                                buildingInclAreaType
                                description
                                isLivable
                            }
                          }
                          totalFloors
                          totalFullFloors
                          gpsCoordinates
                          gpsCoordinatesLat
                          gpsCoordinatesLong
                          energyMark
                          centralHeating
                          cashPrice
                          publicAssessmentDate
                          publicAssessmentPropertyValue
                          publicAssessmentLandValue
                          laytime
                          publicDocuments {
                            documentType
                            documentName
                            fileName
                            downloadUrl
                          }
                          openHouses {
                            id
                            description
                            startDate
                            endDate
                            status
                            requiresRegistration
                          }
                          announceTextInternet
                          announceHeadlineInternet
                          salesPresentationDescription
                          announceTexts {
                            headline
                            introText
                            text
                            textNumber
                            __typename
                          }
                          primaryImage {
                            id
                            imageUrlAsset
                            imageUrlPresentation
                            imageUrlPreview
                            imageUrlThumbnail
                          }
                          media {
                            items {
                              id
                              fileName
                              published
                              priority
                              imageHash
                              fileType
                              mediaType {
                                id
                                name
                              }
                              description
                              mimeType
                              resourceUrl(resourceType: Assets)
                              customSizeResourceUrl(height: 1920, width: 1080)
                            }
                          }
                          availableFrom
                          availableTo
                          deposit
                          petsAllowed
                          prepaidRent
                          rentPerMonth
                          rentOnAcountPerMonth
                          rentalPeriodMaximum
                          residenceRequirement
                          shareable
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $case = json_decode($resp['body']);

            if(empty($case->errors)){
                $case = $case->data->case;
                if(!empty($case) && is_object($case)){
                    return $case;
                }else{
                    return array();
                }
            }else{
                var_dump($case->errors);
            }
            return [];
        }else{

            return $resp;

        }


    }

	/* Get cases from flexya. Returns array if data is given, false otherwise */
	public function get_cases($amount = 9999, $updated_after = "1999-01-01T00:00:00Z", $status = '[ForSale,Sold,UnderSale,FinallyTrade]'){
        $args = $this->get_header_args();
        $status_string = '';

        $amount = $amount ?: 9999;
        $updated_after = $updated_after ?: "1999-01-01T00:00:00Z";
        //$status = $status ?: "[ForSale,Sold]";

        if($status){
            $status_string = ', SalesCaseStatuses: '.$status;
        }

        $ql_string = 'UpdatedAfter: "'.$updated_after.'", Amount: '.$amount.$status_string;
        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
                'query' => '
            {
                      cases('.$ql_string.') {
                        cases {
                          id
                          __typename
                          ... on SalesCase {
                            id
                            shopNo
                            caseNumber
                            status
                            noAdvertisement
                          }
                        }
                      }
                    }
            ']);

		$resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

		if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }
			
			$cases = json_decode($resp['body']);

            if(empty($cases->errors)){
                $cases = $cases->data->cases->cases;
                if(is_array($cases) && count($cases) > 0){
                    $caseArr = [];
                    foreach ($cases as $c){
                        if($c->noAdvertisement === true){
                            continue;
                        }
                        $caseArr[] = $c;
                    }
                    return $caseArr;
                }else{
                    return array();
                }
            }else{
                var_dump($cases->errors);
                set_transient('flexyapress_auth_token', null);
            }
			return [];
		}else{
			
			return $resp;
			
		}
		
	}

    public function get_rental_cases($amount = 9999, $updated_after = "1999-01-01T00:00:00Z", $status = '[ForRent,Rented]'){
        $args = $this->get_header_args();
        $status_string = '';

        $amount = $amount ?: 9999;
        $updated_after = $updated_after ?: "1999-01-01T00:00:00Z";
        $status = $status ?: "[ForRent,Rented]";

        if($status){
            $status_string = ', RentalCaseStatuses: '.$status;
        }

        $ql_string = 'UpdatedAfter: "'.$updated_after.'", Amount: '.$amount.$status_string;
        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                      cases('.$ql_string.') {
                        cases {
                          id
                          __typename
                          ... on RentalCase {
                            id
                            caseNumber
                            shopNo
                            status
                            noAdvertisement
                          }
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $cases = json_decode($resp['body']);

            if(empty($cases->errors)){
                $cases = $cases->data->cases->cases;
                if(is_array($cases) && count($cases) > 0){
                    $caseArr = [];
                    foreach ($cases as $c){
                        if($c->noAdvertisement === true){
                            continue;
                        }
                        $caseArr[] = $c;
                    }
                    return $caseArr;
                }else{
                    return array();
                }
            }else{
                var_dump($cases->errors);
                set_transient('flexyapress_auth_token', null);
            }
            return [];
        }else{

            return $resp;

        }

    }

    public function get_business_cases($amount = 9999, $updated_after = "1999-01-01T00:00:00Z", $status = '[ForSale,Sold,UnderSale,FinallyTrade]'){
        $args = $this->get_header_args();
        $status_string = '';

        $amount = $amount ?: 9999;
        $updated_after = $updated_after ?: "1999-01-01T00:00:00Z";
        //$status = $status ?: "[ForSale,Sold]";

        if($status){
            $status_string = ', BusinessSalesCaseStatuses: '.$status;
        }

        $ql_string = 'UpdatedAfter: "'.$updated_after.'", Amount: '.$amount.$status_string;
        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                      cases('.$ql_string.') {
                        cases {
                          id
                          __typename
                          ... on BusinessSalesCase{
                            id
                            caseNumber
                            shopNo
                            status
                            noAdvertisement
                          }
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $cases = json_decode($resp['body']);

            if(empty($cases->errors)){
                $cases = $cases->data->cases->cases;
                if(is_array($cases) && count($cases) > 0){
                    $caseArr = [];
                    foreach ($cases as $c){
                        if($c->noAdvertisement === true){
                            continue;
                        }
                        $caseArr[] = $c;
                    }
                    return $caseArr;
                }else{
                    return array();
                }
            }else{
                var_dump($cases->errors);
                set_transient('flexyapress_auth_token', null, 1);
            }
            return [];
        }else{

            return $resp;

        }

    }

    public function get_business_rental_cases($amount = 9999, $updated_after = "1999-01-01T00:00:00Z", $status = '[ForRent,Rented]'){
        $args = $this->get_header_args();
        $status_string = '';

        $amount = $amount ?: 9999;
        $updated_after = $updated_after ?: "1999-01-01T00:00:00Z";
        $status = $status ?: "[ForRent,Rented]";

        if($status){
            $status_string = ', BusinessRentalCaseStatuses: '.$status;
        }

        $ql_string = 'UpdatedAfter: "'.$updated_after.'", Amount: '.$amount.$status_string;
        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                      cases('.$ql_string.') {
                        cases {
                          id
                          __typename
                          ... on BusinessRentalCase {
                            id
                            caseNumber
                            shopNo
                            status
                            noAdvertisement
                          }
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $cases = json_decode($resp['body']);

            if(empty($cases->errors)){
                $cases = $cases->data->cases->cases;
                if(is_array($cases) && count($cases) > 0){
                    $caseArr = [];
                    foreach ($cases as $c){
                        if($c->noAdvertisement === true){
                            continue;
                        }
                        $caseArr[] = $c;
                    }
                    return $caseArr;
                }else{
                    return array();
                }
            }else{
                var_dump($cases->errors);
                set_transient('flexyapress_auth_token', null);
            }
            return [];
        }else{

            return $resp;

        }

    }

    public function get_employees($amount = 9999, $updated_after = "1999-01-01T00:00:00Z"){
        $args = $this->get_header_args();

        $amount = $amount ?: 9999;
        $updated_after = $updated_after ?: "1999-01-01T00:00:00Z";

        $ql_string = 'UpdatedAfter: "'.$updated_after.'", Amount: '.$amount;
        $args['timeout'] = 300;
        $args['body'] = wp_json_encode([
            'query' => '
            {
                      employees('.$ql_string.') {
                        employees {
                          active
                          buyerAdviceAllowed
                          customLink
                          description
                          email
                          employeeGuid
                          employeeType
                          firstName
                          facebookLink
                          id
                          imageUrlAsset
                          lastName
                          lastUpdated
                          linkedInLink
                          mobileNumber
                          phoneNumber
                          role
                          shopNo
                          shopNos
                          title
                          videoLink
                          visibleOnWebsite
                        }
                      }
                    }
            ']);

        $resp = wp_remote_post( $this->base_url.'dataexport/graphql/', $args);

        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $employees = json_decode($resp['body']);

            if(empty($employees->errors)){
                $employees = $employees->data->employees->employees;
                if(is_array($employees) && count($employees) > 0){
                    return $employees;
                }else{
                    return array();
                }
            }else{
                var_dump($employees->errors);
            }
            return [];
        }else{

            return $resp;

        }
    }

    public function get_lead_types(){
        $args = $this->get_header_args();

        $args['timeout'] = 300;
        /*$args['body'] = wp_json_encode([
            'query' => '
            {
                      leadTypes {
                        currentConsent {
                          deletionText
                          heading
                          id
                          isCurrent
                          timeUntilDeletionInMonths
                          purposeText
                          withdrawWhenDeleting
                          timeUntilDeletionText
                          leadType {
                            id
                            name
                          }
                        }
                      }
                    }
            ']);
        */
        $args['body'] = wp_json_encode([
            'query' => '
            {
                      leadTypes {
                        name
                        id
                        displayName
                      }
                    }
            ']);
        $resp = wp_remote_post( $this->base_url.'leads/graphql/', $args);
        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $lead_types = json_decode($resp['body']);

            if(empty($lead_types->errors)){
                $lead_types = $lead_types->data->leadTypes;
                if(is_array($lead_types) && count($lead_types) > 0){
                    return $lead_types;
                }else{
                    return array();
                }
            }else{
                var_dump($lead_types->errors);
                return [];
            }
            return [];
        }else{
            return $resp;
        }
    }

    public function get_lead_consents_by_typeid($id){
        $args = $this->get_header_args();

        $args['timeout'] = 300;

        $args['body'] = wp_json_encode([
            'query' => '
            {
                  leadConsents(LeadTypeId: "'.$id.'") {
                    id
                    isCurrent
                    heading
                    purposeText
                    leadType {
                      name
                      id
                      displayName
                    }
                  }
              }
            ']);
        $resp = wp_remote_post( $this->base_url.'leads/graphql/', $args);
        if(!is_wp_error( $resp )){

            if(empty($resp['response']['code']) || $resp['response']['code'] != 200){
                var_dump($resp);
                die();
            }

            $lead_consents = json_decode($resp['body']);

            if(empty($lead_consents->errors)){
                $lead_consents = $lead_consents->data->leadConsents;
                if(is_array($lead_consents) && count($lead_consents) > 0){
                    return $lead_consents;
                }else{
                    return array();
                }
            }else{
                var_dump($lead_consents->errors);
                return [];
            }
            return [];
        }else{
            return $resp;
        }
    }

	/* Get the base-url from the settings */
	private function get_auth_url(){
        $auth_url = get_option('flexyapress')['auth-url'];
        if(!$auth_url){
            $auth_url = 'https://iam.mindworking.eu';
        }

        $auth_url = trailingslashit($auth_url);

		return $auth_url.'auth/realms/'.$this->client_realm.'/protocol/openid-connect/token';

	}

	/* Get the base-url from the settings */
	private function get_api_url(){

		return 'https://'.$this->client_realm.'.mindworking.eu/api/integrations/';

	}

    private function get_shop_no(){
        return get_option('flexyapress')['shop-no'];
    }

    private function get_client_realm(){

        return get_option('flexyapress')['client-realm'];

    }

    private function get_client_id(){

        return get_option('flexyapress')['client-id'];

    }

    private function get_client_secret(){

        return get_option('flexyapress')['client-secret'];

    }

    private function get_auth_token($force = false){

        if(!$force && get_transient('flexyapress_auth_token')){
            return get_transient('flexyapress_auth_token');
        }

        $response = wp_remote_post($this->get_auth_url(),['body' => ['grant_type' => 'client_credentials', 'client_id' => $this->client_id, 'client_secret' => $this->client_secret]]);
        $body     = json_decode( wp_remote_retrieve_body( $response ) );

        if(!empty($body->access_token)){
            set_transient('flexyapress_auth_token', $body->access_token, 200);
            return $body->access_token;
        }


    }

    public function get_external_image_url($id, $imageHash, $size = 1800){

        //return "https://".$this->get_client_realm().".mindworking.eu/resources/shops/".$shopNo."/cases/$caseNumber/casemedia/images/$imageHash/customsize.jpg?&width=$size";
        return "https://".$this->get_client_realm().".mindworking.eu/api/mediaData/mediaDataMediaPurposeId/$id/imageHash/$imageHash/imageSize/assets/inline/True?width=$size";

        //https://demomw.mindworking.eu/api/mediaData/mediaDataMediaPurposeId/SU1lZGlhRGF0YTp7Ik1lZGlhRGF0YUlkIjo4NDIsIk1lZGlhUHVycG9zZUlkIjoyMX0/imageHash/2dccdbe2cce6d092335a9aa9050253c1/imageSize/Preview/inline/True
    }

    /* Get the office-id from the settings */
    public function get_save_images_locally(){

        return get_option('flexyapress')['save-images-locally'];

    }
	
	private function get_header_args(){

		$args = array(
			'headers' => array(
                'Content-Type' => 'application/json',
				'Authorization' => 'Bearer '.$this->auth_token
			)
		);

		return $args;
	}




	public function order($input){

		$allowed_fields = array(
			'orgKey',
			'caseKey',
			'name',
            'date',
            'time',
			'phone',
			'email',
			'makeABid',
			'sendEmail',
			'address',
			'contactAccepted',
			'buyerActionType',
			'callback',
			'officeId',
			'message',
            'firstName',
            'lastName',
            'phoneNumber',
            'caseNo',
            'shopNo',
            'comment',
            'consentIdGlobal',
            'openHouseStartTime',
            'openHouseIdDateStart',
            'openHouseId',
            'dawa-address-id',
            'livesOnAddress'
		);

		$qs = array();

		if(!is_array($input)){
			return array('type' => "error", 'message' => 'Malformed input');
		}

		if(!empty($input['message'])){
			$input['message'] = preg_replace('/[^a-zA-Z0-9_ -?.åæøÅØÆ]/s','',$input['message']);
		}

        if($input['buyerActionType'] == 'PRESENTATION_ORDER' && !$input['date']){
            $input['date'] = date('d-m-Y', strtotime('+1 day'));
        }

		foreach ($input as $key => $value){
			if(in_array($key, $allowed_fields)){
				$qs[filter_var($key, FILTER_SANITIZE_STRING)] = ($value == 'on') ? true : filter_var($value, FILTER_SANITIZE_STRING);
			}
		}
        $qs['lastName'] = '';
        if($qs['name']){
            $parts = explode(' ', trim($qs['name']));
            if($parts){
                $qs['lastName'] = array_pop($parts);
            }
            $qs['name'] = implode(' ', $parts);
        }

        if($qs['date']){
            $qs['date'] = date('Y-m-d', strtotime($qs['date']));

            if($qs['time']){
                $qs['date'] .= ' '.$qs['time'];
            }
            date_default_timezone_set('Europe/Copenhagen');
            $qs['date'] = gmdate('c', strtotime($qs['date']));

        }

		if($qs['buyerActionType'] == 'PRESENTATION_ORDER'){
			if(isset($input['time']) && isset($input['date'])){
				$message = "Ønsket dato: ".filter_var($input['date'], FILTER_SANITIZE_STRING);
				$message .= "<br>Ønsket tidspunkt: ".filter_var($input['time'], FILTER_SANITIZE_STRING);
				if(isset($qs['message'])){
					$qs['message'] .= '<br>'.$message;
				}else{
					$qs['message'] = $message;
				}

			}
		}

		if($qs['buyerActionType'] == 'VALUATION_ORDER'){
			if(isset($input['date'])){
				$message = "Ønsket dato: ".filter_var($input['date'], FILTER_SANITIZE_STRING);

                if(isset($input['time'])){
                    $message .= "<br>Ønsket tidspunkt: ".filter_var($input['time'], FILTER_SANITIZE_STRING);
                }

				if(isset($qs['message'])){
					$qs['message'] .= '<br>'.$message;
				}else{
					$qs['message'] = $message;
				}
			}
            if(!empty($qs['address'])){
                $qs['message'] .= "Adresse: ".$qs['address'];
            }
		}

        if($qs['message']){
            $qs['message'] = str_replace(["\r","\n"], ' ', $qs['message']);
        }

		if($qs['buyerActionType'] == 'DOCUMENTS_ORDER'){
			if(!isset($qs['sendEmail'])){
				$qs['sendEmail'] = false;
			}
			if(!isset($qs['contactAccepted'])){
				$qs['contactAccepted'] = false;
			}
		}

		//$this->sendOrderMail($qs);

        switch ($qs['buyerActionType']){

            case 'PRESENTATION_ORDER':
                $response = $this->order_presentation($qs['caseNo'], $qs['shopNo'], $qs['consentIdGlobal'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message'], $qs['date']);
                break;
            case 'DOCUMENTS_ORDER':
                $consent_id = $qs['contactAccepted'] ? $input['consentIdGlobalWithContact'] : $input['consentIdGlobalWithoutContact'];
                $response = $this->order_documents($qs['contactAccepted'], $qs['caseNo'], $qs['shopNo'], $consent_id, $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message']);
                break;
            case 'MAKEABID_ORDER':
                $response = $this->order_purchase_offer($qs['makeABid'],$qs['caseNo'], $qs['shopNo'], $qs['consentIdGlobal'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message']);
                break;
            case 'CASE_CONTACT_ORDER':
                $response = $this->order_case_contact($qs['caseNo'], $qs['shopNo'], $qs['consentIdGlobal'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message']);
                break;
            case 'CONTACT_ORDER':
                $response = $this->order_office_contact($qs['shopNo'], $qs['consentIdGlobal'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message']);
                break;
            case 'VALUATION_ORDER':
                $response = $this->order_sales_valuation($qs['consentIdGlobal'], $qs['shopNo'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message'], $qs['livesOnAddress'], $qs['dawa-address-id']);
                break;
            case 'OPENHOUSE_SIGNUP':
                if(!empty($qs['openHouseIdDateStart'])){
                    $parts = explode('||',$qs['openHouseIdDateStart']);
                    $openhouseId = $parts[0];
                    $openhouseStartTime = $parts[1];
                    $response = $this->order_open_house($qs['consentIdGlobal'], $openhouseId, $openhouseStartTime, $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'], $qs['message']);
                }else {
                    $response = $this->order_open_house($qs['consentIdGlobal'], $qs['openHouseId'],
                        $qs['openHouseStartTime'], $qs['name'], $qs['lastName'], $qs['phone'], $qs['email'],
                        $qs['message']);
                }
                break;
        }

        /*
		$url .= '?'.http_build_query($qs);
		$args = $this->get_header_args();
		$args['method'] = 'POST';

        if($input['email'] == 'mikkel@pbweb.dk'){
            $response = [
                'response' => [
                    'code' => 200
                ],
                'msg' => 'Developer detected. Skipping Flexya request'
            ];
        }else{
            $response = wp_remote_post($url, $args);
        }
        */

        $response_body = !empty($response['body']) ? json_decode($response['body']) : null;

        $id = null;
        $errors = null;
        switch ($qs['buyerActionType']){

            case 'PRESENTATION_ORDER':
                $id = $response_body->data->createPresentation->id;
                $errors = $response_body->data->createPresentation->errors;
                break;
            case 'DOCUMENTS_ORDER':
                if($qs['contactAccepted']){
                    $id = $response_body->data->createSalesMaterialWithContact->id;
                    $errors = $response_body->data->createSalesMaterialWithContact->errors;
                }else{
                    $id = $response_body->data->createSalesMaterial->id;
                    $errors = $response_body->data->createSalesMaterial->errors;
                }
                break;
            case 'MAKEABID_ORDER':
                $id = $response_body->data->createPurchaseOffer->id;
                $errors = $response_body->data->createPurchaseOffer->errors;
                break;
            case 'CASE_CONTACT_ORDER':
                $id = $response_body->data->createContactEmployee->id;
                $errors = $response_body->data->createContactEmployee->errors;
                break;
            case 'CONTACT_ORDER':
                $id = $response_body->data->createContact->id;
                $errors = $response_body->data->createContact->errors;
                break;
            case 'VALUATION_ORDER':
                $id = $response_body->data->createSalesValuation->id;
                $errors = $response_body->data->createSalesValuation->errors;
                break;
            case 'OPENHOUSE_SIGNUP':
                $id = $response_body->data->createOpenHouse->id;
                $errors = $response_body->data->createOpenHouse->errors;
                break;
        }

		if($response['response']['code'] == 200 && empty($response_body->errors) && !empty($id) && empty($errors)){
            $return = array(
                'type' => 'success',
            );
            new Flexyapress_Log('order_success_'.$qs['buyerActionType'], $qs, serialize($response_body->data));
		}else{
			new Flexyapress_Log('order_fail_'.$qs['buyerActionType'], serialize(array('fields' => $input)), serialize($response));
			$return = array(
				'type' => 'error',
				'message' => __('Der er sket en fejl ved afsendelsen af din forespørgsel. Prøv venligst igen', 'flexyapress'),
				'response' => $response
			);
		}

		return $return;

	}

    private function order_presentation($caseNo, $shopNo, $consent_id, $firstName, $lastName = '', $phone, $email, $message = '', $date = ''){
        $args = $this->get_header_args();

        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createPresentation(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              caseNo: "'.$caseNo.'"
                              shopNo: "'.$shopNo.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              comment: "'.$message.'"
                              '.($date ? 'presentationDate: "'.$date.'"' : '').'
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_purchase_offer($amount, $caseNo, $shopNo, $consent_id, $firstName, $lastName = '', $phone, $email, $message){
        $args = $this->get_header_args();

        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createPurchaseOffer(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              caseNo: "'.$caseNo.'"
                              shopNo: "'.$shopNo.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              comment: "'.$message.'",
                              offerAmount: '.$amount.'
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_contact(){

    }

    private function order_case_contact($caseNo, $shopNo, $consent_id, $firstName, $lastName = '', $phone, $email, $message = ''){
        $args = $this->get_header_args();

        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createContactEmployee(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              caseNo: "'.$caseNo.'"
                              shopNo: "'.$shopNo.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              message: "'.$message.'"
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_office_contact($shopNo, $consent_id, $firstName, $lastName = '', $phone, $email, $message = ''){
        $args = $this->get_header_args();

        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createContact(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              responsibleShopNo: "'.$shopNo.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              message: "'.$message.'"
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_open_house($consent_id, $openhouse_id, $openhouse_start_datetime = '', $firstName, $lastName = '', $phone, $email, $message = ''){
        $args = $this->get_header_args();
        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createOpenHouse(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              comment: "'.$message.'"
                              openHouseId: "'.$openhouse_id.'"
                              openHouseStartTime: "'.$openhouse_start_datetime.'"
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_sales_valuation($consent_id, $shopNo = '', $firstName, $lastName = '', $phone, $email, $message = '', $lives_on_address = false, $dawa_guid = ''){
        $args = $this->get_header_args();

        if(!$shopNo){
            $shopNo = $this->get_shop_no();
        }

        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                          createSalesValuation(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              consentIdGlobal: "'.$consent_id.'"
                              livesOnAddress: '.($lives_on_address ? 'true' : 'false').'
                              '.($email ? 'email: "'.$email.'"' : '').'
                              '.($message ? 'comment: "'.$message.'"' : '').'
                              '.($dawa_guid ? 'awsAddressGuid: "'.$dawa_guid.'"' : '').'
                              '.($shopNo ? 'responsibleShopNo: "'.$shopNo.'"' : '').'
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);

        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }

    private function order_documents($with_contact = false, $caseNo, $shopNo, $consent_id, $firstName, $lastName = '', $phone, $email, $message){
        $args = $this->get_header_args();
        $args['timeout'] = 300;
        $args['body'] = json_encode(
            ['query' => 'mutation{
                        '.($with_contact ? 'createSalesMaterialWithContact' : 'createSalesMaterial').'(
                            input: {
                              firstName: "'.$firstName.'"
                              lastName: "'.$lastName.'"
                              phoneNumber: "'.$phone.'"
                              caseNo: "'.$caseNo.'"
                              shopNo: "'.$shopNo.'"
                              consentIdGlobal: "'.$consent_id.'"
                              email: "'.$email.'"
                              comment: "'.$message.'",
                            }
                          ){
                        id
                        errors {
                          id
                          message
                          path
                          type
                        }
                      }
                      }
            ']);
        return wp_remote_post( $this->base_url.'leads/graphql/', $args);
    }


    function load_wpc7_fields( $form_tag )
    {
        global $post;

        $id = $post->ID;
        if(!empty($_POST['wpb_pcf_post_id'])){
            $id = $_POST['wpb_pcf_post_id'];
        }

        if ( $form_tag['name'] == 'caseKey' ) {
            $form_tag['values'][] = get_post_meta($id, 'caseKey', true);
        }else if ( $form_tag['name'] == 'consentIdGlobal' || $form_tag['name'] == 'consentIdGlobalWithContact' || $form_tag['name'] == 'consentIdGlobalWithoutContact' ) {
            $consent = false;
            switch ($form_tag['raw_values'][0]){
                case 'Presentation':
                    $consent = Flexyapress_API::getConsents('Presentation');
                    break;
                case 'OpenHouse':
                    $consent = Flexyapress_API::getConsents('OpenHouse');
                    break;
                case 'Contact':
                    $consent = Flexyapress_API::getConsents('Contact');
                    break;
                case 'ContactEmployee':
                    $consent = Flexyapress_API::getConsents('ContactEmployee');
                    break;
                case 'SalesValuation':
                    $consent = Flexyapress_API::getConsents('SalesValuation');
                    break;
                case 'SalesMaterialWithContact':
                    $consent = Flexyapress_API::getConsents('SalesMaterialWithContact');
                    break;
                case 'SalesMaterial':
                    $consent = Flexyapress_API::getConsents('SalesMaterial');
                    break;
            }

            if(!empty($consent)){
                $form_tag['values'] = [$consent['id']];
            }

        }else if ( $form_tag['name'] == 'caseNo' ) {
            $form_tag['values'][] = get_post_meta($id, 'caseNumber', true);
        }else if ( $form_tag['name'] == 'openHouseId' ) {
            $oh = get_field('openhouseDatesTotal', $id);
            if($oh){
                $form_tag['values'][] = $oh[0]['id'];
            }
        } else if ( $form_tag['name'] == 'openHouseStartTime' ) {
            $oh = get_field('openhouseDatesTotal', $id);
            if($oh){
                $form_tag['values'][] = $oh[0]['dateStart'];
            }
        }else if($form_tag['type'] == 'acceptance'){

            $consent = false;
            switch ($form_tag['name']){
                case 'Presentation':
                    $consent = Flexyapress_API::getConsents('Presentation');
                    break;
                case 'Contact':
                    $consent = Flexyapress_API::getConsents('Contact');
                    break;
                case 'OpenHouse':
                    $consent = Flexyapress_API::getConsents('OpenHouse');
                    break;
                case 'ContactEmployee':
                    $consent = Flexyapress_API::getConsents('ContactEmployee');
                    break;
                case 'SalesValuation':
                    $consent = Flexyapress_API::getConsents('SalesValuation');
                    break;
                case 'SalesMaterialWithContact':
                    $consent = Flexyapress_API::getConsents('SalesMaterialWithContact');
                    break;
                case 'SalesMaterial':
                    $consent = Flexyapress_API::getConsents('SalesMaterial');
                    break;
            }

            if(!empty($consent)){
                $form_tag['content'] = strip_tags($consent['text']);
            }
        }

        return $form_tag;
    }


    function pb_wpcf7_before_send_mail($cf7){
        $wpcf = WPCF7_ContactForm::get_current();
        $submission = WPCF7_Submission::get_instance();
        $data = $submission->get_posted_data();

        if(array_key_exists('buyerActionType', $data)) {

			if($data['contactAccepted'][0]){
				$data['contactAccepted'] = true;
			}else{
				$data['contactAccepted'] = false;
			}

			if($data['sendEmail'][0]){
				$data['sendEmail'] = true;
			}else{
				$data['sendEmail'] = false;
			}

            if(!$data['name'] && $data['yourname']){
                $data['name'] = $data['yourname'];
            }

            if(!$data['email'] && $data['youremail']){
                $data['email'] = $data['youremail'];
            }

            $this->order($data);
        }
    }


    public function sendOrderMail($fields){
		//TODO Finish this
		$types = array(
			'DOCUMENTS_ORDER' => 'Bestilling af salgsdokumenter',

		);

		$mail = "Du har modtaget en henvendelse fra din hjemmeside:<br>";
		$mail .= "Type: ". $qs['buyerActionType'].'<br>';
		$mail .= "Navn: ". $qs['name'].'<br>';
		$mail .= "Email: ". $qs['email'].'<br>';
		$mail .= "Telefon: ". $qs['phone'].'<br>';
		$mail .= "Sag: ". $qs['caseKey'].'<br>';
		$mail .= "Tid: ". date('d-m-Y', $input['startTime']).'<br>';
		$mail .= "Besked:<br>".$message;

		$headers = "From: noreply@".$_SERVER['SERVER_NAME'] . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		return wp_mail('mikkel@pbweb.dk', 'Fejl i modtagelse af fremvisning fra hjemmeside', $mail, $headers);

	}

	public function set_search_agent($input){

		$url = $this->base_url.'setSearchAgent';
		$allowed_fields = array(
			'orgKey',
			'name',
			'phone',
			'email',
			'callback',
			'message',
			'locations',
			'minPrice',
			'maxPrice',
			'minPropertySize',
			'maxPropertySize',
			'minRooms',
			'minLandSize',
			'maxLandSize',
			'propertyTypes',
			'buyingApprovementBank',
			'currentLivingStatus',
			'sellingProperty',
		);

		$qs = array(
			'orgKey' => $this->get_org_key()
		);

		if(!is_array($input)){
			return array('type' => "error", 'message' => 'Malformed input');
		}

		foreach ($input as $key => $value){
			if(in_array($key, $allowed_fields)){
				if(is_array($value)){
					$qs[filter_var($key, FILTER_SANITIZE_STRING)] = $value;
				}else{
					$qs[filter_var($key, FILTER_SANITIZE_STRING)] = ($value == 'on') ? true : filter_var($value, FILTER_SANITIZE_STRING);
				}
			}
		}

		if(!isset($qs['buyingApprovementBank'])){
			$qs['buyingApprovementBank'] = false;
		}
		if(!isset($qs['sellingProperty'])){
			$qs['sellingProperty'] = false;
		}


		$url .= '?'.http_build_query($qs);

		$args = $this->get_header_args();
		$args['method'] = 'POST';
		$response = wp_remote_post($url, $args);

		if($response['response']['code'] == 200){
			$return = array(
				'type' => 'success',
			);
			//new Flexyapress_Log('set_search_agent_success', serialize(array('url' => $url, 'args' => $args, 'fields' => $input)), serialize($response));
		}else{
			new Flexyapress_Log('set_search_agent_fail', serialize(array('url' => $url, 'args' => $args, 'fields' => $input)), serialize($response));
			$return = array(
				'type' => 'error',
				'message' => __('Der er sket en fejl ved afsendelsen af din forespørgsel. Prøv venligst igen', 'flexyapress'),
				'response' => $response
			);
		}

		return $return;

	}


	public function submit_flexya_form(){

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'submit-flexya-form' )
        ) {
            wp_send_json_error(['msg' => 'Dit request kunne ikke verificeres']);
        }

		//Check for robots
        if($this->captcha_secret_key) {

            $data = array(
                'secret' => $this->captcha_secret_key,
                'response' => $_POST['token']
            );

            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $captcha_response = curl_exec($verify);
            $captcha_response = json_decode($captcha_response);

            if ($captcha_response->success == false) {
                wp_send_json_error(array(
                    'type'     => 'error',
                    'message'  => __('Der er sket en fejl ved afsendelsen af din forespørgsel. Prøv venligst igen. Du er muligvis en robot',
                        'flexyapress'),
                    'response' => $captcha_response
                ));
            }
        }
		$response = $this->order($_POST);
		if(is_array($response)){
			wp_send_json_success($response);
		}
		wp_die();
	}

	public function submit_search_agent_form(){
		$data = array(
			'secret' => $this->captcha_secret_key,
			'response' => $_POST['token']
		);

		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$captcha_response = curl_exec($verify);
		$captcha_response = json_decode($captcha_response);

		if($captcha_response->success == false){
			echo json_encode(array(
				'type' => 'error',
				'message' => __('Der er sket en fejl ved afsendelsen af din forespørgsel. Prøv venligst igen. Du er muligvis en robot', 'flexyapress'),
				'response' => $captcha_response
			));
		}

		$response = $this->set_search_agent($_POST['data']);
		if(is_array($response)){
			echo json_encode($response);
		}
		wp_die();
	}

    public function search_properties(){
        foreach ($_GET['data'] as $key => $val){
            $_GET[$key] = $val;
        }
        ob_start();
        if(file_exists(get_stylesheet_directory() .'/mw/property-loop.php')){
            include get_stylesheet_directory() .'/mw/property-loop.php';
        }else{
            include WP_PLUGIN_DIR .'/flexyapress-mw/templates/property/property-loop.php';
        }
        echo ob_get_clean();
        wp_die();
    }

	public function enqueue_case_scripts(){
		return false;
	}

    public function generate_facebook_ad_catalog(){
        $cases = $this->get_cases();

        $cases_array = [];

        foreach ($cases as $case){

            /*if($case->status != 'ACTIVE'){
                continue;
            }*/

            $availability = 'for_sale';

            if($case->status == 'SOLD'){
                if(strtotime($case->soldDate) > strtotime('-1 month')){
                    $availability = 'recently_sold';
                }else{
                    $availability = 'off_market';
                }
            }

            if($case->status == 'ACTIVE' && $case->propertyClass == 'PRIVATE_RENTAL'){
                $availability = 'for_rent';
            }


            $fields = [
                'home_listing_id' => $case->caseNumber,
                'name' => Flexyapress_Helpers::create_address($case->address->roadname, $case->address->roadnumber, $case->address->floor, $case->address->door),
                'description' => $case->title,
                'availability' => $availability,
                'price' => number_format($case->price, 0, ',', '.').' '.$case->priceCurrency,
                'home_listing_group_id' => '',
                'ac_type' => '',
                'agent_name' => $case->realtorName,
                'agent_company' => $case->companyName,
                'furnish_type' => '',
                'tenure_type' => '',
                'sale_type' => $case->saleType,
                'garden_type' => '',
                'days_on_market' => $case->daysForSale,
                'url' => $case->homepageUrl,
                'fee_schedule_url' => '',
                'heating_type' => $case->heatingInstallation,
                'laundry_type' => '',
                'listing_type'	=> '',
                'agent_rera_id'	=> '',
                'property_rera_id'	=> '',
                'num_baths'	=> $case->numberBathrooms,
                'num_beds'	=> $case->numberBedrooms,
                'num_rooms'	=> $case->numberRooms,
                'num_units'	=> '',
                'parking_type'	=> '',
                'partner_verification'	=> '',
                'pet_policy' => '',
                'min_price'	=> '',
                'max_price'	=> '',
                'property_type'	=> $case->propertyType,
                'area_size'	=> $case->sizeArea,
                'built_up_area_size' => '',
                'property_tax' => '',
                'condo_fee' => '',
                'coownership_charge' => '',
                'parking_spaces'	=> '',
                'area_unit'	=> '',
                'year_built'	=> $case->constructionYear,
                'address.addr1'	=> Flexyapress_Helpers::create_address($case->address->roadname, $case->address->roadnumber, $case->address->floor, $case->address->door),
                'address.addr2'	=> '',
                'address.addr3'	=> '',
                'address.city'	=> $case->address->city,
                'address.city_id'	=> '',
                'address.region'	=> $case->address->region,
                'address.postal_code'	=> $case->address->zipcode,
                'address.country'	=> $case->address->country,
                'address.unit_number'	=> '',
                'latitude'	=> $case->latitude,
                'longitude'	=> $case->longitude,
                'neighborhood[0]'	=> '',
                'energy_rating_eu.grade'	=> '',
                'energy_rating_eu.value'	=> '',
                'co2_emission_rating_eu.grade'	=> '',
                'co2_emission_rating_eu.value'	=> '',
                'additional_fees_description'	=> '',
                'num_pets_allowed'	=> '',
                'land_area_size'	=> '',
                'security_deposit'	=> '',
                'holding_deposit'	=> '',
                'application_fee'	=> '',
                'pet_deposit'	=> '',
                'pet_monthly_fee'	=> '',
                'floor_types[0]'	=> '',
                'unit_features[0]'	=> '',
                'construction_status'	=> '',
                'coownership_num_lots'	=> '',
                'coownership_status'	=> '',
                'coownership_proceedings_status'	=> '',
                'special_offers[0]'	=> '',
                'pet_restrictions[0]'	=> '',
                'building_amenities[0]'	=> '',
                'broker_fee'	=> '',
                'first_month_rent'	=> '',
                'last_month_rent'	=> '',
                'utilities_included_in_rent[0]'	=> '',
                'rental_room_type'	=> '',
                'private_room_bathroom_type'	=> '',
                'number_of_co_renters'	=> '',
                'private_room_area_size'	=> '',
                'virtual_tour_url'	=> '',
                'applink.android_app_name'	=> '',
                'applink.android_package'	=> '',
                'applink.android_url'	=> '',
                'applink.ios_app_name'	=> '',
                'applink.ios_app_store_id'	=> '',
                'applink.ios_url'	=> '',
                'applink.ipad_app_name'	=> '',
                'applink.ipad_app_store_id'	=> '',
                'applink.ipad_url'	=> '',
                'applink.iphone_app_name'	=> '',
                'applink.iphone_app_store_id'	=> '',
                'applink.iphone_url'	=> '',
                'applink.windows_phone_app_id'	=> '',
                'applink.windows_phone_app_name'	=> '',
                'applink.windows_phone_url'	=> '',
                'image[0].url' => $case->urlPhotos[0],
                'image[0].tag[0]' => $case->photoTexts[0],
                'image[1].url' => $case->urlPhotos[1],
                'image[1].tag[0]' => $case->photoTexts[1],
                'image[2].url' => $case->urlPhotos[2],
                'image[2].tag[0]' => $case->photoTexts[2],
            ];

            $cases_array[] = $fields;

        }

        $folder_path = WP_CONTENT_DIR . '/flexya/';
        $csv_file_path = $folder_path . 'facebook_home_listing.csv';

        if(!file_exists($folder_path)){
            mkdir($folder_path, 0755);
        }

        $csv_file = fopen($csv_file_path, 'w');
        //fputs($csv_file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        $headers = array_keys($cases_array[0]);
        fputcsv($csv_file, $headers);
        foreach ($cases_array as $case){
            fputcsv($csv_file, $case);
        }
        fclose($csv_file);
    }


    public static function getConsents($name = ''){

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

    public static function getShopNo(){
        return get_option('flexyapress')['shop-no'];;
    }


}