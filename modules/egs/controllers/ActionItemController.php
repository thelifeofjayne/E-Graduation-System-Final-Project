<?php

namespace app\modules\egs\controllers;


use app\modules\egs\controllers\Format;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsSemester;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;

class ActionItemController extends Controller
{
    public function actionFind()
    {
        $semester = EgsSemester::find()->one();
        $action_items = EgsActionItem::find()
            ->joinWith(['action a'])
            ->where([
                'semester_id' => $semester->semester_id,
            ])->andWhere(['!=', 'a.action_type_id', Config::$ACTION_INIT_TYPE])
            ->all();
        return Json::encode(Format::actionItemActionOnly($action_items));
    }
}
