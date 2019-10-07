<?php
date_default_timezone_set ('Europe/Paris');
// Pas de limite de mémoire
ini_set('memory_limit','-1');
// 2 heures avant que ne survienne un timeout
set_time_limit (-1);
// Voir toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set("xdebug.var_display_max_children", 1000);
ini_set("xdebug.var_display_max_data", 1000);
ini_set("xdebug.var_display_max_depth", 5);

include('functions.php');

$data_path = 'D:/donnees/perso/videos/';
$data_dest_path = 'D:/donnees/perso/videos_codees/';

$PDO = new PDO('mysql:host=localhost;dbname=recup_vids','root','');
$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
$PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

$disqvids = $PDO->query('select * from myvids order by friendly limit 2;');

foreach ($disqvids as $dv) {
    $video_name = $dv->friendly;
    if ($dv->friendly_suffix) {
        $video_name .= '_'. $dv->friendly_suffix;
    }
    $video_name .= '.'. $dv->extension;

    $video_source_path = $data_path . $video_name;
    $video_dest_path = $data_dest_path . $video_name;
    $commamnd = '"C:/ffmpeg/bin/ffmpeg" -i '. $video_source_path .' -vcodec h264 -acodec aac -strict -2 '. $video_dest_path;
    echo $command ."\n";
    exec($commamnd);
}