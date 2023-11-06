<?php

session_start();
require_once 'db_connect.php';

if($get_id=filter_input(INPUT_GET,'id')){
    
    try{
        $pdo = db_connect();
        $sql = 'DELETE FROM todolist WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
        $stmt->execute();
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        $pdo = null;    

    }catch(PDOException $e){
        echo $e->getmessage();
    }
    header('Location:mypage.php');
}

