<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsStatus;
use app\modules\egs\models\EgsStatusType;
use app\modules\egs\models\EgsUserRequest;
use Yii;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class StatusController extends Controller
{
    public function actionAll()
    {
        $status = EgsStatus::find()->all();
        return Json::encode(Format::status($status));
    }
}
