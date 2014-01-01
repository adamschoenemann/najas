<?php
namespace Models;
class SlugModel extends Model {

	function __construct(\DB\SQL $db, $table){
		parent::__construct($db, $table);
	}

	function save(){
		$this->generate_slug();
		parent::save();
	}

	/*
	Generate and assign slug from title
	*/
	protected function generate_slug(){
		$this->slug = \StringUtils::toAscii($this->title);  	
	}

}