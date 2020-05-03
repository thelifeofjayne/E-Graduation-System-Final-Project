<?php

namespace app\modules\egs\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class ConfigController extends Controller
{

    public function actionIndex()
    {
        $data['SYSTEM_ID'] = Config::$SYSTEM_ID;
        $data['DEFAULT_LANGUAGE'] = Config::$DEFAULT_LANGUAGE;

        $data['COMMITTEE_MAIN_POSITION'] = Config::$COMMITTEE_MAIN_POSITION;
        $data['COMMITTEE_CO_POSITION'] = Config::$COMMITTEE_CO_POSITION;

        $data['PASS_DEFENSE_SCORE'] = Config::$PASS_DEFENSE_SCORE;
        $data['DEFENSE_STATUS_DEFAULT'] = Config::$DEFENSE_STATUS_DEFAULT;
        $data['DEFENSE_STATUS_FAIL'] = Config::$DEFENSE_STATUS_FAIL;
        $data['DEFENSE_STATUS_PASS'] = Config::$DEFENSE_STATUS_PASS;
        $data['DEFENSE_STATUS_PASS_CON'] = Config::$DEFENSE_STATUS_PASS_CON;
        $data['DOC_STATUS_SUBMMITTED'] = Config::$DOC_STATUS_SUBMMITTED;
        $data['DOC_STATUS_NOT_SUBMITTED'] = Config::$DOC_STATUS_NOT_SUBMITTED;
        $data['DOC_STATUS_NO_NEED'] = Config::$DOC_STATUS_NO_NEED;
        $data['FEE_STATUS_PAID'] = Config::$FEE_STATUS_PAID;
        $data['FEE_STATUS_NOT_PAY'] = Config::$FEE_STATUS_NOT_PAY;
        $data['DONT_NEED_TO_PAY'] = Config::$DONT_NEED_TO_PAY;
        $data['SUBJECT_STATUS_PASS_ALL'] = Config::$SUBJECT_STATUS_PASS_ALL;
        $data['SUBJECT_STATUS_PASS_SOME'] = Config::$SUBJECT_STATUS_PASS_SOME;
        $data['SUBJECT_STATUS_FAIL_ALL'] = Config::$SUBJECT_STATUS_FAIL_ALL;
        $data['SUBJECT_STATUS_ALREADY_PASSED'] = Config::$SUBJECT_STATUS_ALREADY_PASSED;

        $data['PERSON_STUDENT_TYPE'] = Config::$PERSON_STUDENT_TYPE;
        $data['PERSON_TEACHER_TYPE'] = Config::$PERSON_TEACHER_TYPE;
        $data['PERSON_STAFF_TYPE'] = Config::$PERSON_STAFF_TYPE;

        $data['ACTION_REQUEST_TYPE'] = Config::$ACTION_REQUEST_TYPE;
        $data['ACTION_DEFENSE_TYPE'] = Config::$ACTION_DEFENSE_TYPE;
        $data['ACTION_INIT_TYPE'] = Config::$ACTION_INIT_TYPE;

        return Json::encode($data);
    }
}
