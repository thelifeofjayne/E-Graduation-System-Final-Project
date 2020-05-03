<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsRoom;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsTodo;
use app\modules\egs\models\EgsTodoFor;
use Yii;

use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class TodoController extends Controller
{
    public function actionAll()
    {
        $student = Config::get_current_user();
        $student_id = (int)$student['user_type_id'] === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : $student['id'];
        $reg_program_id = $student['program_id'];
        $plan = EgsPlanBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $program = EgsProgramBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $program_id = empty($program) ? null : $program->program_id;
        $plan_id = empty($plan) ? null : $plan->plan_id;
        $todo = EgsTodo::find()
            ->joinWith(['egsTodoFors tf'])
            ->where([
                'tf.program_id' => $program_id,
                'tf.plan_id' => $plan_id,
            ])->all();
        return Json::encode(Format::todo($todo));
    }
}