<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsCalendar;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class LanguageController extends Controller
{
    public function actionGet()
    {
        return Json::encode(Config::get_language());
    }
}