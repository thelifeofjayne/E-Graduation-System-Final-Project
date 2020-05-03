<?php

namespace app\modules\egs\controllers;


use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsEvaluation;
use app\modules\egs\models\EgsEvaluationTopic;
use app\modules\egs\models\EgsEvaluationTopicGroup;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingComponent;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsUserEvaluation;
use app\modules\egs\models\EgsUserEvaluationRate;
use app\modules\egs\models\EgsUserRequest;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class UserEvaluationController extends Controller
{
    public function actionAll($calendar, $semester)
    {
        $calendar_id = $calendar === 'null' ? EgsCalendar::find()->where(['calendar_active' => 1])->one()->calendar_id : $calendar;
        $semester_id = $semester === 'null' ? EgsSemester::find()->one()->semester_id : $semester;
        $user_evaluation = EgsUserEvaluation::find()
            ->joinWith(['evaluation e'])
            ->where([
                'e.evaluation_active' => 1,
                'calendar_id' => $calendar_id,
                'semester_id' => $semester_id
            ])->all();
        return Json::encode(Format::user_evaluation($user_evaluation));
    }

    public function actionSubmitted()
    {
        $student_id = Config::get_user_id();
        $user_evaluation = EgsUserEvaluation::find()
            ->joinWith(['evaluation e'])
            ->where([
                'student_id' => $student_id,
                'e.evaluation_active' => 1
            ])->one();
        return Json::encode(!empty($user_evaluation));
    }

    public function actionInsert()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $student = Config::get_current_user();
        $student_id = $student['id'];
        $calendar_item = $post['calendar_item'];
        $user_request = EgsUserRequest::findOne([
            'student_id' => $student_id,
            'calendar_id' => $calendar_item['calendar_id'],
            'action_id' => $calendar_item['action_id'],
            'level_id' => $calendar_item['level_id'],
            'semester_id' => $calendar_item['semester_id'],
            'owner_id' => $calendar_item['owner_id']
        ]);
        if (empty($user_request)) {
            $user_request = new EgsUserRequest();
            $user_request->student_id = $student_id;
            $user_request->calendar_id = $calendar_item['calendar_id'];
            $user_request->action_id = $calendar_item['action_id'];
            $user_request->level_id = $calendar_item['level_id'];
            $user_request->semester_id = $calendar_item['semester_id'];
            $user_request->owner_id = $calendar_item['owner_id'];
            $user_request->request_fee = 0;
            $user_request->request_fee_status_id = Config::$FEE_STATUS_NOT_PAY;
            if ($user_request->save()) {
                $evaluation = $post['evaluation'];
                $user_evaluation = new EgsUserEvaluation();
                $user_evaluation->student_id = $user_request->student_id;
                $user_evaluation->calendar_id = $user_request->calendar_id;
                $user_evaluation->action_id = $user_request->action_id;
                $user_evaluation->level_id = $user_request->level_id;
                $user_evaluation->semester_id = $user_request->semester_id;
                $user_evaluation->owner_id = $user_request->owner_id;
                $user_evaluation->evaluation_id = $evaluation['evaluation_id'];
                $user_evaluation->user_evaluation_data = $evaluation['data'];
                if ($user_evaluation->save()) {
                    foreach ($evaluation['evaluation_topic_group'] as $evaluation_topic_group) {
                        foreach ($evaluation_topic_group['evaluation_topic'] as $evaluation_topic) {
                            $user_evaluation_rate = new EgsUserEvaluationRate();
                            $user_evaluation_rate->student_id = $user_evaluation->student_id;
                            $user_evaluation_rate->calendar_id = $user_evaluation->calendar_id;
                            $user_evaluation_rate->action_id = $user_evaluation->action_id;
                            $user_evaluation_rate->level_id = $user_evaluation->level_id;
                            $user_evaluation_rate->semester_id = $user_evaluation->semester_id;
                            $user_evaluation_rate->owner_id = $user_evaluation->owner_id;
                            $user_evaluation_rate->evaluation_id = $user_evaluation->evaluation_id;
                            $user_evaluation_rate->evaluation_topic_id = $evaluation_topic['evaluation_topic_id'];
                            $user_evaluation_rate->evaluation_rate = $evaluation_topic['value'];
                            if (!$user_evaluation_rate->save()) return Json::encode($user_evaluation_rate->errors);
                        }
                    }
                } else {
                    return Json::encode($user_evaluation->errors);
                }

                return Json::encode(null);
            } else {
                return Json::encode($user_request->errors);
            }
        }
        return Json::encode(null);
    }
}
