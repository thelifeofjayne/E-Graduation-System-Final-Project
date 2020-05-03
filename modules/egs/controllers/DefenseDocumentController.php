<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsActionOnStatus;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDefenseDocument;
use app\modules\egs\models\EgsRequestDocument;
use app\modules\egs\models\EgsUserRequest;
use Yii;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class DefenseDocumentController extends Controller
{
    public function actionUpdate()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $defense_document = EgsDefenseDocument::findOne([
            'student_id' => $post['studentId'],
            'calendar_id' => $post['calendarId'],
            'action_id' => $post['actionId'],
            'level_id' => $post['levelId'],
            'semester_id' => $post['semesterId'],
            'document_id' => $post['documentId'],
            'owner_id' => $post['ownerId'],
            'defense_type_id' => $post['defenseTypeId']
        ]);
        if ($path = $this->upload($defense_document)) {
            $defense_document->defense_document_path = $path;
            $defense_document->defense_document_status_id = Config::$DOC_STATUS_SUBMMITTED;
        } else {
            $defense_document->defense_document_path = null;
            $defense_document->defense_document_status_id = Config::$DOC_STATUS_NOT_SUBMITTED;
        }
        if (!$defense_document->save()) return Json::encode($defense_document->errors);
        return Json::encode(Format::userRequestForListing($defense_document->defenseType->calendar));
    }

    private function upload($request_document)
    {
        $file_name = $request_document->student_id . $request_document->calendar_id . $request_document->action_id . $request_document->level_id . $request_document->semester_id . $request_document->document_id . '.pdf';
        $source = $_FILES['paper']['tmp_name'];
        $target_dir = Yii::getAlias('@egswebdir/uploads/papers/' . $request_document->student_id . '/');
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (file_exists($target_dir . $file_name)) {
            unlink($target_dir . $file_name);
        }
        move_uploaded_file($source, $target_dir . $file_name);
        $path = Yii::getAlias('@egsweb/uploads/papers/' . $request_document->student_id . '/');
        return $path . $file_name;
    }
}
