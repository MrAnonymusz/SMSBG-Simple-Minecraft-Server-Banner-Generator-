<?php
ob_start();

error_reporting(E_ALL & ~E_NOTICE);

require_once(__DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'config.php');
require_once(__DIR__.DIRECTORY_SEPARATOR.'panel'.DIRECTORY_SEPARATOR.'scripts'.DIRECTORY_SEPARATOR.'Database.php');

use Intervention\Image\ImageManagerStatic as Image;
use App\Helpers\Database;

class bannerGenerator extends Database
{
  private $_preset;
  private $_address;
  private $_name;
  private $_bakground;
  private $_font;
  private $_color;
  private $_type;
  private $_key;
  private $_file_name;

  /*
    >> This function generates the banner image
  */

  private function generateBanner($_address, $_name, $_background, $_font, $_color, $_type, $_key)
  {
    global $config;

    $this->_address = $_address;
    $this->_name = $_name;
    $this->_background = $_background;
    $this->_font = $_font;
    $this->_color = $_color;
    $this->_type = $_type;
    $this->_key = $_key;

    if($config['enable_htaccess'] == true)
    {
      $this->_file_name = 'query';
    }
    else
    {
      $this->_file_name = 'query.php';
    }

    if($config['enable_key'] == true)
    {
      $this->query = json_decode(file_get_contents($config['app_url'].$this->_file_name.'?address='.$this->_address.'&type=ping&key='.$config['app_key']));
    }
    else
    {
      $this->query = json_decode(file_get_contents($config['app_url'].$this->_file_name.'?address='.$this->_address.'&type=ping'));
    }

    $this->banner_background = __DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'background'.DIRECTORY_SEPARATOR.'banner_'.$this->_background.'.jpg';
    $this->banner_font = __DIR__.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.''.$this->_font.'-regular.ttf';

    $img = Image::make($this->banner_background);

    $this->final_name = $this->_name == "" ? strtoupper($this->_address) : $this->_name.' | '.strtoupper($this->_address);

    if($this->query->online == "true")
    {
      if(!empty($this->query->icon))
      {
        $this->server_icon = Image::make($this->query->icon)->resize(54, 54)->encode('png');
      }
      else
      {
        $this->server_icon = Image::make(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'icon'.DIRECTORY_SEPARATOR.'server-icon.png')->resize(54, 54)->encode('png');
      }

      switch($this->_type)
      {
        case 1:
          $img->text($this->final_name, 74, 12, function($font) {
            $font->file($this->banner_font);
            $font->size(14);
            $font->color($this->_color);
            $font->align('left');
            $font->valign('top');
          });

          $img->text('Version: '.$this->query->version, 74, 32, function($font) {
            $font->file($this->banner_font);
            $font->size(12);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
          });

          $img->text('Players: '.$this->query->players->online.' / '.$this->query->players->max, 74, 50, function($font) {
            $font->file($this->banner_font);
            $font->size(12);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
          });
          break;
        case 2:
          $img->text($this->final_name, 74, 12, function($font) {
            $font->file($this->banner_font);
            $font->size(14);
            $font->color($this->_color);
            $font->align('left');
            $font->valign('top');
          });

          $img->text('Version: '.$this->query->version, 74, 32, function($font) {
            $font->file($this->banner_font);
            $font->size(12);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
          });

          $img->text('Online: '.$this->query->players->online, 74, 50, function($font) {
            $font->file($this->banner_font);
            $font->size(12);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
          });
          break;
        case 3:
          $img->text($this->final_name, 74, 19, function($font) {
            $font->file($this->banner_font);
            $font->size(14);
            $font->color($this->_color);
            $font->align('left');
            $font->valign('top');
          });

          $img->text('Version: '.$this->query->version, 74, 39, function($font) {
            $font->file($this->banner_font);
            $font->size(12);
            $font->color('#fff');
            $font->align('left');
            $font->valign('top');
          });
          break;
      }

      $img->insert($this->server_icon, 'left', 10, 10);

      // Status Icon

      $status_icon = Image::make(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'icon'.DIRECTORY_SEPARATOR.'online-box.png');

      $status_icon->opacity(50);

      $status_icon->text('Online', 33, 14, function($font) {
        $font->file(__DIR__.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'minecraftia-regular.ttf');
        $font->size(24);
        $font->color('#fff');
        $font->align('left');
        $font->valign('top');
      });

      $status_icon->resize(75, 25);

      $img->insert($status_icon->encode('png'), 'top-right', 10, 10);

      // Status Icon
    }
    else
    {
      $this->server_icon = Image::make(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'icon'.DIRECTORY_SEPARATOR.'offline.jpg')->resize(54, 54)->greyscale();

      $this->offline_mark = Image::make(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'icon'.DIRECTORY_SEPARATOR.'times.png')->resize(27, 27);

      $this->server_icon->insert($this->offline_mark, 'center');

      $this->server_icon->encode('png');

      $img->text($this->final_name, 74, 19, function($font) {
        $font->file($this->banner_font);
        $font->size(14);
        $font->color('#fff');
        $font->align('left');
        $font->valign('top');
      });

      $img->text('Server is offline!', 74, 39, function($font) {
        $font->file($this->banner_font);
        $font->size(12);
        $font->color('#fff');
        $font->align('left');
        $font->valign('top');
      });

      $img->insert($this->server_icon, 'left', 10, 10);

      // Status Icon

      $status_icon = Image::make(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'icon'.DIRECTORY_SEPARATOR.'offline-box.png');

      $status_icon->opacity(50);

      $status_icon->text('Offline', 28, 14, function($font) {
        $font->file(__DIR__.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'minecraftia-regular.ttf');
        $font->size(24);
        $font->color('#fff');
        $font->align('left');
        $font->valign('top');
      });

      $status_icon->resize(75, 25);

      $img->insert($status_icon->encode('png'), 'top-right', 10, 10);

      // Status Icon
    }

    $render = (string) $img->encode('jpg');

    return $render;
  }

