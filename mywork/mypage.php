<?php

session_start();
require_once 'db_connect.php';
require_once 'functions/userLogic.php';

//ログインしていない者からのアクセス処理
if(!UserLogic::checkLogin()){
    $_SESSION['loginCheckErr'] = '正しくログインされておりませんでした、再度ログインしてください。';
    header('Location:views/login.form.php');
}

try{
    $pdo = db_connect();
    $sql = 'SELECT id,title,detail,user_id,DATE(created_at) as created_at,DATE(limit_at) as limit_at FROM todolist WHERE user_id=:user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id',$_SESSION['user']['id'],PDO::PARAM_INT);
    $stmt->execute();
    $todo = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    echo $e->getmessage();
}

$date = date('Y年m月d日');

require_once 'views/mypage.tpl.php';

?>
