<?php

function db_connect(){
    $dsn = 'mysql:host=localhost;dbname=mywork;charaset=utf8';
    $user = 'root';
    $pass = 'root';

    try{
        $pdo = new PDO($dsn,$user,$pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);


    }catch(PDOException $e){
        echo '接続失敗しました' . $e->getmessage();
        exit;
    }

    return $pdo;

}