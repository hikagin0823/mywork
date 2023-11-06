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
    //スレッドを一覧で表示するためのスレッドタイトル等を取得。
    //そのスレッドに紐づく投稿数も取得する。=>count(*)だとNULL(MAXがNULLになった時)もカウントしてしまうのでコメントが０の時もレコードが１数えられてしまう。
    //最新投稿の時間も取得する。
    $pdo = db_connect();
    $sql = 'SELECT thread.id,thread.thread_title,thread.thread_created_at,count(thread_id), MAX(board.created_at) FROM thread LEFT JOIN board ON thread.id = board.thread_id GROUP BY thread.id';
    $stmt = $pdo->query($sql);
    $lists = $stmt->fetchall(PDO::FETCH_ASSOC);

 
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
    <title>スレッド一覧</title>
    <script src="jquery.min.js"></script>
</head>
<body>
    <div class="container mt-3">
        <div class="row">
            <p><a href="../mypage.php">マイページへ戻る</a></p>
            <table class='table table-striped'>
                <tr>
                    <th>スレッド作成日時</th>
                    <th>スレッド最終更新日</th>
                    <th>スレッドタイトル</th>
                    <th>コメント件数</th>
                </tr>
        
                <?php foreach($lists as $list){ ?>
                <tr>
                    <td><?=h($list['thread_created_at'])?></td>
                    <td><?=$list['MAX(board.created_at)']?></td>
                    <td><a href='board.php?id=<?=$list['id']?>'><?=h($list['thread_title'])?></td>
                    <td> 
                        <?=$list['count(thread_id)']?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <form action="thread_list.php" method='POST'>
                            <h2>スレッド新規作成</h2>
                        
                            <h4><label for="thread_title" class='form-label'>スレッドタイトル</label><h4>
                            <input type="text" name='thread_title' class='form-control' value=''>
                            <h4><label for="thread.detail" class="form-label">スレッド詳細</label></h4>
                            <textarea name='thread.detail' class='form-control' rows='10' cols='10'></textarea>
                            <p class="form-text">新規に作成するスレッドの詳細について特記事項あれば入力ください。</p>
                            <button type='submit' class='btn btn-primary mt-3'>新規作成</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>