<?php

session_start();

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';

$title = filter_input(INPUT_POST,'title');
$schedule = filter_input(INPUT_POST,'schedule');
$task = filter_input(INPUT_POST,'task');
$note = filter_input(INPUT_POST,'note');
$user_id = filter_input(INPUT_POST,'user_id');

if($post_id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT)){
    
    try{

        $pdo = db_connect();
        $sql = 'UPDATE project SET 
                title = :title,
                schedule = :schedule,
                task = :task,
                note = :note,
                user_id = :user_id
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title',$title,PDO::PARAM_STR);
        $stmt->bindValue(':schedule',$schedule,PDO::PARAM_STR);
        $stmt->bindValue(':task',$task,PDO::PARAM_STR);
        $stmt->bindValue(':note',$note,PDO::PARAM_STR);
        $stmt->bindValue(':user_id',$user_id,PDO::PARAM_STR);   
        $stmt->bindValue(':id',$post_id,PDO::PARAM_INT);
        $stmt->execute();

        $pdo = null;
    }catch(PDOException $e){
        echo $e->getmessage();
    }
    header('Location:project.php?id='.$post_id);
}else{
    echo 'お探しのページが見つかりません。';
}

