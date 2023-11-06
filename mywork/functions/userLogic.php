<?php

require_once '../mywork/db_connect.php';


class UserLogic{

    /** ユーザー登録
     * @param  array $userData
     * @return bool|string  $result|$userErr
     *  */ 
     public static function createUser($userData){
        $result = false;

        //ログインIDの重複がないか確認
        $sql = 'SELECT * FROM user WHERE login_id = :login_id';
        try{
            $pdo = db_connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':login_id',$userData['login_id'],PDO::PARAM_STR);
            $stmt->execute();
            $user=$stmt->fetchall(PDO::FETCH_ASSOC);
        }catch(\Execpion $e){
            return $result;
        }

        if(!empty($user)){
            $userErr = 'ログインIDが重複しています。';
            return $userErr;
        }



        $sql = 'INSERT INTO user (user_name,login_id,password) VALUES(?,?,?)';
        
        $user_arr=[];
        $user_arr[]=$userData['user_name'];
        $user_arr[]=$userData['login_id'];
        $user_arr[]=password_hash($userData['pass'],PASSWORD_DEFAULT);

        try{
            $pdo = db_connect();
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($user_arr);
            return $result;
        }catch(\Exception $e){
            return $result;

        }

     }

/** ログイン
     * @param string $login_id
     * @param string $pass
     * @return bool $result
     *  */ 
    public static function login($login_id,$pass){
        $result = false;
        $user = self::getLoginID($login_id);
        if(empty($user)){
            $_SESSION['login_err']="ログインIDが一致しません。";
            return $result;
        }

        if(password_verify($pass,$user['password'])){
            //ログイン成功
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            $result=true;
            return $result;
        }
        $_SESSION['login_err']='パスワードが一致しません。';
        return $result;
        
    }

     /** ログインIDの検索
     * @param string $login_id
     * @return array|bool $user|false
     *  */ 
    public static function getLoginID($login_id){

        //ログインIDが登録されているか確認
        $sql = 'SELECT * FROM user WHERE login_id = :login_id';
        try{
            $pdo = db_connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':login_id',$login_id,PDO::PARAM_STR);
            $stmt->execute();
            $user=$stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        }catch(\Execpion $e){
            return false;
        }


     }


      /** ログイン中か確認
     * @param void
     * @return bool $result
     *  */ 

     public static function CheckLogin(){
        $result = false;
        if(isset($_SESSION['user']) && $_SESSION['user']['id']>0){
            $result = true;
            return $result;
        }else{
            return $result;
        }
     }

      /** ログアウト
     * @param void
     * @return void
     *  */ 

     public static function logout(){
        $_SESSION=[];
        session_destroy();
     }

     






}



?>