<?php

namespace app\controllers;

class GoroController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
