<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include('header.inc.php'); ?>
        <link rel="stylesheet" href="views/style.css" type="text/css">
        <title>Todo編集</title>
    </head>
<body>
    <div class="container">
        <div class="row">
            <p><a href="../mywork/mypage.php">マイページへ戻る</a></p>
            <div class="card">
                <div class="card-body">
                    <h2 id='addtodo'>Todo編集</h2>
                    <form action="../mywork/update.todo.php?id=<?=$todo['id']?>" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">タイトル</label>       
                            <input type="text" name='title' value='<?=$todo['title']?>' class='form-control'>
                            <div class='form-text'>TODOのタイトルを記入してください。</div>
                        </div> 
                        <div class="mb-3">
                            <label for="detail" class="form-label">ToDO詳細</label>
                            <textarea name="detail" class='form-control' cols="20" rows="10"><?=h($todo['detail'])?></textarea> 
                            <div class='form-text'>TODOの詳細を記入してください。</div>
                        </div>
                        <div class="mb-3">
                            <label for="limit_at" class="form-label">実行期限</label>
                            <input type="date" name='limit_at' value="<?=date('Y-m-d') ?>" class="form-control dateform">
                        </div>
                        <button type="submit" class="btn btn-primary">編集完了</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php include('footer.inc.php'); ?>
</body>

</html>