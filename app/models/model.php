<?php
namespace Models;
class Model extends \DB\SQL\Mapper {

	protected $extra_fields;

	function __construct(\DB\SQL $db, $table){
		parent::__construct($db, $table);
		$this->extra_fields = array();
	}

	public function cast($obj = NULL){
		$result = parent::cast($obj);
		if(is_array($this->extra_fields)){
			foreach ($this->extra_fields as $key => $value) {
				$result[$key] = $value;
			}
		}
		return $result;
	}

}