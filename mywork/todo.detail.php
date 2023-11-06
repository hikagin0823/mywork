<?php

session_start();
require_once 'db_connect.php';

$pdo = db_connect();
$sql = 'SELECT id,title,detail,user_id,DATE(created_at) as created_at,DATE(limit_at) as limit_at FROM todolist WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id',$_GET['id'],PDO::PARAM_INT);
$stmt->execute();
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

$leftDays = null;
$todo['title'] = htmlspecialchars($todo['title'],ENT_QUOTES,'UTF-8');
$todo['detail'] = nl2br(htmlspecialchars($todo['detail'],ENT_QUOTES,'UTF-8'));

if(!is_null($todo['limit_at'])){
    $date = new Datetime('now');
    $today = strtotime($date->format('Y-m-d'));
    $limit = strtotime($todo['limit_at']);
    if($limit > $today){
        $leftDays = ($limit - $today) / 86400 ;
    }elseif($limit === $today){
        $leftDays = '今日が期限です！';
    }else{
        $leftDays = '期限を過ぎています！';
    }
}


require_once 'views/todo.detail.tpl.php';

?>
