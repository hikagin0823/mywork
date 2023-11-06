<?php

session_start();

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';
require_once '../functions/functions.php';

$title = filter_input(INPUT_POST,'title');
$schedule = filter_input(INPUT_POST,'schedule');
$task = filter_input(INPUT_POST,'task');
$note = filter_input(INPUT_POST,'note');
$user_id = filter_input(INPUT_POST,'user_id');
$add_arr = [$title,$schedule,$task,$note];
//プロジェクトをデータベースへ登録
if(!empty($title)){
    $add_arr[]=$user_id;

    try{
        $pdo=db_connect();
        $sql='INSERT INTO project (title,schedule,task,note,user_id) 
                VALUES(?,?,?,?,?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($add_arr);
        
    }catch(PDOException $e){
        echo 'error';
    }

    header('Location:project_list.php');
}else{
    header('Location:project_list.php');
}

?>
