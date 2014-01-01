<?php
namespace Controllers\Admin;
class Admin extends \Controllers\Controller {

  function beforeroute($f3){
  	parent::beforeroute($f3);
  	$f3->set("inc", "admin/admin.html");
  	
  	$f3->push("css", "css/transdmin.css");

  	// $f3->push("js", "js/jNice.js"); // Causes a bug with jQuery form

  	$f3->set("menu", array(
  		$this->createMenu("Dashboard", ""),
  		$this->createMenu("Projects", "/projects"),
  		$this->createMenu("Galleries", "/galleries"),
  		$this->createMenu("Images", "/images")
  	));
  }

  function show($f3, $args){
  	$f3->reroute("/admin/dashboard");
  }

  function projects($f3, $args){
 
    
  }

  function dashboard($f3, $args){
  	$f3->set("page", "Dashboard");
  	$f3->set("action", "Dashboard");
  	
  }

  function gallery($f3, $args){

  }

  function images($f3, $args){

  }

  function playground($f3, $args)
  {
  	$f3->set("page", "Playground");
  	$f3->set("action", "Playground");
  	// $f3->set("content", "admin/playground.html");
  	echo \Template::instance()->render("admin/playground.html");
  	die();
  }

  protected function createMenu($name, $href){
  	return array(
  		"name" => $name,
  		"href" => "../admin".$href
  	);
  }

  protected function get_controller_uri(){
  	return parent::get_controller_uri() . "/admin";
  }

}