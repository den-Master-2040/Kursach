<?php

namespace app\controllers;

use yii\filters\auth\HttpBearerAuth;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\User;
class obj_syvenir
{
    public $id;
    public $title;
    public $discryption;
    public $image_path;

    public function setAllField($row)
    {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->discryption = $row['discryption'];
        $this->image_path = $row['image'];
    }

}

class SyvenirController extends \yii\web\Controller
{ 
    public $modelClass = 'app\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' =>['Pay']
        ];
        return $behaviors;
    }
    public function actionIdsyvenir($id_syvenir)
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
        $query = "SELECT * FROM syvenir WHERE id = '{$id_syvenir}'";
        
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

        $obj_syvenir = new obj_syvenir();  

        $obj_syvenir->setAllField($row);

        \yii:: $app->response->statusCode = 200;        
        $post_data = array(                          
            'name' => $obj_syvenir->title,
            'description'=> $obj_syvenir->discryption,
            'image'=> $obj_syvenir->image_path,
            
           );
        $post_data = json_encode(array($post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }
    public function actionAllgoro()
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
        $query = "SELECT * FROM syvenir";
        
        //send query and get result
        $result = mysqli_query($connect, $query);       

        $myArray = array();     

        do
        {
            $row=mysqli_fetch_array($result);

            if($row== null) break;

            $obj_syvenir = new obj_syvenir();  

            $obj_syvenir->setAllField($row);

            array_push($myArray, $obj_syvenir);
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

    public function actionPay($id_syvenir, $token)
    {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        $row=mysqli_fetch_array($result);

        
        
        $id_user = $row['id'];
        //create query
        $query = "INSERT INTO `korzina` (`id_user`, `idsyvenir`, `kolichestvo`) VALUES ('{$id_user}','{$id_syvenir}', '1');";

        $result = mysqli_query($connect, $query);
      
        
        //check result
        if($result){
            \yii:: $app->response->statusCode = 201;
            return null;
        }            
        else{
            \yii::$app->response->statusCode = 404;
            $post_data = array(                
                'code' => 422,
                'message' => "Syv not found");
        }
        $post_data = json_encode(array('error' => $post_data), JSON_FORCE_OBJECT);

        return $post_data;
    }

    public function actionDeletekorzina($id_syvenir, $token)
    {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $database = 'zabrodin_zabrodin_K';
        $connect = mysqli_connect($host, $user, $pass, $database);
        mysqli_set_charset($connect, "utf8");
        
        $query = "SELECT * FROM user WHERE token = '{$token}'";

        $result = mysqli_query($connect, $query);
        
        $row=mysqli_fetch_array($result);

        
        
        $id_user = $row['id'];
        //create query
        $query = "DELETE FROM `korzina` WHERE id_user = '{$id_user}' and idsyvenir = '{$id_syvenir}' LIMIT 1";

        $result = mysqli_query($connect, $query);
      
        
        //check result
        if($result){
            \yii:: $app->response->statusCode = 201;
            return null;
        }            
        else{
            \yii::$app->response->statusCode = 404;
            $post_data = array(                
                'code' => 422,
                'message' => "Syv not found in korzina");
        }
        $post_data = json_encode(array('error' => $post_data), JSON_FORCE_OBJECT);

        return $post_data;
    }

    public function actionInsert($title, $discryption, $image, $price,$token)
    {
        //POST
        //Вставка нового гороскопа
        //{host}/api/register
        //real url:http://k-zabrodin.сделай.site/public_html/web/index.php?r=user/register&id_user=5&first_name=342342&last_name=1&email=3&phone=2&password=4&data_birthday=20120810&token=231&admin=0

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
        $query = "INSERT INTO `syvenir`  (`title`, `discryption`, `image`, `price`) VALUES ('{$title}', '{$discryption}', '{$image}', '{$price}');";
        
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

    public function actionDeletesystem($id, $token)
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
        $query = "DELETE FROM `syvenir` WHERE id = '{$id}' LIMIT 1";

        $result = mysqli_query($connect, $query);
      
        
        //check result
        if($result){
            \yii:: $app->response->statusCode = 201;
            
            $post_data = array(                
                'code' => 201,
                'message' => "Cувенир успешно удалён из системы");
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

    public function actionUpdate($id, $title, $discryption, $image, $price, $token)
    {
        //POST
        //Вставка нового гороскопа
        //{host}/api/register
        //real url:http://k-zabrodin.сделай.site/public_html/web/index.php?r=user/register&id_user=5&first_name=342342&last_name=1&email=3&phone=2&password=4&data_birthday=20120810&token=231&admin=0

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
        $query = "UPDATE `syvenir` SET title = '{$title}', discryption = '{$discryption}', image = $image, price = $price WHERE id = $id;";
        
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
            'message' => "Сувенир успешно обновлён",               
            
           );
        $post_data = json_encode(array('' => $post_data), JSON_UNESCAPED_UNICODE);
        return $post_data;
                   
        
            
    }
   
}
