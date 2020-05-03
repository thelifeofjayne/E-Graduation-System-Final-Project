<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsSemester;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;

class ActionController extends Controller
{

    public function actionRequest()
    {
        $action = EgsAction::find()->where(['action_type_id' => Config::$ACTION_REQUEST_TYPE])->all();
        return Json::encode(Format::action($action));
    }

    public function actionDefense()
    {
        $action = EgsAction::find()->where(['action_type_id' => Config::$ACTION_DEFENSE_TYPE])->all();
        return Json::encode(Format::action($action));
    }

}