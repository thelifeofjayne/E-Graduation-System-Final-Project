<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionStep;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsRoom;
use app\modules\egs\models\EgsSemester;
use Yii;

use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class ActionStepController extends Controller
{
    public function actionInsert($action_id)
    {
        $action_step = EgsActionStep::find()->where([
            'step_type_id' => Config::$STEP_TYPE_INSERT,
            'action_id' => $action_id
        ])->orderBy('action_step_index')->all();
        return Json::encode(Format::action_step($action_step));
    }

    public function actionProcess($action_id)
    {
        $action_step = EgsActionStep::find()->where([
            'step_type_id' => Config::$STEP_TYPE_PROCESS,
            'action_id' => $action_id
        ])->orderBy('action_step_index')->all();
        return Json::encode(Format::action_step($action_step));
    }
}