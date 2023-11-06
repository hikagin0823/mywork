<?php

session_start();
session_regenerate_id(true);

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';
require_once '../functions/functions.php';


if($thread_title=filter_input(INPUT_POST,'thread_title')){
    $thread_detail=filter_input(INPUT_POST,'thread_detail');
    try{
        $pdo = db_connect();
        $sql = 'INSERT INTO thread (thread_title,thread_detail) VALUES(:thread_title,:thread_detail)';
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':thread_title',$thread_title,PDO::PARAM_STR);
        $stmt->bindValue(':thread_detail',$thread_detail,PDO::PARAM_STR);
        $stmt->execute();
        header('Location:thread_list.php');
    }catch(PDOException$e){
        echo $e->getmessage();
    }

}

$thread_id = [];

try{
    //プロジェクト一覧を取得
    $pdo = db_connect();
    $sql = 'SELECT project.*,user.user_name FROM project INNER JOIN user ON project.user_id = user.id';

    $stmt = $pdo->query($sql);
    $projects = $stmt->fetchall(PDO::FETCH_ASSOC);

    
    $pdo = null;
}catch(PDOExeception $e){
    echo $e->getmessage();
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../css/bootstrap.css'>
    <title>プロジェクト一覧</title>
    <script src="../jquery.min.js"></script>
</head>
<body>
    <div class="container mt-3">
        <div class="row">
            <p><a href="../mypage.php">マイページへ戻る</a></p>
            <table class='table table-striped'>
                <tr>
                    <th>作成日時</th>
                    <th>タイトル</th>
                    <th>担当者</th>
                    <th>最終更新日</th>
                </tr>
        
                <?php foreach($projects as $project){ ?>
                <tr>
                    <td><?=h($project['created_at'])?></td>
                    <td><a href='project.php?id=<?=$project['id']?>'><?=h($project['title'])?></td>
                    <td><?=$project['user_name']?></td>
                    <td><?=$project['updated_at']?></td>
                </tr>
                <?php } ?>
            </table>

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <form action="project_add.php" method='POST'>
                            <h2 id='project_add'>プロジェクト追加</h2>
                            <div id='project_add_content'>
                                <h4><label for="title" class='form-label'>タイトル</label><h4>
                                <input type="text" name='title' class='form-control' value=''>
                                <h4><label for="schedule" class="form-label">スケジュール</label></h4>
                                <textarea name='schedule' class='form-control' rows='10' cols='10'></textarea>
                                <p class="form-text">プロジェクトの想定スケジュールを入力してください。</p>
                                <h4><label for="task" class="form-label">課題・懸案事項</label></h4>
                                <textarea name='task' class='form-control' rows='10' cols='10'></textarea>
                                <p class="form-text">プロジェクト上の課題、懸案事項を入力してください。</p>
                                <h4><label for="note" class="form-label">備考</label></h4>
                                <textarea name='note' class='form-control' rows='10' cols='10'></textarea>
                                <p class="form-text">その他留意事項等入力してください。</p>
                                <input type="hidden" name='user_id' value='<?=$_SESSION['user']['id']?>'>
                                <button type='submit' class='btn btn-primary mt-3'>新規作成</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../views/footer.inc.php'); ?>

<script>
$('#project_add_content').hide();
$('#project_add').hover(function(){
    $('#project_add').css('cursor','pointer');
})
$('#project_add').click(function(){
    $('#project_add_content').slideToggle();
})

</script>
</body>
</html>