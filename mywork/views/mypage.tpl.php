<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include('header.inc.php'); ?>
        <script src="jquery.min.js"></script>
        <title>マイページ</title>
    </head>
<body>
    <div class="container">
        <div class="row">

            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                        <a class="navbar-brand" href="#"><?=$_SESSION['user']['user_name']?> さんのマイページ</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link " aria-current="page" href="#todo">TodoList</a>
                            </li>
            
                            <li class="nav-item">
                                <a class="nav-link" href="../mywork/board/thread_list.php">Board</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../mywork/project/project_list.php">Project Mangiment</a>
                            </li>
                            <li class="nav-item">
                                <form action="../mywork/logout.php" method='POST'>
                                    <input type="submit" class="nav-link active" value='Logout' id='logout'>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="alert alert-info mt-1" role="alert">
                <h4>ようこそ！<?=$_SESSION['user']['user_name']?> さん！<h4>
                <p>今日は<?=$date?>です。</p>
            </div>
        
            <div class="card">
                <div class="card-body">
                    <h2 id="todo"><span class="card-title">やることリスト</span></h2>
                    <div class="table-responsive mb-5">    
                        <table class='table table-hover align-middle' >
                            <thead class='table-dark'>        
                                <?php if(!empty($todo)){ ?>
                                <tr>
                                    <th scope="col">作成日時</th>
                                    <th scope="col">TODO</th>
                                    <th scope="col">実行期限</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody class='table-group-divider'>
                                <?php foreach($todo as $row){ ?>
                                        <tr>
                                            <td><?=$row['created_at'] ?></td>
                                            <td><a href="../mywork/todo.detail.php?id=<?=$row['id']?>"><?=htmlspecialchars($row['title'])?></a></td>
                                            <td><?=$row['limit_at'] ?></td>
                                            <td>
                                                <a href="../mywork/edit.php?id=<?=$row['id']?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="../mywork/delete.todo.php?id=<?=$row['id']?>" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                </tbody>
                                <?php } ?>
                                
                            <?php }else{ ?>
                                <h3>現在、TODOは登録されておりません。</h3>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h2 id='addtodo' class='card-title'>Todo追加</h2>
                    <form action="../mywork/new.todo.php" method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">タイトル</label>       
                            <input type="text" name='title' value='' class='form-control'>
                            <div class='form-text'>TODOのタイトルを記入してください。</div>
                            <?php if(isset($_SESSION['titleLoss'])){ ?>
                                <p class="err"><?=$_SESSION['titleLoss']?></p>
                            <?php } ?>
                        </div> 
                        <div class="mb-3">
                            <label for="detail" class="form-label">TODO詳細</label>
                            <textarea name="detail" class='form-control' cols="20" rows="10"></textarea> 
                            <div class='form-text'>TODOの詳細を記入してください。</div>
                            <?php if(isset($_SESSION['detailLoss'])){ ?>
                                <p class="err"><?=$_SESSION['detailLoss']?></p>
                            <?php } ?>
                        </div>
                        <div class="mb-3">
                            <label for="limit_at" class="form-label">実行期限</label>
                            <input type="date" name='limit_at' value="<?=date('Y-m-d') ?>" class="form-control dateform">
                        </div>
                        <input type="hidden" name='user_id' value=<?=$_SESSION['user']['id']?>>
                        <button type="submit" class="btn btn-primary">Todo追加</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php include('footer.inc.php'); ?>
    <script>
        $('#logout').click(function(){
            if(confirm('ログアウトしますか？')){
                window.location.href ="../mywork/logout.php";
            }else{
                return false;
            }
        })
    </script>
</body>

</html>