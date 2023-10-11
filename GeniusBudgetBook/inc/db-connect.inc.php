<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=geniusbudgetbook', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // echo 'Connected to database...';
    return $pdo;
}
catch(PDOException $e) {
    try{
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    // echo 'Connected without database...';
    return $pdo;
    }
    catch(PDOException $e) {
        echo 'Problems with database connection...';
        die();
    }
}
    

