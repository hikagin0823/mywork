<?php

session_start();

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}
require_once '../db_connect.php';

if($get_id=filter_input(INPUT_GET,'id')){
    
    try{
        $pdo = db_connect();
        $sql = 'DELETE FROM project WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
        $stmt->execute();
        
        $pdo = null;    

    }catch(PDOException $e){
        echo $e->getmessage();
    }
    header('Location:project_list.php');
}else{
    exit('不正なリクエストです');
}

