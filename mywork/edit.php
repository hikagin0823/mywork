<?php

session_start();
require_once 'db_connect.php';
require_once 'functions/functions.php';

if($get_id=filter_input(INPUT_GET,'id')){
    
    $pdo = db_connect();
    $sql = 'SELECT id,title,detail,user_id,DATE(created_at) as created_at,DATE(limit_at) as limit_at FROM todolist WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
    $stmt->execute();
    $todo = $stmt->fetch(PDO::FETCH_ASSOC);
    
}

require_once 'views/edit.tpl.php';