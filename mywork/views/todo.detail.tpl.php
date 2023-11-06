<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include('header.inc.php'); ?>
        <link rel="stylesheet" href="views/style.css" type="text/css">
        <title>Todo詳細</title>
    </head>
<body>
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    
                    <h2 id="todo"><span class="border-bottom">詳細</span></h2>
                    <h3>⭐️タイトル<br /><?=$todo['title']?></h3>
                    <div class="border border-dark mb-2">
                        <p><?=$todo['detail']?></p>
                    </div>
                    <p>投稿日時:<?=$todo['created_at']?></p>
                    <p>実行期限:<?=$todo['limit_at']?></p>
                    <div>
                        <?php if(is_string($leftDays)){ ?>
                            <p class='limit'><?=$leftDays?></p>
                        <?php }elseif(is_int($leftDays)){ ?>
                            <p>期限まで残り<?=$leftDays?>日です。</p>
                        <?php }else{ ?>
                            <p></p>
                        <?php } ?>      
                    </div>
                    <p><a href="../mywork/mypage.php" class='btn btn-primary'>一覧へ戻る</a><a href="../mywork/edit.php?id=<?=$todo['id']?>" class='btn btn-secondary ms-2'>編集する</a><a href="../mywork/delete.todo.php?id=<?=$todo['id']?>" class='btn btn-danger ms-2'>削除する</a></p>
                </div>
            </div>

            
           
        </div>
    </div>

    <?php include('footer.inc.php'); ?>
</body>

</html>