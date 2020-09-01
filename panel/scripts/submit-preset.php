<?php
error_reporting(E_ALL & ~E_NOTICE);

header("Content-type: text/html;charset=utf-8");

require_once("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php");
require_once("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.php");
require_once("..".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."Database.php");

use App\Helpers\Database;

ob_start();

class SubmitPreset extends Database
{
  private $name;
  private $address;
  private $display_name;
  private $img;
  private $font;
  private $color;
  private $type;
  private $key;
  private $output;

  public function __construct()
  {
    global $config;

    $this->name = mb_strtolower($_POST['preset_name']);
    $this->name = trim($this->name);
    $this->name = htmlspecialchars($this->name);

    $this->address = $_POST['server_address'];
    $this->address = trim($this->address);
    $this->address = htmlspecialchars($this->address);

    $this->display_name = $_POST['preset_display_name'];
    $this->display_name = trim($this->display_name);
    $this->display_name = htmlspecialchars($this->display_name);

    $this->img = $_POST['banner_img'];
    $this->img = trim($this->img);
    $this->img = htmlspecialchars($this->img);

    $this->font = strtolower($_POST['banner_font']);
    $this->font = trim($this->font);
    $this->font = htmlspecialchars($this->font);

    $this->color = $_POST['banner_color'];
    $this->color = trim($this->color);
    $this->color = htmlspecialchars($this->color);

    $this->type = $_POST['banner_type'];
    $this->type = trim($this->type);
    $this->type = htmlspecialchars($this->type);

    $this->key = $_POST['app_key'];
    $this->key = trim($this->key);
    $this->key = htmlspecialchars($this->key);

    if(empty($this->name))
    {
      $this->error = 1;
      $this->error_name = "The name field must have a value.";
    }
    else if(strlen($this->name) > 128)
    {
      $this->error = 1;
      $this->error_name = "The name must be less than 128.";
    }
    else if(strlen($this->name) < 3)
    {
      $this->error = 1;
      $this->error_name = "The name must be greater than 3.";
    }
    else if(!preg_match('/^[a-zA-Z0-9]+$/', $this->name))
    {
      $this->error = 1;
      $this->error_name = "The selected name is invalid.";
    }
    else
    {
      $this->error_name = "";
    }

    if(empty($this->address))
    {
      $this->error = 1;
      $this->error_address = "The address field must have a value.";
    }
    else if(strlen($this->address) > 128)
    {
      $this->error = 1;
      $this->error_address = "The address must be less than 128.";
    }
    else if(strlen($this->address) < 3)
    {
      $this->error = 1;
      $this->error_address = "The address must be greater than 3.";
    }
    else
    {
      $this->error_address = "";
    }

    if(!empty($this->display_name))
    {
      if(strlen($this->display_name) > 128)
      {
        $this->error = 1;
        $this->error_display_name = "The display name must be less than 128.";
      }
      else if(strlen($this->display_name) < 3)
      {
        $this->error = 1;
        $this->error_display_name = "The display name must be greater than 3.";
      }
      else
      {
        $this->error_display_name = "";
      }
    }
    else
    {
      $this->error_display_name = "";
    }

    if(empty($this->img))
    {
      $this->error = 1;
      $this->error_img = "The img field must have a value.";
    }
    else if(!filter_var($this->img, FILTER_VALIDATE_INT))
    {
      $this->error = 1;
      $this->error_img = "The selected img is invalid.";
    }
    else
    {
      $this->image_list = array_diff(scandir("..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."background"), ['..', '.']);

      if(!in_array('banner_'.$this->img.'.jpg', $this->image_list))
      {
        $this->error = 1;
        $this->error_img = "The selected img is invalid.";
      }
      else
      {
        $this->error_img = "";
      }
    }

    if(empty($this->color))
    {
      $this->error = 1;
      $this->error_color = "The color field must have a value.";
    }
    else if(strlen($this->color) > 7)
    {
      $this->error = 1;
      $this->error_color = "The color must be less than 7.";
    }
    else if(strlen($this->color) < 4)
    {
      $this->error = 1;
      $this->error_color = "The color must be greater than 4.";
    }
    else if(preg_match('/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/', $this->color))
    {
      $this->error = 1;
      $this->error_color = "The selected color is invalid.";
    }
    else
    {
      $this->error_color = "";
    }

    if(empty($this->font))
    {
      $this->error = 1;
      $this->error_font = "The font field must have a value.";
    }
    else if(strlen($this->font) > 128)
    {
      $this->error = 1;
      $this->error_font = "The font must be less than 128.";
    }
    else if(strlen($this->font) < 2)
    {
      $this->error = 1;
      $this->error_font = "The font must be greater than 2.";
    }
    else
    {
      $this->font_list = array_diff(scandir('..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."fonts'), ['..', '.']);

      if(!in_array($this->font.'-regular.ttf', $this->font_list))
      {
        $this->error = 1;
        $this->error_font = "The selected font is invalid.";
      }
      else
      {
        $this->error_font = "";
      }
    }

    $this->type_list = $config['type_list'];

    if(empty($this->type))
    {
      $this->error = 1;
      $this->error_type = "The type field must have a value.";
    }
    else if(!filter_var($this->type, FILTER_VALIDATE_INT))
    {
      $this->error = 1;
      $this->error_type = "The selected 'type' is invalid.";
    }
    else if(!in_array($this->type, $this->type_list))
    {
      $this->error = 1;
      $this->error_type = "The selected 'type' is invalid.";
    }
    else
    {
      $this->error_type = "";
    }

    if(empty($this->key))
    {
      $this->error = 1;
      $this->error_key = "The key field must have a value.";
    }
    else if($this->key != $config['app_key'])
    {
      $this->error = 1;
      $this->error_key = "The selected key is invalid.";
    }
    else
    {
      $this->error_key = "";
    }

    if($this->error != 1)
    {
      switch($config['preset-driver'])
      {
        case 'database':
          $db = new Database;

          $this->check_preset = $db->connect()->table('presets')->where('name', $this->name)->count();

          if($this->check_preset != 1)
          {
            $db->connect()->table('presets')->insert([
              'name' => $this->name,
              'address' => $this->address,
              'display_name' => !empty($this->display_name) ? $this->display_name : NULL,
              'font' => $this->font,
              'color' => '#'.$this->color,
              'background' => $this->img,
              'type' => $this->type
            ]);

            $this->output = [
              'error' => 0,
            ];
          }
          else
          {
            $this->output = [
              'error' => 1,
              'messages' => [
                'general' => 'This preset name is already taken!'
              ]
            ];
          }
          break;
        
        case 'file':
          $this->preset_file_dir = '..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."presets.json';

          $this->get_presets = json_decode(file_get_contents($this->preset_file_dir), true);

          if(empty($this->get_presets))
          {
            $this->get_presets = [];
          }

          if(!array_key_exists($this->name, $this->get_presets))
          {
            if(file_exists($this->preset_file_dir) && is_writable($this->preset_file_dir))
            {
              $this->get_presets[$this->name] = [
                'address' => $this->address,
                'display_name' => !empty($this->display_name) ? $this->display_name : '',
                'font' => $this->font,
                'color' => '#'.$this->color,
                'background' => $this->img,
                'type' => $this->type
              ];
  
              $this->preset_file = fopen($this->preset_file_dir, "w");
  
              fwrite($this->preset_file, json_encode($this->get_presets));
  
              fclose($this->preset_file);

              $this->output = [
                'error' => 0,
              ];
            }
            else
            {
              $this->output = [
                'error' => 1,
                'messages' => [
                  'general' => 'This preset file doesn\'t exist or is not writable!'
                ]
              ]; 
            }
          }
          else
          {
            $this->output = [
              'error' => 1,
              'messages' => [
                'general' => 'This preset name is already taken!'
              ]
            ];
          }
          break;

        default:
          $this->output = [
            'error' => 1,
            'messages' => [
              'general' => 'Invalid Banner Preset Driver!'
            ]
          ];
          break;
      }

      echo json_encode($this->output);
    }
    else
    {
      echo json_encode([
        'error' => 1,
        'messages' => [
          'error_name' => $this->error_name,
          'error_address' => $this->error_address,
          'error_display_name' => $this->error_display_name,
          'error_img' => $this->error_img,
          'error_font' => $this->error_font,
          'error_color' => $this->error_color,
          'error_type' => $this->error_type,
          'error_key' => $this->error_key
        ]
      ]);
    }
  }
}

$init = new SubmitPreset;

ob_end_flush();