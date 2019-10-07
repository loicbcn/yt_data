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

include('functions.php');

$PDO = getpdo();

$PDO->query("TRUNCATE myvids;");

// Insertion
$insertsql = 'INSERT INTO myvids (filename, friendly, extension, friendly_suffix) values(:filename, :friendly, :extension, :friendly_suffix)';
$q = $PDO->prepare($insertsql);

// teste les fichiers avec le même nom
$testdoublesql = 'SELECT count(*) nb FROM myvids WHERE friendly = :str';
$tq = $PDO->prepare($testdoublesql);

$data_path = 'D:/donnees/perso/videos/';

$vids = glob("$data_path*{.avi,.mp4}",GLOB_BRACE);
$counter = 0;

$allv = [];
$cleans = [];
$doubles = [];
foreach ($vids as $v) {
  $pathinfo = pathinfo($v);
  $friendly_suffix = null;

  $original_name = $pathinfo['filename'];
  $ext = $pathinfo['extension'];
  $clean_name = friendly_url(iconv('Windows-1252', 'UTF-8', $original_name),'_',TRUE);

  if (substr($clean_name,-1) == '_') {
    $clean_name = substr($clean_name, 0, -1);
  }

  $tq->execute([':str' => $clean_name]);
  $numrows = (int) $tq->fetchColumn();

  $newname =  $clean_name;

  if ($numrows > 0 ) {
    $friendly_suffix = $numrows;
    $newname .= '_'. $friendly_suffix;
  }
  
  $q->execute([
    ':filename' => $original_name, 
    ':friendly' => $clean_name, 
    ':extension' => $pathinfo['extension'],
    ':friendly_suffix' => $friendly_suffix
  ]);

  $ren = rename($data_path . $original_name .'.'. $pathinfo['extension'], $data_path . $newname .'.'. $pathinfo['extension']);
  echo 'rename('. $data_path . $original_name .'.'. $pathinfo['extension'] .','. $data_path . $newname .'.'. $pathinfo['extension'].')<br>';
  var_dump($ren);
  
  $allv[] = ['original' => $original_name, 'convert' => $clean_name];
  
}


//var_dump($allv);


function getpdo(){

    $PDO = new PDO('mysql:host=localhost;dbname=recup_vids','root','');
    $PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    return $PDO;

}
