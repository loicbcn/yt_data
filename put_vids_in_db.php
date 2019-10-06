<?php
date_default_timezone_set ('Europe/Paris');
// Pas de limite de mémoire
ini_set('memory_limit','-1');
// 2 heures avant que ne survienne un timeout
set_time_limit (7200);
// Voir toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set("xdebug.var_display_max_children", 1000);
ini_set("xdebug.var_display_max_data", 1000);
ini_set("xdebug.var_display_max_depth", 5);

include('../lib/functions.php');
$PDO = getpdo();
$insertsql = 'INSERT INTO myvids (filename, friendly, extension) values(:filename, :friendly, :extension)';
$q = $PDO->prepare($insertsql);



$data_path = 'D:/yt_vids/Takeout/YouTube/videos/';
$vids = glob("$data_path*{.avi,.mp4}",GLOB_BRACE);
$counter = 0;

$allv = [];
$cleans = [];
$doubles = [];
foreach ($vids as $v) {
  $pathinfo = pathinfo($v);

  //var_dump($pathinfo);
  $original_name = $pathinfo['filename'];
  $ext = $pathinfo['extension'];
  $clean_name = friendly_url(utf8_encode($original_name));  
  
  $q->execute([':filename' => utf8_encode($original_name), ':friendly' => friendly_url(utf8_encode($original_name)), 'extension' => $pathinfo['extension']]);
  $allv[] = ['original' => $original_name, 'convert' => $clean_name];
  
  if (in_array($clean_name, $cleans)) {
    $doubles[] = $clean_name;
  }
  $cleans[] = $clean_name;
  
}

var_dump($doubles);
var_dump($allv);


function getpdo(){

    $PDO = new PDO('mysql:host=localhost;dbname=recup_vids','root','root');
    $PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    return $PDO;

}
