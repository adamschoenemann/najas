<?php
namespace API;
class API {

	protected $db;
	protected $f3;

	function __construct(){
		$f3 = \Base::instance();
    	// Connect to the database
		$db = new \DB\SQL(
			$f3->get("db"),
			$f3->get("admin"),
			$f3->get("pass")
			);
		$this->db = $db;
		$this->f3 = $f3;
	}

	protected function _view_all($f3, $params, $table){
		$projects = $this->db->exec(
			"SELECT * FROM $table"
			);
		return $projects;
	}

	protected function _view($f3, $params, \DB\SQL\Mapper $model){
		try {
			$id = $params["id"];
			if(is_numeric($id) === false){
				throw new \InvalidArgumentException("Illegal id provided");
			}
			$model->load(array("id=?", $id));
			if($model->dry()){
				throw new \UnexpectedValueException("id not found in database");
			}
			return $model;
		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}

	protected function _update($f3, $params, \DB\SQL\Mapper $model){
		try {
			$post = $f3->get("POST");
			$id = $params["id"];
			if(is_numeric($id) == FALSE){
				throw new \InvalidArgumentException("An id must be supplied");
			}
			$model->load(array("id=?", $id));
			if($model->dry()){
				throw new \UnexpectedValueException("Project id not found");
			}
			$model->copyFrom("POST");
			$model->save();
			return $model;
		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}

	protected function _delete($f3, $params, $table){
		try {
			$id = $params["id"];
			if(is_numeric($id) == FALSE){
				throw new \InvalidArgumentException("An id must be supplied");
			}
			$this->db->exec(
				"DELETE FROM $table WHERE id=$id"
			);
			return array("success" => 1);
		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}

	function handle_exception(\Exception $e){
		$error = array(
			"message" => $e->getMessage()
		);
		if($this->f3->get("DEBUG")){
			$error["stacktrace"] = $e->getTraceAsString();
		}
		echo \Utils::json_encode(array($error));
		// echo mysql_info();
		$this->f3->status(400);
	}
}