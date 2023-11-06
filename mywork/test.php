<?php

function db_connect(){
    $dsn = 'mysql:host=mysql1.php.starfree.ne.jp;dbname=hikagin_mywork;charaset=utf8';
    $user = 'hikagin_host';
    $pass = 'hikagin0823';

    try{
        $pdo = new PDO($dsn,$user,$pass,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        echo '接続成功です。';

    }catch(PDOException $e){
        echo '接続失敗しました' . $e->getmessage();
        exit;
    }

    return $pdo;

}
$msg1='aho';
$msg = $msg1 === 'aho' ? 'hello':NULL;

$msg2 = $msg === NULL ? 'null' : 'no';

echo $msg;
echo $msg2;