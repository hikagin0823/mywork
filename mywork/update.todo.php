<?php

session_start();
require_once 'db_connect.php';

if($get_id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)){
    
    try{
        $title = filter_input(INPUT_POST,'title');
        $detail = filter_input(INPUT_POST,'detail');
        $limit_at = filter_input(INPUT_POST,'limit_at');
        
        echo $limit_at;
        $pdo = db_connect();
        $sql = 'UPDATE todolist SET 
                title = :title,
                detail = :detail,
                limit_at=:limit_at 
                WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title',$title,PDO::PARAM_STR);
        $stmt->bindValue(':detail',$detail,PDO::PARAM_STR);
        $stmt->bindValue(':limit_at',$limit_at,PDO::PARAM_STR);   
        $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
        $stmt->execute();

        $pdo = null;
    }catch(PDOException $e){
        echo $e->getmessage();
    }
    
    header('Location:todo.detail.php?id='.$get_id);
}else{
    echo 'お探しのページが見つかりません。';
}

