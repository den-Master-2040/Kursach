<?php

namespace app\controllers;

use app\models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\components\JwtUtil;

class Obj_user
{
    //public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $password;
    public $data_birthday;
    //public $token;
    //public $admin;

    public function setAllField($row)
    {
        //$this->id = $row['id'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->email = $row['email'];
        $this->phone = $row['phone'];
        $this->password = $row['password'];
        $this->data_birthday = $row['data_birthday'];
        //$this->token = $row['token'];
        //$this->admin = $row['admin'];
    }
}
class UserController extends \yii\web\Controller
{
   
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRegister($first_name, $last_name, $email, $phone, $password, $data_birthday ,$admin)
    {
        
        //Регистрация пользователя
        

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
        
        //Регистрация пользователя
        

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

        //\yii::$app->user->login($email);

        $jwtUtil = new jwtUtil();
        
        $token = $jwtUtil->generateToken($row['id']);
        
        \yii::$app->response->statusCode = 201;
            $post_data = array(                
                
                'message' => "Успешный вход в аккаунт",
                'token'=> $token);
            $post_data = json_encode($post_data, JSON_FORCE_OBJECT);

        return $post_data;
    }
    public function actionGetone($id, $token)
    {
        
        
        

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");


        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        if(!$result){         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "No user...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $row=mysqli_fetch_array($result);


        if(!$row['admin']) 
        {         //если 0, значит нет прав
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 403,
                'message' => "Отсутствуют права администратора ");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }  

        
        //create query 
        $query = "SELECT * FROM user WHERE id= '{$id}';";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        $row=mysqli_fetch_array($result); 

        if($row== null) {         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Error...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $obj_goro = new Obj_user();  

        $obj_goro->setAllField($row);

        
        
        
        if(!$result){         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Error...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(  
            'message' => "Гороскоп Овен успешно добавлен в систему.”",               
            'data'=>$obj_goro
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }

    public function actionGetall($token)
    {
        
        
        

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");


        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        if(!$result){         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "No user...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $row=mysqli_fetch_array($result);


        if(!$row['admin']) 
        {         //если 0, значит нет прав
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 403,
                'message' => "Отсутствуют права администратора ");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }  

        
        //create query 
        $query = "SELECT * FROM user;";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        if($row== null) {         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Error...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        $myArray = array();
        do
        {
            $row=mysqli_fetch_array($result);

            if($row== null) break;

            $obj_goro = new Obj_user();  

            $obj_goro->setAllField($row);

            array_push($myArray, $obj_goro);
        } while($row != null);
        
        
        if(Empty($myArray)){         //если мы сделали 0 проходов, значит пусто в таблице или нам ничего не пришло.
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Нет пользователей");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(                
            'Token' => $myArray
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
        
            
    }

    public function actionUpdate($id, $first_name, $last_name, $email, $phone, $password, $data_birthday, $token_user ,$admin, $token)
    {
        
        
        

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");


        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        if(!$result){         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "No user...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $row=mysqli_fetch_array($result);


        if(!$row['admin']) 
        {         //если 0, значит нет прав
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 403,
                'message' => "Отсутствуют права администратора ");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }  

        
        //create query 
        $query = "UPDATE `user` SET first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', phone = '{$phone}', password = '{$password}', data_birthday = '{$data_birthday}', token = '{$token_user}',admin = '{$admin}' WHERE id = $id LIMIT 1;";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        
        if(!$result){         //если мы сделали 0 проходов, значит пусто в таблице или нам ничего не пришло.
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Нет пользователей");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(                
            'code' => 200,
                'message' => "Данные успешно изменены");
           
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
        
            
    }
    public function actionDelete($id, $token)
    {
        
        
        

        //connect to Database
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");


        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        if(!$result){         
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "No user...");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $row=mysqli_fetch_array($result);


        if(!$row['admin']) 
        {         //если 0, значит нет прав
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 403,
                'message' => "Отсутствуют права администратора ");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }  

        
        //create query 
        $query = "DELETE FROM `user` WHERE id = $id LIMIT 1;";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        
        if(!$result){         //если мы сделали 0 проходов, значит пусто в таблице или нам ничего не пришло.
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Нет пользователей");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(                
            'code' => 200,
                'message' => "Пользователь успешно удалён");
           
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
        
            
    }
    
    
}

