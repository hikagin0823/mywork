<?php

session_start();
if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';
require_once '../functions/functions.php';

$comment = filter_input(INPUT_POST,'comment');
$user_id = filter_input(INPUT_POST,'user_id');
$project_id = filter_input(INPUT_POST,'project_id');
$add_arr = [$comment,$user_id,$project_id];

//コメントをデータベースへ登録
if(!empty($add_arr)){
    try{
        $pdo=db_connect();
        $sql='INSERT INTO comment (comment,user_id,project_id) 
                VALUES(:comment,:user_id,:project_id)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($add_arr);
        $pdo = null;
        header('Location:project.php?id='.$project_id);
    }catch(PDOException $e){
        echo 'error';
    }

}else{
    echo 'error';
}

?>
