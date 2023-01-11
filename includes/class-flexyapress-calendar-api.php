<?php

class Flexyapress_Calendar_API{
	
	private $api;
	private $org_key;
	private $token;
	private $office_id;
	
	public function __construct(){
		$this->api = new Flexyapress_API();
	}

}