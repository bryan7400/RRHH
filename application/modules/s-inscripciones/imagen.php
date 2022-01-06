<?php

    $nombre_dominio = escape($_institution['nombre_dominio']);
    //var_dump($_FILES);die;
    $nombre = $_FILES['avatar']['name'];
    $ruta = $_FILES['avatar']['tmp_name'];
    $size = $_FILES['avatar']['size'];
    $ruta = files .'/'. $nombre_dominio .'/profiles/' . '/temporal/' . '/fotos/';
    $imagen = $ruta . $nombre;
    $error = $_FILES['avatar']['error'];
    //$img1path = 'imgs . '/avatar.jpg'' .  $_FILES['avatar']['name'];
    
    if($_FILES['avatar']['type'] == "image/jpg" || $_FILES['avatar']['type'] == "image/png"){
        // Importa la libreria para subir el avatar
        move_uploaded_file($_FILES['avatar']['tmp_name'], $imagen);
        echo $nombre;
    }else{
        
    }
?>