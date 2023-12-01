<?php

namespace app\controllers;

use app\models\User;
use app;

class UserController extends \yii\web\Controller
{
   
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRegister($id_user, $first_name, $last_name, $email, $phone, $password, $data_birthday,$token ,$admin)
    {
        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8"); // Устанавливаем кодировку UTF-8 для работы с русскими символами
        //end connect

        //create query and send
        $query = "INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `data_birthday`, `token`, `admin`) VALUES ($id_user, $first_name, $last_name, $email, $phone, $password, $data_birthday, $token, $admin);";
        $result = mysqli_query($connect, $query);    
        $row = mysqli_fetch_assoc($result);
        //$db = require __DIR__ . '/db.php';
        //$db->
        //$post = $db->createCommand("SELECT * FROM post WHERE id=1")
          // ->queryOne();
        Yii::$app->response->statusCode = 200;
        return null;
    }
}
