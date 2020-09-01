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