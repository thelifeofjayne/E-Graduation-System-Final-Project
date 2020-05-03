<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsAdvisor;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;

class AdvisorController extends Controller
{
    public function actionLoad()
    {
        $teachers = Config::get_all_teacher();
        $teachers_ = Format::personNameOnly($teachers);
        $teachers = [];
        foreach ($teachers_ as $teacher) {
            $pre_loaded = 0;
            $loaded = 0;
            $advisors = EgsAdvisor::find()->where(['teacher_id' => $teacher['id']])->all();
            foreach ($advisors as $advisor) {
                /* TODO : STUDENT WHO SILL IN SYSTEM */
                $finish_process = Validation::advisor_added($advisor->calendar->student_id);
                if ($finish_process) {
                    $loaded += $advisor->load->load_amount;
                } else {
                    $pre_loaded += $advisor->load->load_amount;
                }
            }
            /* TODO : MAX LOADED CONDITIONALLY TO ACADEMIC POSITION */
            $teacher['pre_loaded'] = $pre_loaded;
            $teacher['loaded'] = $loaded;
            $teacher['max'] = 6;
            array_push($teachers, $teacher);
        }
        return Json::encode($teachers);
    }
}
