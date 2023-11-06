<?php

session_start();

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';
require_once '../functions/functions.php';


//GETで取得したIDのスレッドをデータベースから取得
if($get_id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)){
    try{
        $pdo=db_connect();
        $sql ='SELECT project.*,user.user_name 
                FROM project   
                INNER JOIN user ON project.user_id = user.id 
                WHERE project.id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
        $stmt->execute();
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        

        
        //コメントを取得
        $sql ='SELECT comment.*,user.user_name,project.title 
                FROM comment
                INNER JOIN user ON comment.user_id = user.id
                INNER JOIN project ON comment.project_id = project.id
                WHERE project.id = :id';
            
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchall(PDO::FETCH_ASSOC);
        
        $pdo = null;
    
    }catch(PDOExeception $e){
        echo $e->getmessage();
    }

}else{
    exit('不正なリクエストです。');
}

//GETでIDと一致しないIDが送られてきたらスレッド一覧へリダイレクト
if(empty($project)){
    header('Location:project_list.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../css/bootstrap.css'>
    <script src="../jquery.min.js"></script>
    <title>プロジェクト</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div><a href="project_list.php" class='list-back'>&lt;&lt;プロジェクト一覧へ戻る</a></div>
            <div class="col-9">
                <div class="card">
                    <div class="thread_title">
                        <h1><?=$project['title']?></h1>
                    </div>
                    <div class="project_info">
                        <ul>
                            <li>担当者：<?=$project['user_name']?></li>
                            <li>プロジェクト開始日：<?=$project['created_at']?></li>
                            <li>最終更新日：<?=$project['updated_at']?></li>
                        </ul>
                    </div>
                    
                    <div class="card-body">
                        <h3>スケジュール</h3>
                        <div class="project_box">
                            <?=nl2br(h($project['schedule'])) ?>
                        </div>
                        <h3 class="mt-1">課題、懸案事項</h3>
                        <div class='project_box'>
                            <?=nl2br(h($project['task'])) ?>
                        </div>
                        <h3 class="mt-1">備考</h3>
                        <div class='project_box'>
                            <?=nl2br(h($project['note'])) ?>
                        </div>
                    </div>
                    
                </div>
                
                <?php if($project['user_id']===$_SESSION['user']['id']){ ?>
                    <p>
                        <button id="post" class="btn btn-primary comment"><span>コメントする</span><span style="display:none">やめる</span></button>&nbsp;
                        <a href="project.edit.php?id=<?=$project['id']?>" class="btn btn-success comment">編集する</a>&nbsp;
                        <a href="delete.project.php?id=<?=$project['id']?>" class="btn btn-danger comment">削除する</a>
                    </p>
                <?php }else{ ?>
                    <button id="post" class="btn btn-primary comment me-3"><span>コメントする</span><span style="display:none">やめる</span></button>
                <?php } ?>
                
                <div id="form" class="card postForm">
                    <div class="card-body">
                        <div class="form-group">
                            
                            <form action="comment_add.php?id=<?=$get_id?>" method='POST'>
                                <label for="name" class='form-label'>名前</label>
                                <p class="form-control"><?=h($_SESSION['user']['user_name'])?></p>
                                <input type="hidden" name="user_id" value='<?=$_SESSION['user']['id']?>'>
                                <input type="hidden" name='project_id' value='<?=$get_id?>'>
                                <label for="comment" class='form-label'>コメント</label>
                                <textarea id='content' name='comment' rows="10" cols="10" class=form-control></textarea>
                                
                                <p class='ms-3'><span id="count">0</span>/100</p>
                            </div>
                            
                            <button disabled='disabled' id ='submit' type='submit' class='btn btn-primary mt-2'>投稿</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="comment_box col-3">
                <h3 class="comment_title">コメント一覧</h3>
                <dl>
                    <?php foreach($comments as $comment){ ?>
                    <dt><?=h($comment['user_name'])?>&nbsp;&nbsp;<?=date('Y年m月d日 h:m',strtotime($comment['comment_at']))?></dt>
                    <dd>
                     <p><?=nl2br(h($comment['comment']))?></p>
                    </dd>
                    <?php } ?>
                </dl>
            </div>
        </div>
    </div>
    <?php include('../views/footer.inc.php'); ?>
    <script>
        $('#form').hide();
        $("#post").click(function(){
            $(".postForm").slideToggle();
            $("#post span").toggle();
        })

        $('.replyBtn').click(function(){
            $(".postForm").slideToggle();
            $("#post span").toggle();
            let targetReply = $(this).prev().prev().text();
            $('#content').text('<<' + targetReply);
            console.log(targetReply); 
        })

        $('dd').hide();
        $('dt').click(function(){
            $(this).next().slideToggle();
        })

        $('dt').hover(function(){
            $(this).css('backgroundColor','#fff');
        },function(){
            $(this).css('backgroundColor','#d8e9f7');
        })
        $('li').hover(function(){
            $(this).css('backgroundColor','#fff');
            $(this).parent().prev().css('backgroundColor','#fff');
        },function(){
            $(this).css('backgroundColor','#d8e9f7');
            $(this).parent().prev().css('backgroundColor','#d8e9f7');
        })

        $('#content').keyup(function(){
            let count = $(this).val().length;
            $('#count').text(count);

            if(count>0){
                $('#count').removeClass('err');
                $('#submit').removeAttr('disabled');
                if(count>100){
                    $('#count').addClass('err');
                    $('#submit').attr('disabled',true);
                }
            }else{
                $('#submit').attr('disabled',true);
            }
        })




    </script>
</body>
</html>

