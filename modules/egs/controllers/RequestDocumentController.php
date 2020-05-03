<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsActionOnStatus;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsRequestDocument;
use app\modules\egs\models\EgsUserRequest;
use Yii;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class RequestDocumentController extends Controller
{
    public function actionUpdate()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $request_document = EgsRequestDocument::findOne([
            'student_id' => $post['studentId'],
            'calendar_id' => $post['calendarId'],
            'action_id' => $post['actionId'],
            'level_id' => $post['levelId'],
            'semester_id' => $post['semesterId'],
            'document_id' => $post['documentId'],
            'owner_id' => $post['ownerId']
        ]);
        $request_document->request_document_id = $post['requestDocumentId'];
        $request_document->request_document_status_id = $post['requestDocumentId'] === null ? Config::$DOC_STATUS_NOT_SUBMITTED : Config::$DOC_STATUS_SUBMMITTED;
        if (!$request_document->save()) return Json::encode($request_document->errors);
        return Json::encode(Format::userRequestForListing($request_document->calendar));
    }
}
