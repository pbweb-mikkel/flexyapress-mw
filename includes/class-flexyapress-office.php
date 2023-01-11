<?php

class Flexyapress_Office{

	private $id;
	private $officeId;
	private $officeKey;
	private $address;
	private $city;
	private $zipcode;
	private $phone;
	private $name;
	private $email;
	private $realtorName;
	private $realtorEmail;
	private $realtorPhoto;
	private $realtorPhone;

	/**
	 * Flexyapress_Realtor constructor.
	 *
	 * @param $id "The ID or Email"
	 */
	public function __construct( $id = null, $office_id = null, $office_key = null ) {
		if($id){
			$this->setId($id);
		}

		if($office_id){
			$this->setOfficeId($office_id);
			$this->setId(self::findIdByOfficeId($this->getOfficeId()));
		}

		if($office_key){
			$this->setOfficeKey($office_key);
			$this->setId(self::findIdByOfficeKey($this->getOfficeKey()));
		}
	}

	public function save(){
		$postarr = array(
			'ID'			=> $this->getId(),
			'post_title'	=> $this->getName(),
			'post_type'		=> 'office',
			'post_status'	=> 'publish',
			'meta_input'	=>	array(
				'officeId'      => $this->getOfficeId(),
				'officeKey'     => $this->getOfficeKey(),
				'address'       => $this->getAddress(),
				'city'          => $this->getCity(),
				'zipcode'       => $this->getZipcode(),
				'phone'         => $this->getPhone(),
				'email'         => $this->getEmail(),
				'name'         => $this->getName(),
				'realtorEmail'	=> $this->getRealtorEmail(),
				'realtorPhone'	=> $this->getRealtorPhone(),
				'realtorName'	=> $this->getRealtorName(),
				'realtorPhoto'  => $this->getRealtorPhoto(),
			),
		);

		$id = wp_insert_post($postarr);

		if($id && !$this->getId()){
			$this->setId($id);
		}

		return $id;
	}

	public function fetch(){
		if(!$this->getId()){
			return false;
		}

		$meta = get_post_meta($this->getId(), null, true);

		foreach ($meta as $key => $value){
			$value = array_shift($value);
			//Go through the easy properties fast
			if(property_exists($this, $key)){
				$funcName = "set".ucfirst($key);
				$this->$funcName($value);
			}
		}

	}

	public static function findIdByOfficeId($office_id){
		$search = Flexyapress_Helpers::get_post_by_meta_value('office', 'officeId', $office_id, 1);
		$id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;
		return $id;
	}

	public static function findIdByOfficeKey($office_key){
		$search = Flexyapress_Helpers::get_post_by_meta_value('office', 'officeKey', $office_key, 1);
		$id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;
		return $id;
	}

	/**
	 * @return mixed
	 */
	public function getFullAddress() {
		return $this->getAddress() .', '.$this->getZipcode().' '.$this->getCity();
	}


	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getOfficeId() {
		return $this->officeId;
	}

	/**
	 * @param mixed $office_id
	 */
	public function setOfficeId( $office_id ) {
		$this->officeId = $office_id;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
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
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param mixed $phone
	 */
	public function setPhone( $phone ) {
		$this->phone = $phone;
	}

	/**
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email ) {
		$this->email = $email;
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
	public function getRealtorPhoto() {
		return $this->realtorPhoto;
	}

	/**
	 * @param mixed $realtorPhoto
	 */
	public function setRealtorPhoto( $realtorPhoto ) {
		$this->realtorPhoto = $realtorPhoto;
	}

	/**
	 * @return mixed
	 */
	public function getRealtorPhone() {
		return $this->realtorPhone;
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
	public function getOfficeKey() {
		return $this->officeKey;
	}

	/**
	 * @param mixed $office_key
	 */
	public function setOfficeKey( $office_key ) {
		$this->officeKey = $office_key;
	}





}