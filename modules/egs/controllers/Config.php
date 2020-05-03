<?php

namespace app\modules\egs\controllers;

use Yii;

class Config
{
    static public $PASS_DEFENSE_SCORE = 50;
    static public $REQUIRE_CREDITED = 12;

    static public $DEFENSE_STATUS_DEFAULT = 1;
    static public $DEFENSE_STATUS_FAIL = 2;
    static public $DEFENSE_STATUS_PASS = 3;
    static public $DEFENSE_STATUS_PASS_CON = 4;
    static public $DOC_STATUS_SUBMMITTED = 5;
    static public $DOC_STATUS_NOT_SUBMITTED = 6;
    static public $DOC_STATUS_NO_NEED = 7;
    static public $FEE_STATUS_PAID = 8;
    static public $FEE_STATUS_NOT_PAY = 9;
    static public $DONT_NEED_TO_PAY = 10;
    static public $SUBJECT_STATUS_PASS_ALL = 11;
    static public $SUBJECT_STATUS_PASS_SOME = 12;
    static public $SUBJECT_STATUS_FAIL_ALL = 13;
    static public $SUBJECT_STATUS_ALREADY_PASSED = 14;

    static public $STATUS_NOT_IN_TIME = 15;
    static public $STATUS_ALREADY_PASSED = 16;
    static public $STATUS_NOT_CONDITION = 17;
    static public $STATUS_DONT_NEED_TODO = 18;

    static public $DEFAULT_LANGUAGE = 'th';
    static public $SYSTEM_ID = -99;

    static public $STEP_TYPE_INSERT = 1;
    static public $STEP_TYPE_PROCESS = 2;

    static public $PRINTING_TYPE_REVIEW = 1;

    static public $ACTION_REQUEST_TYPE = 1;
    static public $ACTION_DEFENSE_TYPE = 2;
    static public $ACTION_INIT_TYPE = 3;
    static public $ACTION_EVAL_TYPE = 4;

    static public $DOCUMENT_PETITION_TYPE = 1;
    static public $DOCUMENT_PAPER_TYPE = 2;

    static public $ADVISOR_MAIN_POSITION = 1;
    static public $ADVISOR_CO_POSITION = 2;
    static public $COMMITTEE_MAIN_POSITION = 3;
    static public $COMMITTEE_CO_POSITION = 4;

    static public $SUBMIT_TYPE_BEFORE = 1;
    static public $SUBMIT_TYPE_AFTER = 2;

    static public $PERSON_STUDENT_TYPE = 0;
    static public $PERSON_TEACHER_TYPE = 1;
    static public $PERSON_STAFF_TYPE = 2;

    static public $POSITION_COMMITTEE_TYPE = 1;
    static public $POSITION_ADVISOR_TYPE = 2;

    static public $LEVEL_MASTER = 1;
    static public $LEVEL_DOCTOR = 2;

    static public $REQUEST_ADVISOR = 1;
    static public $REQUEST_PROPOSAL = 2;
    static public $REQUEST_FINAL_1 = 4;
    static public $REQUEST_FINAL_2 = 5;
    static public $REQUEST_PROGRESS = 8;
    static public $REQUEST_COMPRE_QE = 10;
    static public $EVALUATION = 14;

    static public $DEFENSE_WIRTE = 11;
    static public $DEFENSE_ORAL = 12;

    static public function get_language()
    {
        $lang = Config::$DEFAULT_LANGUAGE;
        if (Yii::$app->session->has('lang'))
            $lang = Yii::$app->session->get('lang');
        return $lang;
    }

    static public function get_user_id()
    {
        return (int)Yii::$app->session->get('id');
    }

    static public function get_one_user($id)
    {
        return Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user WHERE id=' . $id)->queryOne();
    }

    static public function get_current_user()
    {
        $current_user = Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user WHERE id=' . Config::get_user_id())->queryOne();
        return $current_user;
    }

    static public function get_all_user()
    {
        return Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user')->queryAll();
    }

    static public function get_all_teacher()
    {
        return Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user WHERE user_type_id = ' . Config::$PERSON_TEACHER_TYPE)->queryAll();
    }

    static public function get_all_student()
    {
        return Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user WHERE user_type_id = ' . Config::$PERSON_STUDENT_TYPE)->queryAll();
    }
}
