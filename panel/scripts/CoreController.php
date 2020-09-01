<?php

namespace App;

error_reporting(E_ALL & ~E_NOTICE);

ob_start();

require_once("..".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php");
require_once("..".DIRECTORY_SEPARATOR."config.php");
require_once(".".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."Database.php");

use App\Helpers\Database;

class CoreController extends Database
{
  /*
    >> Database Connection
  */

  public function db()
  {
    $db = new Database;

    return $db->connect();
  }

  /*
    >> Database Build Schema
  */

  public function db_build_schema()
  {
    $db = new Database;

    return $db->build_schema();
  }
}

ob_end_flush();