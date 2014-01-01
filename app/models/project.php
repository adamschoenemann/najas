<?php
namespace Models;

class Project extends SlugModel {

	function __construct(\DB\SQL $db){
		parent::__construct($db, "projects");
	}


	/*
	Return false if any field is empty
	*/
	function is_valid(){
		return (
			!empty($this->title) &&
			!empty($this->author) &&
			!empty($this->teaser) &&
			!empty($this->description)
			);
	}


	public function load_galleries(){
		$q = 
			"SELECT * FROM " . \API\Galleries::TABLE_NAME .
			" WHERE project_id=" . $this->id;
		// echo $q;
		$result = $this->db->exec($q);
		// print_r($result);
		$this->extra_fields["galleries"] = $result;
	}



}