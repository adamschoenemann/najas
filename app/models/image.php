<?php
namespace Models;
class Image extends SlugModel {
  
  function __construct(\DB\SQL $db){
    parent::__construct($db, "images");
  }

  
}
