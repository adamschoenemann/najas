<?php
namespace Controllers\Admin;
class Galleries extends Admin {

	function beforeroute($f3){
		parent::beforeroute($f3);
		$f3->set("page", "Galleries");
		$f3->set("submenu", array(
			$this->make_anchor("View Galleries", "view"),
			$this->make_anchor("Create New Gallery", "create")
			));
	}

	function show($f3, $args){
		$f3->set("galleries", $this->db->exec(
			"SELECT * FROM galleries"
			));
		$f3->set("action", "View Galleries");
		$f3->set("content", $this->get_content("galleries"));
		$json = json_encode($f3->get("galleries"));
		// echo "<pre>" . print_r($json) . "</pre>";
	}

	function create($f3){
		$gallery = new \Models\Gallery($this->db);
		$f3->set("gallery", $gallery);
		$f3->set("action", "Create Gallery");
		$f3->set("content", $this->get_content("create"));
	}

	function edit($f3, $params){
		$id = $params["id"];
		$gallery = new \Models\Gallery($this->db);
		$gallery->load(array("id=?", $id));
		$f3->set("gallery", $gallery);
		$f3->set("action", "Edit Gallery");
		$f3->set("content", $this->get_content("edit"));
		$f3->push("js", "../app/lib/image-browser/js/image-browser.js");
		$f3->push("css", "../app/lib/image-browser/css/imgbrowser.css");
	}

	function POST_create($f3){
		try {
			$gallery = new \Models\Gallery($this->db);
			$gallery->copyFrom("POST");
			if($gallery->is_valid() == false){
				throw new \UnexpectedValueException("Invalid post data supplied");
			}
			$gallery->project_id = 1; // test
			$gallery->save();
			// $f3->reroute("/admin/galleries/edit/" . $gallery->id);
		} catch (Exception $e){
			$this->handle_exception($e);
		}
	}

	function get_content($name){
		return "admin/galleries/" . $name . ".html";
	}

	function get_controller_uri(){
		return parent::get_controller_uri() . "/galleries";
	}
}