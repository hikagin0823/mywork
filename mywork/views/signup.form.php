<?php

session_start();
if(isset($_SESSION['signup_err'])){
    $err = $_SESSION['signup_err'];
}

$_SESSION=[];
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include('header.inc.php'); ?>
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>ユーザー登録</title>

    </head>
<body class='signup_body'>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h1>新規ユーザー登録</h1>
                        <form action="../user.add.php" method="POST">
                            <div class="mb-3 form-group">
                                <label for="user_name" class="form-label">ニックネーム</label>       
                                <input type="text" name='user_name' value='' class='form-control'>
                                <div class='form-text'>あなたのニックネームを入力してください。</div>
                                <?php if(isset($err['name'])){ ?>
                                    <p class='err'><?=$err['name'] ?></p>
                                <?php } ?>
                            </div> 
                            <div class="mb-3 form-group">
                                <label for="login_id" class="form-label">ログインID</label>
                                <input type="text" name='login_id' value='' class='form-control'>
                                <div class='form-text'>ログインIDを半角英数字6文字以上20字以内で入力してください。</div>
                                <?php if(isset($err['login'])){ ?>
                                    <p class='err'><?=$err['login'] ?></p>
                                <?php } ?>
                                <?php if(isset($err['userErr'])){ ?>
                                    <p class='err'><?=$err['userErr'] ?></p>
                                <?php } ?>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="pass" class="form-label">パスワード</label>
                                <input type="password" name='pass' class="form-control">
                                <div class='form-text'>パスワードを半角英数字6文字以上20字以内で入力してください。</div>
                                <?php if(isset($err['pass'])){ ?>
                                    <p class='err'><?=$err['pass'] ?></p>
                                <?php } ?>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="pass_conf" class="form-label">パスワード（確認用）</label>
                                <input type="password" name='pass_conf' class="form-control">
                                <div class='form-text'>先ほど入力したパスワードをもう一度入力してください。</div>
                                <?php if(isset($err['pass_conf'])){ ?>
                                    <p class='err'><?=$err['pass_conf'] ?></p>
                                <?php } ?>
                            </div>
                            <button type="submit" class="btn btn-primary">新規登録</button>
                        </form>
                        <div class="ps">
                            <p>登録済みの方は<a href="login.form.php">ログイン</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.inc.php'); ?>
</body>

</html>
