<?php

session_start();
$err = [];

require_once 'functions/userLogic.php';


//POST送信されたニックネーム、ログインID、パスワード、確認用パスワードをそれぞれ変数へ格納
$login_id = filter_input(INPUT_POST,'login_id');
$pass = filter_input(INPUT_POST,'pass');


//バリデーションの実装

if(!$login_id){
    $err['login']="ログインIDを入力してください。";
}
if(!$pass){
    $err['pass']="パスワードを入力してください。";
}


//エラーメッセージが１つ以上あればSESSION変数に格納し、ログイン画面へリダイレクト
if(count($err)>0){
    $_SESSION = $err;
    header('Location:views/login.form.php');
}

//エラーがなければ、ログインを行う。
$result = UserLogic::login($login_id,$pass);

//$resultがfalseで返ってきた場合はログイン画面へリダイレクト
if(!$result){
    header('Location:views/login.form.php');
}else{
    header('Location:mypage.php');
}




?>

