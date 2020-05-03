<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsAdvisorFee;
use app\modules\egs\models\EgsBranchBinder;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsSubject;
use app\modules\egs\models\EgsSubjectFor;
use Yii;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class TempoController extends Controller
{

    private $VIEW_NAME = "view_pis_user";

    private $DEFAULT_LANGUAGE = 'th';

    public function actionTest()
    {
        $student = Config::get_current_user();
        $student_id = (int)$student['user_type_id'] === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : $student['id'];
        $reg_program_id = $student['program_id'];
        $plan = EgsPlanBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $plan_id = empty($plan) ? null : $plan->plan_id;
        $program = EgsProgramBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $program_id = empty($program) ? null : $program->program_id;

        $subject_for = EgsSubjectFor::find()->where([
            'plan_id' => $plan_id,
            'program_id' => $program_id
        ])->all();
        $subject = EgsSubject::find()
            ->joinWith(['egsSubjectFors sf'])
            ->where([
                'sf.plan_id' => $plan_id,
                'sf.program_id' => $program_id
            ])->all();
        return Json::encode($subject_for);
    }

    public function actionIndex()
    {
        $datas = [];
        $persons = Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user')->queryAll();
        foreach ($persons as $person) {
            $person['program'] = Yii::$app->getDb()->createCommand(
                'SELECT * FROM reg_program WHERE PROGRAMID LIKE "' . $person['program_id'] . '"'
            )->queryOne()['PROGRAMNAME'];
            array_push($datas, $person);
        }
        return $this->render('tempo', ['temp' => $datas]);
    }

    public function actionJson()
    {
        $temp = Yii::$app->getDb()->createCommand('SELECT * FROM view_pis_user')->queryAll();
        $user_id = Yii::$app->session->get('id');
        return Json::encode($temp);
    }

    public function actionLogin($id)
    {
        $session = Yii::$app->session;
        $session->set('id', $id);
        $this->redirect(Url::base() . '/egs');
    }

    public function actionLoginOffline($type)
    {
        $session = Yii::$app->session;
        $session->set('type', $type);
        $this->redirect(Url::base() . '/egs');
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->removeAll();
        $this->redirect(Url::base() . '/egs');
    }

    public function actionLanguage($lang)
    {
        Yii::$app->session->set('lang', $lang);
        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionGetLanguage()
    {
        $lang = $this->DEFAULT_LANGUAGE;
        if (Yii::$app->session->has('lang'))
            $lang = Yii::$app->session->get('lang');
        return Json::encode($lang);
    }
}
