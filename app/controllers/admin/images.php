<?php
namespace Controllers\Admin;
class Images extends Admin {

	function beforeroute($f3){
		parent::beforeroute($f3);
		$f3->set("page", "Images");
		$f3->set("submenu", array(
			$this->make_anchor("View All", "view"),
			$this->make_anchor("Upload image", "create")
			));
		$f3->push("js", "js/jq-file-upload/jquery.ui.widget.js");
		$f3->push("js", "js/jq-file-upload/jquery.fileupload.js");
		$f3->push("js", "js/jq-file-upload/jquery.iframe-transport.js");
	}

	function show($f3, $args){
		$f3->set("images", $this->db->exec(
			"SELECT * FROM images"
			));
		$f3->set("action", "View Images");
		$f3->set("content", $this->get_content("images"));
		// $json = json_encode($f3->get("images"));
		// echo "<pre>" . print_r($json) . "</pre>";
	}

	function create($f3){
		$image = new \Models\Image($this->db);
		$f3->set("image", $image);
		$f3->set("action", "Upload image");
		$f3->set("content", $this->get_content("edit"));
	}

	function POST_create($f3){
		try {
			$image = new \Models\Gallery($this->db);
			$image->copyFrom("POST");
			if($image->is_valid() == false){
				throw new \UnexpectedValueException("Invalid post data supplied");
			}
			$image->project_id = 1; // test
			$image->save();
			// $f3->reroute("/admin/images/edit/" . $image->id);
		} catch (Exception $e){
			$this->handle_exception($e);
		}
	}

	function get_content($name){
		return "admin/images/" . $name . ".html";
	}

	function get_controller_uri(){
		return parent::get_controller_uri() . "/images";
	}
}