<?php
error_reporting(E_ALL & ~E_NOTICE);

header("Content-type: text/html;charset=utf-8");

ob_start();

require_once(".".DIRECTORY_SEPARATOR."scripts".DIRECTORY_SEPARATOR."CoreController.php");

use App\CoreController;

class PageController
{
  private $output;

  /*
    >> Output's all the banner images from the `/img/background/` folder
  */

  public function banner_list_img()
  {
    $this->scandir = array_diff(scandir("..".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR."background"), ['..', '.']);

    $this->output = "";

    foreach($this->scandir as $key => $item)
    {
      $this->id += 1;

      $this->output .= "<li class=\"list-item\">";

      $this->output .= "<span class=\"text\">".$item."</span>";

      $this->output .= "<img src=\"../img/background/".$item."\" class=\"banner-img\" id=\"banner-img-".$key."\" draggable=\"false\" />";

      $this->output .= "</li>";
    }

    return $this->output;
  }

  /*
    >> Outputs all the fonts from the `/fonts/` folder
  */

  public function banner_font_list()
  {
    $this->scandir = array_diff(scandir("..".DIRECTORY_SEPARATOR."fonts"), ['..', '.']);

    $this->output = "";

    foreach($this->scandir as $key => $item)
    {
      $this->id += 1;

      $this->item_value = explode('-regular.ttf', $item);

      $this->output .= "<option value=\"".$this->item_value[0]."\">".$item."</option>";
    }

    return $this->output;
  }

  /*
    >> Outputs all the presets
  */

  public function preset_list()
  {
    global $config;

    $core = new CoreController;

    switch($config['preset-driver'])
    {
      case 'database':
        $this->presets = $core->db()->table('presets')->get();

        $this->presets = json_encode($this->presets);
        break;
      case 'file':
        $this->get_presets = json_decode(file_get_contents('..'.DIRECTORY_SEPARATOR.'presets.json'), true);

        $this->keys = array_keys($this->get_presets);

        for($i = 0; $i < count($this->keys); $i++)
        {
          $this->presets[$this->keys[$i]]['name'] = $this->keys[$i];
          $this->presets[$this->keys[$i]]['address'] = $this->get_presets[$this->keys[$i]]['address'];
          $this->presets[$this->keys[$i]]['display_name'] = $this->get_presets[$this->keys[$i]]['display_name'];
          $this->presets[$this->keys[$i]]['font'] = $this->get_presets[$this->keys[$i]]['font'];
          $this->presets[$this->keys[$i]]['color'] = $this->get_presets[$this->keys[$i]]['color'];
          $this->presets[$this->keys[$i]]['background'] = $this->get_presets[$this->keys[$i]]['background'];
          $this->presets[$this->keys[$i]]['type'] = $this->get_presets[$this->keys[$i]]['type'];
        }

        $this->presets = array_values($this->presets);

        $this->presets = json_encode($this->presets);
        break;
      default:
        $this->get_presets = json_decode(file_get_contents('../presets.json'), true);

        $this->keys = array_keys($this->get_presets);

        for($i = 0; $i < count($this->keys); $i++)
        {
          $this->presets[$this->keys[$i]]['name'] = $this->keys[$i];
          $this->presets[$this->keys[$i]]['address'] = $this->get_presets[$this->keys[$i]]['address'];
          $this->presets[$this->keys[$i]]['display_name'] = $this->get_presets[$this->keys[$i]]['display_name'];
          $this->presets[$this->keys[$i]]['font'] = $this->get_presets[$this->keys[$i]]['font'];
          $this->presets[$this->keys[$i]]['color'] = $this->get_presets[$this->keys[$i]]['color'];
          $this->presets[$this->keys[$i]]['background'] = $this->get_presets[$this->keys[$i]]['background'];
          $this->presets[$this->keys[$i]]['type'] = $this->get_presets[$this->keys[$i]]['type'];
        }

        $this->presets = array_values($this->presets);

        $this->presets = $this->presets;
        break;
    }

    $this->output = "";

    foreach(json_decode($this->presets) as $item)
    {
      $this->banner_url = $config['app_url']."banner?preset=".$item->name;
      $this->banner_name = !empty($item->display_name) ? $item->display_name : mb_strtoupper($item->name);

      $this->output .= "<li>";

      $this->output .= "<h3 class=\"title\">".$this->banner_name."</h3>";

      $this->output .= "<img src=\"".$this->banner_url."\" draggable=\"false\"/>";

      $this->output .= "<div class=\"input-group\">";

      $this->output .= "<input type=\"text\" class=\"form-control\" id=\"preset-output-".$item->name."\" value=\"$this->banner_url\" readonly>";

      $this->output .= "<div class=\"input-group-append\">";

      $this->output .= "<button class=\"btn btn-info\" id=\"copy-btn\" data-clipboard-target=\"#preset-output-".$item->name."\" data-clipboard-action=\"copy\"><i class=\"fas fa-clipboard-check mr-2\"></i> <span>Copy to Clipboard</span></button>";

      $this->output .= "<a href=\"".$this->banner_url."\" class=\"btn btn-primary\" download><i class=\"fas fa-download mr-2\"></i> <span>Download</span></a>";

      $this->output .= "</div>";

      $this->output .= "</div>";

      $this->output .= "</li>";
    }

    return $this->output;
  } 

