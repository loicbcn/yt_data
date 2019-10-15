<?php
date_default_timezone_set ('Europe/Paris');
header('Content-Type: text/html; charset=utf-8');
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

// pour chaque enregistrement de la table "video", mettre à jour le champ "friendly"
$PDO = getpdo();

$vids = $PDO->query("select videoId, title from video");
$upd = 'UPDATE video SET FRIENDLY = :friendly where videoId = :videoId';
$q = $PDO->prepare($upd);


foreach ($vids as $v) {
  $friendly = friendly_url($v->title,'_',TRUE);
  $q->execute([
    ':friendly' => $friendly, 
    ':videoId' => $v->videoId
  ]);
}



/* Query update

update myvids m
    inner join (
        select videoId, substr(friendly,1,40)shorty, count(*)nb, group_concat(friendly) grouped,group_concat(videoId) grouped_id
        from video 
        group by shorty having nb = 1    
    ) v on v.shorty = substr(friendly,1,40)
    set yt_id = v.videoId


*/



function getpdo(){

    $PDO = new PDO('mysql:host=localhost;dbname=recup_vids','root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    return $PDO;

}
