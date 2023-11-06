<?php

session_start();
$err = [];

require_once 'functions/userLogic.php';


//POST送信されたニックネーム、ログインID、パスワード、確認用パスワードをそれぞれ変数へ格納
$user_name = filter_input(INPUT_POST,'user_name');
$login_id = filter_input(INPUT_POST,'login_id');
$pass = filter_input(INPUT_POST,'pass');
$pass_conf= filter_input(INPUT_POST,'pass_conf');

//バリデーションの実装

if(!$user_name){
    $err['name']="ニックネームを入力してください。";
}
if(!preg_match('/\A[a-z\d]{6,20}\z/i',$login_id)){
    $err['login']="ログインIDは英数字6文字以上20文字以内で入力してください。";
}
if(!preg_match('/\A[a-z\d]{6,20}\z/i',$pass)){
    $err['pass']="パスワードは英数字6文字以上20文字以内で入力してください。";
}
if($pass !== $pass_conf){
    $err['pass_conf']="確認用パスワードが異なっています。";
}



//エラーがなければ、新規登録を行う。
if(count($err)===0){
    $createdUser = UserLogic::createUser($_POST);
    if(!$createdUser){
        echo '登録に失敗しました。';
    }
    if(is_string($createdUser)){
        $err['userErr']=$createdUser;
    }
}

//エラーメッセージが１つ以上あればSESSION変数に格納し、新規登録画面へリダイレクト
if(count($err)>0){
    $_SESSION['signup_err'] = $err;
    header('Location:views/signup.form.php');
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録完了</title>
</head>
<body>
    <?php if(count($err)===0){ ?>
        <h3>新規登録が完了しました。下記よりログインしてください。</h3>
        <a href="views/login.form.php">ログインはこちら</a>

    <?php } ?>
</body>
</html>