<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsEvaluation;
use app\modules\egs\models\EgsEvaluationTopic;
use app\modules\egs\models\EgsEvaluationTopicGroup;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingComponent;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;

class EvaluationController extends Controller
{
    public function actionAll()
    {
        $evaluation = EgsEvaluation::find()->all();
        return Json::encode(Format::evaluation($evaluation));
    }

    public function actionActive($eval_id)
    {
        $evaluations = EgsEvaluation::find()->all();
        foreach ($evaluations as $evaluation) {
            $evaluation->evaluation_active = $evaluation->evaluation_id === (int)$eval_id ? 1 : 0;
            if (!$evaluation->save()) return Json::encode($evaluation->errors);
        }
        return Json::encode(Format::evaluation($evaluations));
    }

    public function actionFind($eval_id)
    {
        $evaluation = EgsEvaluation::findOne(['evaluation_id' => $eval_id]);
        return Json::encode(Format::evaluationFull($evaluation));
    }

    public function actionCurrent()
    {
        $evaluation = EgsEvaluation::find()->where(['evaluation_active' => 1])->one();
        return Json::encode(Format::evaluationFull($evaluation));
    }

    public function actionDelete($eval_id)
    {
        $evaluation = EgsEvaluation::findOne(['evaluation_id' => $eval_id]);
        foreach ($evaluation->egsUserEvaluations as $user_evaluation) {
            foreach ($user_evaluation->egsUserEvaluationRates as $user_evaluation_rate) {
                $user_evaluation_rate->delete();
            }
            $user_evaluation->delete();
        }
        foreach ($evaluation->egsEvaluationTopicGroups as $evaluation_topic_group) {
            foreach ($evaluation_topic_group->egsEvaluationTopics as $evaluation_topic) {
                $evaluation_topic->delete();
            }
            $evaluation_topic_group->delete();
        }
        $file = Yii::getAlias('@egswebdir/uploads/eval/eval_template_' . $evaluation->evaluation_id . '.docx');
        if (file_exists($file)) unlink($file);
        $evaluation->delete();
        return Json::encode(null);
    }

    public function actionInsert()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $name = Json::decode(Yii::$app->request->post('name'));
        $evaluation = new EgsEvaluation();
        $evaluation->evaluation_name_th = $name['name'];
        $evaluation->evaluation_name_en = $name['name'];
        $evaluation->evaluation_path = 'XD';
        $evaluation->evaluation_active = 0;
        if ($evaluation->save()) {
            $evaluation->evaluation_path = $this->upload($evaluation);
            if ($evaluation->save()) {
                foreach ($post['group'] as $group) {
                    $evaluation_topic_group = new EgsEvaluationTopicGroup();
                    $evaluation_topic_group->evaluation_topic_group_name_th = $group['name'];
                    $evaluation_topic_group->evaluation_topic_group_name_en = $group['name'];
                    $evaluation_topic_group->evaluation_id = $evaluation->evaluation_id;
                    if ($evaluation_topic_group->save()) {
                        foreach ($group['topic'] as $topic) {
                            $evaluation_topic = new EgsEvaluationTopic();
                            $evaluation_topic->evaluation_topic_name_th = $topic['name'];
                            $evaluation_topic->evaluation_topic_name_en = $topic['name'];
                            $evaluation_topic->evaluation_topic_group_id = $evaluation_topic_group->evaluation_topic_group_id;
                            if (!$evaluation_topic->save()) return Json::encode($evaluation_topic->errors);
                        }
                    } else {
                        return Json::encode($evaluation_topic_group->errors);
                    }
                }
            } else {
                return Json::encode($evaluation->errors);
            }
        } else {
            return Json::encode($evaluation->errors);
        }
        return Json::encode($evaluation);
    }

    private function upload($evaluation)
    {
        /* @var $evaluation EgsEvaluation */
        $file_name = 'eval_template_' . $evaluation->evaluation_id . '.docx';
        $source = $_FILES['file']['tmp_name'];
        $target_dir = Yii::getAlias('@egswebdir/uploads/eval/');
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (file_exists($target_dir . $file_name)) {
            unlink($target_dir . $file_name);
        }
        move_uploaded_file($source, $target_dir . $file_name);
        $path = Yii::getAlias('@egsweb/uploads/eval/');
        return $path . $file_name;
    }
}