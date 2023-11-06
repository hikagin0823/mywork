<?php

session_start();
require_once 'functions/userLogic.php';

//ログインしていない者からのアクセス処理
if(!UserLogic::checkLogin()){
    exit('不正なリクエストです。');
}

UserLogic::logout();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト完了</title>
</head>
<body>
    <p>ログアウト完了しました。</p>

    <p>新規登録は<a href="views/signup.form.php">こちら</a></p>
    <p></p>
    <p>ログインは<a href="views/login.form.php">こちら</a></p>
</body>
</html>