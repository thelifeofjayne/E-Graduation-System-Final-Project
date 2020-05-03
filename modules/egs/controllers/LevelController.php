<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsSemester;
use Yii;

use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class LevelController extends Controller
{
    public function actionAll()
    {
        $level = EgsLevel::find()->all();
        return Json::encode(Format::level($level));
    }
}