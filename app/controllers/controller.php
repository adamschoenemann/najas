<?php
namespace Controllers;
//! Base controller
class Controller {

  protected $db;

  function show($f3, $args){
    //echo "hey";
  }

  function beforeroute($f3){
  	  $f3->push("js", "js/lib/jquery-1.10.2.js");
  	  $f3->push("js", "js/lib/modernizr.custom.js");
  	  // $f3->push("js", "js/lib/jquery.form.min.js");
  	  // $f3->push("js", "js/lib/jquery.form.js");
  	  $f3->push("js", "js/lib/underscore-min.js");
  	  $f3->push("js", "js/lib/backbone.js");
  	  $f3->push("js", "js/lib/lightbox-2.6.min.js");
  	  $f3->push("js", "js/lib/jquery.ui.widget.js");
  	  $f3->push("js", "js/lib/jquery.fileupload.js");

  	  $f3->push("css", "css/lightbox.css");
  }

  function afterroute($f3){
  	$f3->set("URL", $_SERVER['REQUEST_URI']);
  	$f3->set("DIR", $this->get_dir($_SERVER['REQUEST_URI']));
  	$f3->set("CTRL", $this->get_controller_uri());
	// Render HTML layout
	echo \Template::instance()->render('layout.html');
  }

  function __construct(){
    $f3 = \Base::instance();
    // Connect to the database
    $db = new \DB\SQL(
      $f3->get("db"),
      $f3->get("admin"),
      $f3->get("pass")
    );
	$this->db = $db;
	
	$css = array();
	$f3->set("css", $css);

	$js = array();
	$f3->set("js", $js);
  }


  private function get_dir($url){
  	return substr($url, 0, strrpos($url, '/'));
  }

  protected function get_controller_uri(){
  	return "/najas";
  }

  protected function make_anchor($name, $href){
  	return array("href" => $href, "name" => $name);
  }
}
