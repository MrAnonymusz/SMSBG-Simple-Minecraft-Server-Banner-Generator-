# SMSBG (Simple Minecraft Server Banner Generator)

*As the name of this project is it's just a simple php script which allows you to create custom banners for your minecarft server. These are live banners so they display live stats of your server. At the moment they display the address of your server, the amount of online players and the version of the server.
But you may configure these options in the `banner.php` file.*

### Some additional information

* **You can now access the banner generator by going to _`yourdomain.com/banner/panel/index.php`_!**
* For prettier urls you can make a request without include the file's extension _(Instead of __banner.php?address=localhost:25565__ you can simply just __banner?address=localhost:25565__)_ but you must have the `.htaccess` file in the projects root folder.
* This project also includes a server query using [mcsrvstat.us](https://mcsrvstat.us/) api.
* If you enable the `enable_key` option in the config file you can restrict the usage of the banner generator but then you must need to pass the following value for the future request's: `&key=your_app_key`. You can find the `app_key` in the `config.php` file. If you want to use it it's recommended to change it frequently but the actual usage of it is to disable the momentary usage of the generator.
* Since adding a preset requires to provide the projects `app_key` every time you want to create a preset you can specify the `app_key` in the url and that way you can create presets easily. _(Example: **yourdomain.com/banner/panel/index.php?app_key=your_app_key**)_
* All the presets are stored in the `presets.json` file _if you set the `preset-driver` in the `config.php` to **file**_ and make please do not edit the `presets.default.json`.
* This project includes 8 default banners (1-8) and 3 default fonts (Roboto, Ubuntu, Minecraftia)
* **_Some images are used from [Reign [512x]](https://www.planetminecraft.com/texture_pack/vividhd-512x/)! by [Tanner77](https://www.planetminecraft.com/member/tanner77/)._**

### Installation

* **Drag all the files included form the downloaded zip in to a folder and after the go to the `config.php` and change the `app_url` from `http://localhost/banner/` to the domain for the banner generator.**
* _Go to `yourdomain.com/banner/panel/index.php?build_schema=1` **(Only if you want your presets to be loaded from a database)**_

### How can you make a request?!

* You can get a custom banner by accessing: `banner?address=your_minecraft_server_ip&background=1&font=roboto&color=27AE60&type=1`
* You can access the server query by: `query?address=your_minecraft_server_ip`
* __Note 1:__ You can now simply request a banner by `banner?address=your_minecraft_server_ip` and the default settings in the `config.php` will be applied.
* __Note 2:__ Now you can also choose from 3 predefined styles for the banner _(type)_, the default type for the banner is 1. Example: `banner?address=your_minecraft_server_ip&type=2`

### How can you add a new banner background?!

**The banner's resolution must be 468x60 and it must a `jpg` file and included in the `img/background` folder with the following name: `banner_(here a custom number).jpg`, example: `banner_11.jpg`. _You can set the background of the banner by changing the `background` variable for example: `banner?address=your_minecraft_server_ip&background=11&font=roboto`_.**

### How can you add a new font for the banner?!

**First you need to add a add your font file to the `fonts` folder and rename it like this: `examplefont-regular.ttf` after this go to the `config.php` and add it to the `fonts` and the name you specify there should be just the font's name without the _-regular.ttf_ and you must make sure it's all lowercase _(The name specified in the config and the actual name of the font file must be all lowercase)_. And now you can change the font by simply changing the `font` variable in the request url for example: `banner?address=your_minecraft_server_ip&background=1&font=your_custom_font`.**

### How can you request a presetted banner?!

* __You can request a custom banner specified in the `presets.json` like this: `banner?preset=hypixel` *(This is the default banner preset in the `presets.json` and you can use it as an example)*__
* You can add a new preset in the `presets.json` or via the **new banner generator panel** by clicking on the **"Create Preset"** button.

#### Default config

```php
<?php

$config = [
  'app_key' => 'K6TdeFXHuqZC7Fam', // Please change this to whatever your want (Do not leave this as default!)
  'enable_key' => false,
  'query_api' => 'https://api.mcsrvstat.us/2/',
  'app_url' => 'http://localhost/banner/',
  'enable_htaccess' => false,
  'fonts' => ['ubuntu', 'roboto', 'minecraftia'],
  'type_list' => [1, 2, 3],
  'defaults' => [
    'font' => 'minecraftia',
    'color' => '#fff',
    'background' => 1,
    'type' => 1
  ],
  'database' => [
    'host' => 'localhost',
    'database' => 'database',
    'username' => 'root',
    'password' => '',
    'prefix'  => 'banner_',
  ],
  'preset-driver' => 'file' // Driver Types: file, database
];
```

### Request Examples

* __Banner:__ `http://localhost/banner/banner?address=mc.hypixel.net` _(Note: you may specify other parameters like `name`, `font`, `type`, `background` or `color`)_
* __Query:__ `http://localhost/banner/query?address=mc.hypixel.net`

##### Example Banner

**Default Banner**
<br/><br/><img src="https://i.ibb.co/s3p9s3y/banner-example.jpg" alt="Default Banner" />

**Default Presetted Banner**
<br/><br/><img src="https://i.ibb.co/TwrDwVY/banner-preset.jpg" alt="Default Presetted Banner"/>

### Nginx Config (Optional)

```nginx
# nginx configuration

autoindex off;

location / {
  if (!-e $request_filename){
    rewrite ^/([^\.]+)$ /$1.php break;
  }
}
```
