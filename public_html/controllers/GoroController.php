<?php

namespace app\controllers;
use PHPUnit\Framework\Constraint\IsEmpty;

class Obj_Syvenir
{
    public $id;
    public $title;
    public $date;
    public $discryption;
    public $image_path;

    public function setAllField($row)
    {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->date = $row['date'];
        $this->discryption = $row['discryption'];
        $this->image_path = $row['image'];
    }

}

class GoroController extends \yii\web\Controller
{
    public $modelClass = 'app\models\User';
    public function actionIndex()
    {
        return $this->render('index');
    }

    
    public function actionIdgoro($id_goro)
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
        $query = "SELECT * FROM goro WHERE id = '{$id_goro}'";
        
        //send query and get result
        $result = mysqli_query($connect, $query);      

        $row=mysqli_fetch_array($result);
        
        if($row== null){         //если мы сделали 0 проходов, значит пусто в таблице или нам ничего не пришло.
            \yii::$app->response->statusCode = 404;
            $post_data = array(                
                'code' => 404,
                'message' => "Goro not found");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }

        $obj_goro = new Obj_Syvenir();  

        $obj_goro->setAllField($row);

        \yii:: $app->response->statusCode = 200;        
        $post_data = array(                          
            'name' => $obj_goro->title,
            'description'=> $obj_goro->discryption,
            'image'=> $obj_goro->image_path,
            
           );
        $post_data = json_encode(array($post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }
    public function actionAllgoro()
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
        $query = "SELECT * FROM goro";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        $myArray = array();     

        do
        {
            $row=mysqli_fetch_array($result);

            if($row== null) break;

            $obj_goro = new Obj_Syvenir();  

            $obj_goro->setAllField($row);

            array_push($myArray, $obj_goro);
        } while($row != null);
        
        
        if(Empty($myArray)){         //если мы сделали 0 проходов, значит пусто в таблице или нам ничего не пришло.
            \yii::$app->response->statusCode = 401;
            $post_data = array(                
                'code' => 401,
                'message' => "Invalid email or password");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }
        
        \yii:: $app->response->statusCode = 201;        
        $post_data = array(  
            'message' => "Successful login",               
            'Token' => $myArray
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }

    public function actionInsert($title, $date, $discryption, $image, $token)
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
        $query = "INSERT INTO `goro`  (`title`, `date`, `discryption`, `image`) VALUES ('{$title}', '{$date}', '{$discryption}', '{$image}');";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        
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
            
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }
    public function actionDeletesystem($id_goro, $token)
    {
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
        $query = "DELETE FROM `goro` WHERE id = '{$id_goro}' LIMIT 1";

        $result = mysqli_query($connect, $query);
      
        
        //check result
        if($result){
            \yii:: $app->response->statusCode = 201;
            
            $post_data = array(                
                'code' => 201,
                'message' => "Гороскоп успешно удалён из системы");
            $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

            return $post_data;
        }            
        else{
            \yii::$app->response->statusCode = 404;
            $post_data = array(                
                'code' => 422,
                'message' => "Syv not found in korzina");
        }
        $post_data = json_encode(array('error' => $post_data), JSON_UNESCAPED_UNICODE);

        return $post_data;
    }

    
    public function actionUpdate($id, $title, $date, $discryption, $image, $token)
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

        //create query ('{$title}', '{$date}', '{$discryption}', '{$image}');";
        $query = "UPDATE `goro` SET title = $title, date = '{$date}', discryption = $discryption, image = $image WHERE id = $id;";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        
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
            
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }
}
