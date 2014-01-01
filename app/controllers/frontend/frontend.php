<?php
class Frontend extends Controller {

	function show($f3, $args){
		$f3->push("css", "css/theme.css");
	}

	function __construct(){
		parent::__construct();
		$f3->set("inc", "frontend.css");
	}
}