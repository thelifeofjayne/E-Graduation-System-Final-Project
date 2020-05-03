<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsActionBypass;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsSemester;
use Yii;

use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class ActionBypassController extends Controller
{
    public function actionAll()
    {
        $action_bypasses = EgsActionBypass::find()->all();
        return Json::encode(Format::action_bypass($action_bypasses));
    }

    public function actionDelete($semester, $action, $student)
    {
        $action_bypass = EgsActionBypass::findOne([
            'student_id' => $student,
            'action_id' => $action,
            'semester_id' => $semester
        ]);
        if (!empty($action_bypass)) {
            $action_bypass->delete();
        }
        return Json::encode(null);
    }

    public function actionInsert($semester, $action, $student)
    {
        $action_bypass = EgsActionBypass::findOne([
            'student_id' => $student,
            'action_id' => $action,
            'semester_id' => $semester
        ]);
        if (empty($action_bypass)) {
            $action_bypass = new EgsActionBypass();
            $action_bypass->student_id = $student;
            $action_bypass->action_id = $action;
            $action_bypass->semester_id = $semester;
            return Json::encode($action_bypass->save() ? Format::action_bypass($action_bypass) : $action_bypass->errors);
        }
        return Json::encode(null);
    }
}