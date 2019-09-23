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

include('../lib/functions.php');

$data_path = 'D:/donnees/perso/videos/';
$jsons = glob($data_path . '*.json');
$vid_exts = ['.mp4','.avi','.mov'];

$counter = 0;
$counter_exists = 0;
$notfound = [];
$hashtml = [];
foreach ($jsons as $j) {
 
  $jsonfile = file_get_contents($j);
  $data = json_decode($jsonfile, true);
  
  // la video existe t'elle ?
  $vtitle = $data[0]['snippet']['title'];
  $vtitle = str_replace('\'','_',$vtitle);
  $vtitle_to_test = iconv("UTF-8", "Windows-1252",$vtitle);
  // Si le dernier caractère du titre de la vidéo est un point, on le remplace par un _
  if (substr($vtitle,-1) == '.') {
    $vtitle_to_test =  iconv("UTF-8", "Windows-1252", substr($vtitle, 0,-1) . '_');
  }
    
  $exists = false;
 
  foreach ($vid_exts as $ve) {
    echo $data_path . $vtitle_to_test. $ve.'<br>';
    if (file_exists($data_path . $vtitle_to_test. $ve)) {
      $exists = true;
      $counter_exists ++;
      break;
    }
  }
  
  if (file_exists($data_path . $vtitle_to_test. '.html')) {
    $hashtml[] = $vtitle;
  }
  
  var_dump($vtitle, $vtitle_to_test, $exists);
  echo '<hr>';
  
  if (!$exists) {
    $notfound[] = $vtitle;
  }
  
  $counter++;

}

var_dump($notfound);
echo $counter_exists . '/'. $counter . ' = ' .( $counter_exists / $counter );
echo '<hr>HTML<br>';
var_dump($hashtml);