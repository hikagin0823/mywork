<?php

session_start();


if(isset($_SESSION)){
    $err = $_SESSION;
}


$_SESSION=[];
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include('header.inc.php'); ?>
        <link rel="stylesheet" href="style.css" type="text/css">
        <title>ログイン</title>

    </head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h1>ログイン</h1>
                        <?php if(isset($err['login_err'])){ ?>
                                    <p class='err'><?=$err['login_err'] ?></p>
                                <?php }elseif(isset($err['loginCheckErr'])){ ?>
                                    <p class="err"><?=$err['loginCheckErr']?></p>
                                <?php } ?>
                                <?php if(isset($err['logoutMsg'])){ ?>
                                    <p><?=$err['logoutMsg']?></p>
                                <?php } ?>
                                
                        <form action="../login.php" method="POST">
                           
                            <div class="mb-3 form-group">
                                <label for="login_id" class="form-label">ログインID</label>
                                <input type="text" name='login_id' value='' class='form-control'>
                                <div class='form-text'>ログインIDを入力してください。</div>
                                <?php if(isset($err['login'])){ ?>
                                    <p class='err'><?=$err['login'] ?></p>
                                <?php } ?>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="pass" class="form-label">パスワード</label>
                                <input type="password" name='pass' class="form-control">
                                <div class='form-text'>パスワードを入力してください。</div>
                                <?php if(isset($err['pass'])){ ?>
                                    <p class='err'><?=$err['pass'] ?></p>
                                <?php } ?>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">ログイン</button>
                        </form>
                        <p class='mt-2'>登録がお済みでない方は<a href="signup.form.php">こちら</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.inc.php'); ?>
</body>

</html>
