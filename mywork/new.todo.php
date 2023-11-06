<?php

session_start();
require_once 'db_connect.php';

$title=filter_input(INPUT_POST,'title');
$detail=filter_input(INPUT_POST,'detail');


if(empty($title)){
    $_SESSION['titleLoss']='タイトルを入力してください。';
}
if(empty($detail)){
    $_SESSION['detailLoss']='詳細を入力してください。';
}
$user_id = filter_input(INPUT_POST,'user_id');
$limit = filter_input(INPUT_POST,'limit_at');


if(!empty($title) && !empty($detail)){
    try{
        $pdo = db_connect();
        $sql = 'INSERT INTO todolist (title,detail,user_id,limit_at) VALUES(?,?,?,?)';
        $newArr = array($title,$detail,$user_id,$limit);
        $stmt=$pdo->prepare($sql);
        $stmt->execute($newArr);
    }catch(PDOException $e){
        echo $e->getmessage();
    }
    unset($_SESSION['detailLoss']);
    unset($_SESSION['titleLoss']);
    header('Location:mypage.php');
}else{
    
    header('Location:mypage.php');

}




