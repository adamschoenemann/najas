<?php
namespace Controllers\Admin;
class Projects extends Admin {

	function beforeroute($f3){
		parent::beforeroute($f3);
		$f3->set("page", "Projects");
		$f3->set("submenu", array(
			$this->make_anchor("View All", "view"),
			$this->make_anchor("Create New Project", "create")
		));
	}

	function show($f3, $args){
		$f3->set("projects", $this->db->exec(
			"SELECT * FROM projects"
		));
		$f3->set("action", "View Projects");
		$f3->set("content", $this->get_content("projects"));
		$json = json_encode($f3->get("projects"));
		// echo "<pre>" . print_r($json) . "</pre>";
	}

	function view($f3, $params){
		$id = $params["id"];
		
		// Get stuff about the project
	}

	function edit($f3){
		$id = $f3->get("PARAMS.id");
		if(is_numeric($id) == false){
			$f3->error(404);
		}
		$projects = $this->db->exec(
			"SELECT * FROM projects WHERE id=$id"
		);
		$f3->set("project", $projects[0]);
		$f3->set("action", "Edit Project");
		$f3->set("content", $this->get_content("edit"));
	}

	function create($f3){
		$project = new \Models\Project($this->db);
		$f3->set("project", $project);
		$f3->set("action", "Create Project");
		$f3->set("content", $this->get_content("edit"));
	}

	function POST_create($f3){
		try {
			$project = new \Models\Project($this->db);
			$project->copyFrom("POST");
			if($project->is_valid() == false){
				throw new \UnexpectedValueException("Invalid post data supplied");
			}
			$project->save();
			$f3->reroute("/admin/projects/edit/" . $project->id);
		} catch (Exception $e){
			$this->handle_exception($e);
		}
	}

	private function handle_exception($e){
		echo "<br>Caught exception: " . $e->getMessage() . "<br>";
		echo "<br>Stack Trace:<br>", $e->getTraceAsString(), "<br>";
		$f3->error(500);
	}

	function POST_edit($f3){
		try {
			$id = $f3->get("PARAMS.id");
			if(is_numeric($id) == false)
				throw new \InvalidArgumentException("No project id supplied");

			$project = new \Models\Project($this->db);
			$project->load(array("id=?", $id));
			$project->copyFrom("POST");

			if($project->dry() == false)
				throw new \InvalidArgumentException("id not present in database");

			if($project->is_valid() == false)
				throw new \UnexpectedValueException();

			$project->save();

		} catch (Exception $e) {
			$this->handle_exception($e);
		}
	}

	function delete($f3){

	}

	function get_content($name){
		return "admin/projects/" . $name . ".html";
	}

	function get_controller_uri(){
		return parent::get_controller_uri() . "/projects";
	}

}