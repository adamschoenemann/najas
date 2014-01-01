<?php
namespace API;
class Galleries extends API {

	const TABLE_NAME = "galleries";

	public function create($f3, $params){
		try {
			$post = $f3->get("POST");
			if(empty($post["title"])){
				throw new \InvalidArgumentException("No title specified");
			}
			if(empty($post["project_id"])){
				throw new \InvalidArgumentException("A project_id must be specified");
			}
			$model = new \Models\Gallery($this->db);
			$model->copyFrom("POST");
			$model->save();
			echo \Utils::json_encode($model->cast());

		} catch (\Exception $e){
			$this->handle_exception($e);
		}
	}

	public function view($f3, $params){
		$model = parent::_view($f3, $params, new \Models\Gallery($this->db));
		if(is_object($model)){
			echo \Utils::json_encode($model->cast());
		}
	}

	public function view_all($f3, $params){
		$models = parent::_view_all($f3, $params, self::TABLE_NAME);
		if(is_array($models)){
			echo \Utils::json_encode($models);
		}
	}

	public function update($f3, $params){
		$model = parent::_update($f3, $params, new \Models\Gallery($this->db));
		if(is_array($models)){
			echo \Utils::json_encode($model);;
		}
	}

	public function delete($f3, $params){
		$response = parent::_delete($f3, $params, self::TABLE_NAME);
		if($response){
			echo \Utils::json_encode($response);
		}
	}

}