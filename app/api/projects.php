<?php
namespace API;
class Projects extends API {

	const TABLE_NAME = "projects";

	public function view_all($f3, $params){
		$models = parent::_view_all($f3, $params, self::TABLE_NAME);
		if(is_array($models)){
			echo \Utils::json_encode($models);
		}
	}

	public function view($f3, $params){
		$model = new \Models\Project($this->db);
		$model = parent::_view($f3, $params, $model);
		if(is_object($model)){
			$model->load_galleries();
			echo \Utils::json_encode($model->cast());
		}
	}

	public function create($f3){
		try {
			$post = $f3->get("POST");
			if(empty($post["title"])){
				throw new \InvalidArgumentException("A title must be supplied");
			}
			$project = new \Models\Project($this->db);
			$project->copyFrom("POST");
			$project->save();
			echo \Utils::json_encode($project->cast());

		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}

	public function update($f3, $params){
		$model = parent::_update($f3, $params, new \Models\Project($this->db));
		if(is_object($model)){
			echo \Utils::json_encode($model->cast());
		}
	}

/*	public function update($f3, $params){
		try {
			$post = $f3->get("POST");
			$id = $params["id"];
			if(is_numeric($id) == FALSE){
				throw new \InvalidArgumentException("An id must be supplied");
			}
			$project = new \Models\Project($this->db);
			$project->load(array("id=?", $id));
			if($project->dry()){
				throw new \UnexpectedValueException("Project id not found");
			}
			$project->copyFrom("POST");
			$project->save();
			echo json_encode($project->cast());
		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}*/

	public function delete($f3, $params){
		parent::_delete($f3, $params, self::TABLE_NAME);
	}
}

