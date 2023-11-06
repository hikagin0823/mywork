<?php

session_start();

if(empty($_SESSION['user']['id'])){
    exit('不正なリクエストです。');
}

require_once '../db_connect.php';
require_once '../functions/functions.php';

//GETでスレッドIDが送信されない時や整数以外が送信された時にリダイレクト
if(!$get_id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT)){
    header('Location:thread_list.php');
}
$err = [];

//コメント追加処理
if($post_content=filter_input(INPUT_POST,'content')){
    try{
        $pdo=db_connect();
        $sql = 'INSERT INTO board (user_id,content,thread_id) VALUES(:user_id,:content,:thread_id)';
        $user_id = filter_input(INPUT_POST,'user_id');
        $content = filter_input(INPUT_POST,'content');
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
        $stmt->bindValue(':content',$content,PDO::PARAM_STR);
        $stmt->bindValue(':thread_id',$get_id,PDO::PARAM_INT);
        $stmt->execute();

    }catch(PDOExeception $e){
        echo $e->getmessage();
    }
}

//GETで取得したIDのスレッドをデータベースから取得
try{
    $pdo=db_connect();
    $sql = 'SELECT board.id,board.content,board.created_at,board.thread_id,user.user_name,thread.thread_title,thread.thread_detail FROM thread LEFT JOIN board ON board.thread_id = thread.id LEFT JOIN user ON board.user_id = user.id WHERE thread.id = :thread_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':thread_id',$get_id,PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchall(PDO::FETCH_ASSOC);

    $sql = 'SELECT thread_title, thread_detail FROM thread WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id',$get_id,PDO::PARAM_INT);
    $stmt->execute();
    $title = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //サイドバーに表示するためのリストを取得
    $stmt = $pdo->query('SELECT * FROM thread');
    $lists = $stmt->fetchall(PDO::FETCH_ASSOC);

    $pdo = null;

}catch(PDOExeception $e){
    echo $e->getmessage();
}

//GETでスレッドIDと一致しないIDが送られてきたらスレッド一覧へリダイレクト
if(empty($posts)){
    header('Location:thread_list.php');
}





?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../../css/bootstrap.css'>
    <script src="jquery.min.js"></script>
    <title>簡易掲示板</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div><a href="thread_list.php" class='list-back'>&lt;&lt;スレッド一覧へ戻る</a></div>
            <div class="thread_box col-md-3">
                <dl>
                    <dt>スレッド一覧</dt>
                    <dd>
                        <?php foreach($lists as $list){ ?>
                            <li><a href="board.php?id=<?=$list['id']?>"><?=$list['thread_title']?></a></li>
                        <?php } ?>
                    </dd>
                
                </dl>

            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="thread_title">
                        <h1><?=$title['thread_title']?></h1>
                        <p class="form-text"><?=$title['thread_detail']?></p>
                    </div>
                    <div class="card-body box">
                        <?php foreach($posts as $post){ ?>
                            <?php if(!is_null($post['content'])){ ?>
                                <input type="hidden" value='<?=$post['id']?>'>
                                <p><span><?=$post['id']?></span>
                                &nbsp;名前:<span class='name'><?=$post['user_name']?></span>
                                &nbsp;<?=$post['created_at']?><button type='button' class='btn btn-link ms-auto replyBtn'>返信</button></p>
                                <p><?=nl2br(h($post['content']))?></p>
                            <?php } ?>
                        <?php } ?>
                        <?php if(!is_null($posts[0]['content'])){ ?>
                            <p class='end'>このスレッドのコメントはここまで</p>
                        <?php }else{ ?>
                            <p class='none'>このスレッドにはまだコメントがありません。</p> 
                        <?php } ?>
                    </div>
                    
                </div>
                <button id="post" class="btn btn-primary mt-1"><span>投稿する</span><span style="display:none">投稿をやめる</span></button>
                
                <div id="form" class="card postForm">
                    <div class="card-body">
                        <div class="form-group">
                            
                            <form action="board.php?id=<?=$get_id?>" method='POST'>
                                <label for="name" class='form-label'>名前</label>
                                <p class="form-control"><?=$_SESSION['user']['user_name']?></p>
                                <input type="hidden" name="user_id" value='<?=$_SESSION['user']['id']?>'>
                                
                                <label for="content" class='form-label'>投稿内容</label>
                                <textarea id='content' name='content' rows="10" cols="30" class=form-control></textarea>
                                
                                <p class='ms-3'><span id="count">0</span>/50</p>
                        </div>
                            <button disabled='disabled' id ='submit' type='submit' class='btn btn-primary mt-2'>投稿</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#form').hide();
        $("#post").click(function(){
            $(".postForm").slideToggle();
            $("#post span").toggle();
        })
        //「返信」がクリックされたら投稿IDを取得し投稿文へ追加
        $('.replyBtn').click(function(){
            $(".postForm").slideToggle();
            $("#post span").toggle();
            let targetReply = $(this).prev().prev().text();
            $('#content').text('<<' + targetReply);
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

        //文字数をカウント
        //0文字あるいは50文字を超えた状態では投稿できなくする。
        $('#content').keyup(function(){
            let count = $(this).val().length;
            $('#count').text(count);

            if(count>0){
                $('#count').removeClass('err');
                $('#submit').removeAttr('disabled');
                if(count>50){
                    $('#count').addClass('err');
                    $('#submit').attr('disabled',true);
                }
            }else{
                $('#submit').attr('disabled',true);
            }
        })


        //スレッド一覧の高さを自動で調節
        let thread_title_height = $('.thread_title').outerHeight();
        let thread_content_height = $('.card-body').outerHeight();
        $('.thread_box').css('height',thread_title_height + thread_content_height);
        console.log(thread_title_height + thread_content_height);



    </script>
</body>
</html>

