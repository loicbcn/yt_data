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
$found = [];
$notfound = [];
$hashtml = [];

foreach ($jsons as $j) {
 
  $jsonfile = file_get_contents($j);
  $data = json_decode($jsonfile, true);
  
  // la video existe t'elle ?
  $vtitle = $data[0]['snippet']['title'];
  $yt_duration = ISO8601ToSeconds($data[0]['contentDetails']['duration']);
  
  // les apostrophes sont remplacées par des underscores par yt
  $vtitle = str_replace('\'','_',$vtitle);
  $vtitle_to_test = iconv("UTF-8", "Windows-1252",$vtitle);
  
  // Si le dernier caractère du titre de la vidéo est un point, on le remplace par un _
  if (substr($vtitle,-1) == '.') {
    $vtitle_to_test =  iconv("UTF-8", "Windows-1252", substr($vtitle, 0,-1) . '_');
  }
    
  $exists = false;
 
  foreach ($vid_exts as $ve) {
    //echo $data_path . $vtitle_to_test. $ve.'<br>';
    $path_to_test = $data_path . $vtitle_to_test. $ve;
    if (file_exists($path_to_test)) {
      // Si le fichier est trouvé
      // on compare la durée donnée par yt avec les durées des fichiers trouvés
      echo "Fichier cherché: $path_to_test - durée yt: ". $yt_duration ."<br>";
      $fichiers_possibles = glob($data_path . $vtitle_to_test.'*'. $ve);
      foreach($fichiers_possibles as $fp) {
        exec('"C:\ffmpeg\bin\ffprobe" -i "'. $fp .'" -v quiet -print_format json -show_format -hide_banner', $output);
        $ffprope = json_decode(implode('',$output), true);
        //var_dump($ffprope);
        echo "-------- Fichier $fp - durée ". $ffprope['format']['duration'] . '( "C:\ffmpeg\bin\ffprobe" -i "'. $fp .'" -v quiet -print_format json -show_format -hide_banner )' ."<br>";
        unset($output);
      }
      echo '<hr>';
      /*
      exec('"C:\ffmpeg\bin\ffprobe" -i "'. $path_to_test .'" -v quiet -print_format json -show_format -hide_banner', $output);
      $ffprope = json_decode(implode('',$output), true);
      var_dump($ffprope); 
      echo $ffprope['format']['duration'];*/
      //$testarr = json_decode($test, true);
      //var_dump($testarr);
      
      $exists = true;
      $counter_exists ++;
      break;
    }
  }
  
  if (file_exists($data_path . $vtitle_to_test. '.html')) {
    $hashtml[] = $vtitle;
  }
  
  //var_dump($vtitle, $vtitle_to_test, $exists);
  //echo '<hr>';
  
  if (!$exists) {
    $notfound[] = $vtitle;
  }
  
  $counter++;

}

die;
echo '<hr>';
var_dump($notfound);
echo $counter_exists . '/'. $counter . ' = ' .( $counter_exists / $counter );
echo '<hr>HTML<br>';
var_dump($hashtml);


/**
 * Convert ISO 8601 values like P2DT15M33S
 * to a total value of seconds.
 *
 * @param string $ISO8601
 */
function ISO8601ToSeconds($ISO8601){
	$interval = new \DateInterval($ISO8601);

	return ($interval->d * 24 * 60 * 60) +
		($interval->h * 60 * 60) +
		($interval->i * 60) +
		$interval->s;
}