  /*
    >> This function check all the inputed variables and loads the banner
  */

  public function __construct()
  {
    global $config;

    $this->_preset = $_GET['preset'];
    $this->_address = $_GET['address'];
    $this->_name = $_GET['name'];
    $this->_background = $_GET['background'];
    $this->_font = strtolower($_GET['font']);
    $this->_color = $_GET['color'];
    $this->_type = $_GET['type'];

    $this->allowed_fonts = $config['fonts'];

    if(empty($this->_preset))
    {
      if(empty($this->_address))
      {
        $this->error = 1;
        $this->error_message = "Please specify a server address for the query!";
      }

      if(!empty($this->_background))
      {
        if(!filter_var($this->_background, FILTER_VALIDATE_INT))
        {
          $this->error = 1;
          $this->error_message = "Invalid value given for the background!";
        }
        else
        {
          $this->check_dir = array_diff(scandir(__DIR__.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'background'), ['..', '.']);

          if(!in_array('banner_'.$this->_background.'.jpg', $this->check_dir))
          {
            $this->error = 1;
            $this->error_message = "Invalid value given for the background!";
          }
        }
      }
      else
      {
        $this->_background = $config['defaults']['background'];
      }

      if(!empty($this->_font))
      {
        if(!in_array($this->_font, $this->allowed_fonts))
        {
          $this->error = 1;
          $this->error_message = "Invalid value given for the font!";
        }
      }
      else
      {
        $this->_font = $config['defaults']['font'];
      }

      if(empty($this->_color))
      {
        $this->_color = $config['defaults']['color'];
      }
      else
      {
        $this->_color = '#'.$_GET['color'];
      }

      if(!empty($this->_type))
      {
        if(!filter_var($this->_type, FILTER_VALIDATE_INT))
        {
          $this->error = 1;
          $this->error_message = "Invalid value given for the banner type!";
        }
        else if(!in_array($this->_type, $config['type_list']))
        {
          $this->error = 1;
          $this->error_message = "Invalid value given for the banner type!";
        }
      }
      else
      {
        $this->_type = $config['defaults']['type'];
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
        // Render Banner
        header('Content-Type: image/jpeg');

        echo $this->generateBanner($this->_address, $this->_name, $this->_background, $this->_font, $this->_color, $this->_type, $this->_key);
      }
      else
      {
        echo json_encode([
          'error' => $this->error_message
        ]);
      }
    }
    else
    {
      switch($config['preset-driver'])
      {
        case 'database':
          $db = new Database;

          $this->get_preset = $db->connect()->table('presets')->where('name', $this->_preset);

          if($this->get_preset->count() == 1)
          {
            $this->g_preset = $this->get_preset->first();

            $this->_address = $this->g_preset->address;
            $this->_name = !empty($this->g_preset->display_name) && $this->g_preset->display_name != "null" ? $this->g_preset->display_name : "";
            $this->_background = $this->g_preset->background;
            $this->_font = $this->g_preset->font;
            $this->_color = $this->g_preset->color;
            $this->_type = $this->g_preset->type;

            // Render Banner
            header('Content-Type: image/jpeg');

            echo $this->generateBanner($this->_address, $this->_name, $this->_background, $this->_font, $this->_color, $this->_type, $this->_key);
          }
          else
          {
            echo json_encode([
              'error' => 'Sorry but we couldn\'t find any preset with this name!'
            ]);
          }
          break;
        case 'file':
          $this->get_presets = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'presets.json'), true);

          if(empty($this->get_presets))
          {
            $this->get_presets = [];
          }

          if(array_key_exists($this->_preset, $this->get_presets))
          {
            $this->_address = $this->get_presets[$this->_preset]['address'];
            $this->_name = $this->get_presets[$this->_preset]['display_name'];
            $this->_background = $this->get_presets[$this->_preset]['background'];
            $this->_font = $this->get_presets[$this->_preset]['font'];
            $this->_color = $this->get_presets[$this->_preset]['color'];
            $this->_type = $this->get_presets[$this->_preset]['type'];

            // Render Banner
            header('Content-Type: image/jpeg');

            echo $this->generateBanner($this->_address, $this->_name, $this->_background, $this->_font, $this->_color, $this->_type, $this->_key);
          }
          else
          {
            echo json_encode([
              'error' => 'Sorry but we couldn\'t find any preset with this name!'
            ]);
          }
          break;
        default:
          echo json_encode([
            'error' => 'Invalid Banner Preset Driver!'
          ]);
          break;
      }
    }
  }
}

$banner = new bannerGenerator;

ob_end_flush();