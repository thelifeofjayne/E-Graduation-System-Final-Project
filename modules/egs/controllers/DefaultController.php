<?php

namespace app\modules\egs\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['router' => null]);
    }

    public function actionDefense()
    {
        return $this->render('index', ['router' => 'defense']);
    }

    public function actionRequest()
    {
        return $this->render('index', ['router' => 'request']);
    }

}
