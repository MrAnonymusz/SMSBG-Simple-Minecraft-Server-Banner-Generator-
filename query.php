<?php
ob_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once(__DIR__.'\vendor\autoload.php');
require_once(__DIR__.'\config.php');

class serverQuery
{
  private $_address;
  private $_key;

  public function __construct()
  {
    global $config;

    $this->_address = $_GET['address'];

    if(empty($this->_address))
    {
      $this->error = 1;
      $this->error_message = "Please specify a server address for the query!";
    }

    if($config['enable_key'] == true)
    {
      $this->_key = $_GET['key'];

      if(!empty($this->_key))
      {
        if($this->_key != $config['app_key'])
        {
          $this->error = 1;
          $this->error_message = "The specified key is invalid!";
        }
      }
      else
      {
        $this->error = 1;
        $this->error_message = "Please specify the app key!";
      }
    }

    if($this->error != 1)
    {
      $this->api = file_get_contents($config['query_api'].$this->_address);

      echo $this->api;
    }
    else
    {
      echo json_encode([
        'error' => $this->error_message
      ]);
    }
  }
}

$query = new serverQuery;

ob_end_flush();