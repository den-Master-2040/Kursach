<?php

namespace app\controllers;

use app\models\User;


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
        $query = "INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `data_birthday`, `token`, `admin`) 
            VALUES ('131', 'Руслан2', 'Долуханов4', 'ryslanchick@mak.ri4', '1', '1', '2023-12-08', '1', '0');";
            
        $result = mysqli_query($connect, $query);
        
        if($result){
            \yii:: $app->response->statusCode = 204;
            return null;
        }            
        else{
            \yii::$app->response->statusCode = 422;
            return null;
        }
            
    }
}
