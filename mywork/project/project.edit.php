<?php

session_start();
require_once '../db_connect.php';
require_once '../functions/functions.php';

if($get_id=filter_input(INPUT_GET,'id')){
    
    try{
        $pdo = db_connect();
        $sql = 'SELECT * FROM project WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('id',$get_id,PDO::PARAM_INT);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
    }catch(PDOException $e){
        echo 'error';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../css/bootstrap.css'>
    <title>編集画面</title>
    <script src="../jquery.min.js"></script>
</head>
<body>
    <div class="cotainer">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-body">
                        <h1>Edit Project</h1>
                        <form action="project.update.php" method='POST'>
                            <input type="hidden" name='id' value='<?=$project['id']?>'>
                            <input type="hidden" name='user_id' value='<?=$project['user_id']?>'>
                    
                            
                            <h4><label for="title" class='form-label'>タイトル</label><h4>
                            <input type="text" name='title' class='form-control' value='<?=h($project['title'])?>'>
                            <h4><label for="schedule" class="form-label">スケジュール</label></h4>
                            <textarea name='schedule' class='form-control' rows='10' cols='10'><?=h($project['schedule'])?></textarea>
                            <p class="form-text">プロジェクトの想定スケジュールを入力してください。</p>
                            <h4><label for="task" class="form-label">課題・懸案事項</label></h4>
                            <textarea name='task' class='form-control' rows='10' cols='10'><?=h($project['task'])?></textarea>
                            <p class="form-text">プロジェクト上の課題、懸案事項を入力してください。</p>
                            <h4><label for="note" class="form-label">備考</label></h4>
                            <textarea name='note' class='form-control' rows='10' cols='10'><?=h($project['note'])?></textarea>
                            <p class="form-text">その他留意事項等入力してください。</p>
    
                            <button type='submit' class='btn btn-success'>修正する</button>
                    
                        </form>
                        <p></p>
                        <p><a href='project.php?id=<?=$project['id']?>' class='btn btn-secondary'>戻る</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include('../views/footer.inc.php'); ?>
</body>

</html>