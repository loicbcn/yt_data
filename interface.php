<?php
$PDO = new PDO('mysql:host=localhost;dbname=recup_vids','root','');
$PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
$PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);

$disqvids = $PDO->query('select * from myvids order by friendly limit 10;');
$data_path = 'D:/donnees/perso/videos/';
?>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interface</title>
    <link rel="stylesheet" type="text/css" href="css/knacss.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<h1>Hello</h1>

<div class="autogrid">
    <div><h2>Yt<h2></div>
    <div><h2>Disque<h2></div>
</div>
<div class="autogrid">
    <div>Hop</div>
    <div>
       <?php foreach ($disqvids as $dv): ?>
       <div>
       <video controls width="250">
            <source src="http://repvideo/<?=$dv->filename .'.'. $dv->extension; ?>" type="video/<?= $dv->extension; ?>">
        </video><br>
        <?= $dv->friendly; ?>
        </div>
        <hr>
        <?php endforeach; ?>
    
    
    </div>
</div>

</body>
</html>
