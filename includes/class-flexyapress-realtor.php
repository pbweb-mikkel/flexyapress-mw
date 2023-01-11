<?php

class Flexyapress_Realtor{

	private $id;
	private $realtorID;
	private $realtorName;
	private $realtorTitle;
	private $realtorEmail;
	private $realtorPhone;
	private $realtorMobilePhone;
	private $realtorPhoto;
	private $realtorOffice;
	private $realtorLinkedIn;
	private $realtorFacebook;

	/**
	 * Flexyapress_Realtor constructor.
	 *
	 * @param $id "The ID or Email"
	 */
	public function __construct( $id = null ) {
		if(is_numeric($id)){
			$this->setId($id);
		}else if(is_string($id)){
			$this->setRealtorEmail($id);
			$this->setId(self::findIdByEmail($this->getRealtorEmail()));
		}
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

	public function save(){

        var_dump($this->getRealtorId());

		$postarr = array(
			'ID'			=> $this->getId(),
			'post_title'	=> $this->getRealtorName(),
			'post_type'		=> 'realtor',
			'post_status'	=> 'publish',
			'meta_input'	=>	array(
				'realtorID' => $this->getRealtorID(),
				'realtorEmail'	=> $this->getRealtorEmail(),
				'realtorPhone'	=> $this->getRealtorPhone(),
				'realtorMobilePhone'	=> $this->getRealtorMobilePhone(),
				'realtorName'	=> $this->getRealtorName(),
				'realtorTitle'	=> $this->getRealtorTitle(),
				'realtorOffice'	=> $this->getRealtorOffice(),
				'realtorPhoto'  => $this->getRealtorPhoto(),
				'realtorLinkedin'  => $this->getRealtorLinkedIn(),
				'realtorFacebook'  => $this->getRealtorFacebook(),
			),
		);

		$id = wp_insert_post($postarr);

		if($id && !$this->getId()){
			$this->setId($id);
		}

		return $id;
	}

	public static function findIdByEmail($email){
		$search = Flexyapress_Helpers::get_post_by_meta_value('realtor', 'realtorEmail', $email, 1);
		$id = (is_array($search) && count($search) == 1) ? $search[0]->ID : null;
		return $id;
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
	public function getRealtorName() {
		return $this->realtorName;
	}

	/**
	 * @param mixed $name
	 */
	public function setRealtorName( $name ) {
		$this->realtorName = $name;
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
    public function getRealtorOffice()
    {
        return $this->realtorOffice;
    }

    /**
     * @param mixed $realtorOffice
     */
    public function setRealtorOffice($realtorOffice)
    {
        $this->realtorOffice = $realtorOffice;
    }

	/**
	 * @return mixed
	 */
	public function getRealtorEmail() {
		return $this->realtorEmail;
	}

	/**
	 * @param mixed $email
	 */
	public function setRealtorEmail( $email ) {
		$this->realtorEmail = $email;
	}

	/**
	 * @return mixed
	 */
	public function getRealtorPhone() {
		return $this->realtorPhone;
	}

	/**
	 * @return mixed
	 */
	public function getPrettyRealtorPhone() {

		if(strlen($this->realtorPhone) == 8){
			return substr($this->realtorPhone, 0, 4).' '.substr($this->realtorPhone, 4, 4);
		}else{
			return $this->realtorPhone;
		}

	}

	/**
	 * @param mixed $realtorPhone
	 */
	public function setRealtorPhone( $realtorPhone ) {

		if(strlen($realtorPhone) == 8){
			$realtorPhone = substr($realtorPhone, 0, 4).' '.substr($realtorPhone, 4, 4);
		}

		$this->realtorPhone = $realtorPhone;
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

		$realtorPhoto = str_replace('/100/', '/', $realtorPhoto);
		$this->realtorPhoto = $realtorPhoto;
	}

	/**
	 * @return mixed
	 */
	public function getRealtorID() {
		return $this->realtorID;
	}

	/**
	 * @param mixed $realtorID
	 */
	public function setRealtorID( $realtorID ) {
		$this->realtorID = $realtorID;
	}

    /**
     * @return mixed
     */
    public function getRealtorMobilePhone()
    {
        return $this->realtorMobilePhone;
    }

    /**
     * @param mixed $realtorMobilePhone
     */
    public function setRealtorMobilePhone($realtorMobilePhone)
    {
        $this->realtorMobilePhone = $realtorMobilePhone;
    }

    /**
     * @return mixed
     */
    public function getRealtorLinkedIn()
    {
        return $this->realtorLinkedIn;
    }

    /**
     * @param mixed $realtorLinkedIn
     */
    public function setRealtorLinkedIn($realtorLinkedIn)
    {
        $this->realtorLinkedIn = $realtorLinkedIn;
    }

    /**
     * @return mixed
     */
    public function getRealtorFacebook()
    {
        return $this->realtorFacebook;
    }

    /**
     * @param mixed $realtorFacebook
     */
    public function setRealtorFacebook($realtorFacebook)
    {
        $this->realtorFacebook = $realtorFacebook;
    }





}