  public function __construct()
  {
    $core = new CoreController;

    $this->build_schema = mb_strtolower($_GET['build_schema']);

    if(!empty($this->build_schema) && $this->build_schema == 1)
    {
      $core->db_build_schema();

      header("Location: ".$config['app_url'].'/panel/index.php');
    }
  }
}

$page = new PageController;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Banner Generator</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/img/favicon.jpg">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="./assets/plugins/font-awesome/css/all.css">
    <link rel="stylesheet" href="./assets/plugins/sweet-alert2/sweetalert2.css">
    <link rel="stylesheet" href="./assets/css/core.css">
    <!-- Stylesheets -->
    <!-- Javascripts -->
    <script src="./assets/plugins/jquery/jquery-3.3.1.min.js"></script>
    <script src="./assets/plugins/bootstrap/dist/js/popper.min.js"></script>
    <script src="./assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="./assets/plugins/sweet-alert2/sweetalert2.all.min.js"></script>
    <script src="./assets/plugins/clipboard/clipboard.min.js"></script>
    <script src="./assets/js/core.js"></script>
    <!-- Javascripts -->
  </head>
  <body>
    <!-- BODY -->
    <div class="page-container">
      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">
          <img src="./assets/img/logo.png" class="brand-logo" draggable="false">
          <span class="text">Banner Generator</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="btn btn-mc-green d-block d-lg-inline-block active" href="javascript:;">
                <i class="fas fa-home mr-1"></i> <span class="text">Home</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary d-block d-lg-inline-block" href="javascript:;" id="btn-gif-generator">
                <i class="fas fa-book-open mr-1"></i> <span class="text">Gif Generator</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-info d-block d-lg-inline-block" href="javascript:;" id="btn-toggle-presets-modal">
                <i class="fas fa-list-ul mr-1"></i> <span class="text">Presets</span>
              </a>
            </li>
          </ul>
        </div>
      </nav>
      <!-- Navigation -->
      <div class="body-wrapper">
        <!-- Main Body -->
        <main>
          <div class="container-fluid">
            <div class="row">
              <!-- Server Address -->
              <div class="col-md-12">
                <div class="d-block text-center mt-4 pl-3 pr-3">
                  <div class="d-inline-block" style="width: 80%">
                    <h2 class="out-title mb-3">Server Address</h2>
                    <div class="form-group">
                      <input type="text" name="server-address" class="form-control form-control-lg" placeholder="mc.hypixel.net">
                    </div>
                  </div>
                </div>
              </div>
              <!-- Server Address -->
              <!-- Banner Properties -->
              <div class="col-md-12">
                <!-- Banner List Container -->
                <div class="banner-list-container">
                  <h2 class="title">Select Banner Background</h2>
                  <ul class="box-list">
                    <?=$page->banner_list_img()?>
                  </ul>
                  <input type="hidden" name="banner-img-output">
                </div>
                <!-- Banner List Container -->
                <div class="row">
                  <div class="col-md-4">
                    <h2 class="out-title mb-3">Select Text Font</h2>
                    <div class="form-group">
                      <select class="custom-select" name="banner-font-list" id="banner-font-list">
                        <?=$page->banner_font_list()?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h2 class="out-title mb-3">Select Text Color</h2>
                    <div class="form-group">
                      <input type="text" class="form-control" name="banner-text-color" id="banner-text-color" value="#ffffff" maxlength="7">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h2 class="out-title mb-3">Select Banner Type</h2>
                    <div class="form-group">
                      <select name="banner-type-select" id="banner-type-select" class="custom-select">
                        <?php foreach($config['type_list'] as $item) { ?>
                        <option value="<?=$item?>">Type #<?=$item?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Banner Properties -->
              <!-- Preview Banner -->
              <div class="col-md-12">
                <div class="d-block text-center mr-3 mb-3">
                  <button type="button" class="btn btn-success btn-lg" id="banner-preview-btn">
                    <i class="fas fa-eye mr-2"></i> Preview Banner
                  </button>
                  <button type="button" class="btn btn-mc-green ml-3 btn-lg" id="banner-create-preset">
                    <i class="fas fa-plus-circle mr-2"></i> Create Preset
                  </button>
                </div>
                <div class="banner-preview-box"></div>
              </div>
              <!-- Preview Banner -->
            </div>
          </div>
        </main>
        <!-- Main Body -->
        <!-- Footer -->
        <div class="a-footer">
          <div class="row">
            <div class="col-md-6 box-left">
              <p class="text">&copy;2020 <u>Banner Generator</u>.</p>
            </div>
            <div class="col-md-6 box-right">
              <p class="text">
                Made with <i class="fas fa-heart text-danger"></i> by <a href="https://annotech.net" target="_blank">MrAnonymusz</a>.
              </p>
            </div>
          </div>
        </div>
        <!-- Footer -->
      </div>
    </div>
    <!-- BODY -->
    <!-- Modals -->
    <!-- Preset Modal -->
    <div class="modal fade" id="presets-modal" tabindex="-1" aria-labelledby="presets-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="presets-modalLabel">Preset List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                <i class="far fa-times-circle fa-xs text-danger"></i>
              </span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="preset-list">
              <?=$page->preset_list()?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Preset Modal -->
    <!-- Create Preset Modal -->
    <div class="modal fade" id="create-preset-modal" tabindex="-1" role="dialog" aria-labelledby="create-preset-modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="create-preset-modalLabel">Create Preset</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                <i class="far fa-times-circle fa-xs text-danger"></i>
              </span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" name="preset-name" class="form-control" placeholder="Preset Name" autocomplete="off">
            </div>
            <div class="form-group mb-0">
              <input type="text" name="preset-display-name" class="form-control" placeholder="Preset Display Name (Optional)" autocomplete="off">
            </div>
            <hr>
            <div class="form-group mb-0">
              <?php if(empty($_GET['app_key']) || $_GET['app_key'] != $config['app_key']) { ?>
              <input type="text" name="app-key" class="form-control" placeholder="App Key">
              <?php } else { ?>
              <input type="text" name="app-key" class="form-control" placeholder="App Key" value="<?=$_GET['app_key']?>" readonly>
              <?php } ?>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="btn-submit-preset">
              <i class="fas fa-plus-circle mr-1"></i> <span>Save</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Create Preset Modal -->
    <!-- Modals -->
    <!-- JS -->
    <script>
    $(function() {
      <?php foreach(array_diff(scandir("../img/background"), ['..', '.']) as $key => $item) { ?>
      $('#banner-img-<?=$key?>').click(function() {
        $(this).addClass('active');

        var active_img = $('[name="banner-img-output"]').val();

        $('#banner-img-' + active_img).removeClass('active');

        $('[name="banner-img-output"]').val(<?=$key?>);
      });
      <?php } ?>
    });
    </script>
    <script>
    $(function() {
      // SweetToast
      const SweetToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      // Clipboard
      var banner_btn = new ClipboardJS('#banner-url-btn');
      var clipboard = new ClipboardJS('#copy-btn');

      banner_btn.on('success', function() {
        SweetToast.fire({
          icon: 'success',
          title: 'Banner URL successfully copied!'
        });
      });

      clipboard.on('success', function(e) {
        var target = $(e.trigger).attr('data-clipboard-target');

        $(target).addClass('is-valid');

        $(target).tooltip({
          html: true,
          placement: 'bottom',
          trigger: 'manual',
          title: 'URL Copied!',
          template: '<div class="tooltip bs-tooltip-success" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });

        $(target).tooltip('show');

        setTimeout(function() {
          if($(target).hasClass('is-valid'))
          {
            $(target).removeClass('is-valid');
          }

          $(target).tooltip('hide');
        }, 1000);
      });

      // Gif Generator
      $('#btn-gif-generator').click(function() {
        SweetToast.fire({
          icon: 'info',
          title: ' This feature is coming soon!'
        });
      });

      // Presets Modal
      $('#btn-toggle-presets-modal').click(function() {
        $('#presets-modal').modal({
          show: true,
          keyboard: false,
          backdrop: 'static'
        });
      });

      // Preview Banner
      $('#banner-preview-btn').click(function() {
        var server_address = $('[name="server-address"]').val() != "" ? $('[name="server-address"]').val() : 'mc.hypixel.net';
        var timestamp = '&version=' + Date.now();

        var banner_img = $('[name="banner-img-output"]').val() != "" ? $('[name="banner-img-output"]').val() - 1 : 1;
        var banner_font = $('#banner-font-list').val();
        var banner_color = $('#banner-text-color').val() != "" ? $('#banner-text-color').val().replace('#', '') : 'ffffff';
        var banner_type = $('#banner-type-select').val();

        var url = '<?=$config['app_url']?>banner.php?address=' + server_address + '&background=' + banner_img + '&font=' + banner_font + '&color=' + banner_color + '&type=' + banner_type + timestamp;

        // IMG
        var template = '<div class="d-block text-center mt-4">';

        template += '<img src="' + url + '" class="d-inline-block" draggable="false" />';

        template += '</div>';

        // Buttons
        template += '<div class="d-block text-center mt-3 mb-3">';

        template += '<div class="btn-group btn-group-lg" role="group">';

        template += '<button type="button" class="btn btn-info" id="banner-url-btn" data-clipboard-text="' + url + '">';
        template += '<i class="fas fa-clipboard-check mr-2"></i> <span>Copy to Clipboard</span>';
        template += '</button>';

        template += '<a href="' + url + '" class="btn btn-success" download>';
        template += '<i class="fas fa-download mr-2"></i> <span>Download</span>';
        template += '</a>';         

        template += '</div>';

        template += '</div>';

        $('.banner-preview-box').html(template);
      });

      // Create Preset
      $('#banner-create-preset').click(function() {
        $('#create-preset-modal').modal({
          show: true,
          keyboard: false,
          backdrop: 'static'
        });
      });

      // Submit Preset
      $('#btn-submit-preset').click(function() {
        var preset_name = $('[name="preset-name"]').val();
        var app_key = $('[name="app-key"]').val();

        var server_address = $('[name="server-address"]').val() != "" ? $('[name="server-address"]').val() : 'mc.hypixel.net';

        var preset_display_name = $('[name="preset_display_name"]').val();
        var banner_img = $('[name="banner-img-output"]').val() != "" ? $('[name="banner-img-output"]').val() - 1 : 1;
        var banner_font = $('#banner-font-list').val();
        var banner_color = $('#banner-text-color').val() != "" ? $('#banner-text-color').val().replace('#', '') : 'ffffff';
        var banner_type = $('#banner-type-select').val();

        $.ajax({
          url: "<?=$config['app_url']?>/panel/scripts/submit-preset.php",
          method: "POST",
          data: {
            preset_name: preset_name,
            server_address: server_address,
            preset_display_name: preset_display_name,
            banner_img: banner_img,
            banner_font: banner_font,
            banner_color: banner_color,
            banner_type: banner_type,
            app_key: app_key
          },
          dataType: "JSON",
          success: function(data) {
            if(data.error == 1)
            {
              // Error Messages
            }
            else if(data.error == 0)
            {
              $('#create-preset-modal').modal('hide');

              SweetToast.fire({
                icon: 'success',
                title: 'Preset Successfully Created!',
                onClose: () => {
                  window.location.href = "";
                }
              });
            }
          }
        });
      });
    });
    </script>
    <!-- JS -->
  </body>
</html>
<?php
ob_end_flush();
?>