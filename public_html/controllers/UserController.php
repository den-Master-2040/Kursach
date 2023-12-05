<?php

namespace app\controllers;

use app\models\User;


class UserController extends \yii\web\Controller
{
   
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRegister($first_name, $last_name, $email, $phone, $password, $data_birthday ,$admin)
    {
        //POST
        //Регистрация пользователя
        //{host}/api/register
        //real url:http://k-zabrodin.сделай.site/public_html/web/index.php?r=user/register&id_user=5&first_name=342342&last_name=1&email=3&phone=2&password=4&data_birthday=20120810&admin=0

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");

        $token = \yii::$app->security->generateRandomString($length = 16);
        $id = 12;
        //create query
        $query = "INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `data_birthday`, `token`, `admin`) VALUES
                           ('1', '{$first_name}', '{$last_name}', '{$email}', '{$phone}', '{$password}', '{$data_birthday}', '{$token}', '{$admin}');";
        
        //send query and get result
        $result = mysqli_query($connect, $query);
        
        //check result
        if($result){
            \yii:: $app->response->statusCode = 204;
            return null;
        }            
        else{
            \yii::$app->response->statusCode = 422;
            $post_data = array(                
                'code' => 422,
                'message' => "Validation error");
            $post_data = json_encode(array('error' => $post_data), JSON_FORCE_OBJECT);

            return $post_data;
        }
            
    }

    public function actionLogin($email, $password)
    {
        //POST
        //Регистрация пользователя
        //{host}/api/register
        //real url:http://k-zabrodin.сделай.site/public_html/web/index.php?r=user/register&id_user=5&first_name=342342&last_name=1&email=3&phone=2&password=4&data_birthday=20120810&token=231&admin=0

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");

        //create query
        $query = "SELECT * FROM user WHERE email= $email AND password = $password";

        //send query and get result
        $result = mysqli_query($connect, $query);
        
        $row=mysqli_fetch_array($result);


        if($row == null){
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Invalid email or password");
            $post_data = json_encode(array('error' => $post_data), JSON_FORCE_OBJECT);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(  
            'message' => "Successful login",               
            'Token' => $row['id']
           );
        $post_data = json_encode(array('' => $post_data), JSON_FORCE_OBJECT);
        return $post_data;
    }
}

