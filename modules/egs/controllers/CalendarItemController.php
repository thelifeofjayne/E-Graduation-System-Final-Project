<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsProgramBinder;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class CalendarItemController extends Controller
{
    public function actionFindInit()
    {
        $user = Config::get_current_user();
        $user_type_id = $user['user_type_id'];
        $calendar = EgsCalendar::find()->where(['calendar_active' => 1])->one();
        $calendar->egsCalendarItems;
        $calendar_items = EgsCalendarItem::find()
            ->joinWith(['calendar c'])
            ->joinWith(['semester.action a'])
            ->where([
                'a.action_type_id' => Config::$ACTION_INIT_TYPE,
                'c.calendar_active' => 1,
            ])->all();
        return Json::encode(Format::calendarItemWithStatus($calendar_items, $user_type_id, null, null));
    }

    public function actionFind($calendar_id)
    {
        $calendar_items = EgsCalendarItem::find()
            ->joinWith(['semester.action a'])
            ->where([
                'calendar_id' => $calendar_id,
                'owner_id' => Config::$SYSTEM_ID
            ])->andWhere(['!=', 'a.action_type_id', Config::$ACTION_INIT_TYPE])
            ->all();
        return Json::encode(Format::calendarItem($calendar_items));
    }

    public function actionFindOne($calendar_id, $semester_id, $action_id, $owner_id, $level_id)
    {
        $user = Config::get_current_user();
        $program = EgsProgramBinder::find()->where(['reg_program_id' => $user['program_id']])->one();
        $program_id = empty($program) ? -1 : $program->program_id;
        $plan = EgsPlanBinder::find()->where(['reg_program_id' => $user['program_id']])->one();
        $plan_id = empty($plan) ? -1 : $plan->plan_id;
        $user_type_id = $user['user_type_id'];
        $calendar_item = EgsCalendarItem::findOne([
            'calendar_id' => $calendar_id,
            'level_id' => $level_id,
            'semester_id' => $semester_id,
            'action_id' => $action_id,
            'owner_id' => $owner_id
        ]);
        return Json::encode(Format::calendarItemFull($calendar_item, $user_type_id, $plan_id, $program_id));
    }

    public function actionFindWithStatus()
    {
        $user = Config::get_current_user();
        $level = EgsLevelBinder::find()->where(['reg_program_id' => $user['program_id']])->one();
        $level_id = empty($level) ? -1 : $level->level_id;
        $program = EgsProgramBinder::find()->where(['reg_program_id' => $user['program_id']])->one();
        $program_id = empty($program) ? -1 : $program->program_id;
        $plan = EgsPlanBinder::find()->where(['reg_program_id' => $user['program_id']])->one();
        $plan_id = empty($plan) ? -1 : $plan->plan_id;
        $calendar_items = EgsCalendarItem::find()
            ->joinWith(['calendar c'])
            ->joinWith(['semester.action a'])
            ->where([
                'owner_id' => Config::$SYSTEM_ID,
                'c.calendar_active' => 1,
                'egs_calendar_item.level_id' => $level_id,
            ])
            ->orWhere(['owner_id' => $user['id']])
            ->andWhere(['!=', 'a.action_type_id', Config::$ACTION_DEFENSE_TYPE])
            ->andWhere(['!=', 'a.action_type_id', Config::$ACTION_INIT_TYPE])
            ->all();
        return Json::encode(Format::calendarItemWithStatus($calendar_items, null, $program_id, $plan_id));
    }

    public function actionUpdate()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $calendar_item = EgsCalendarItem::findOne([
            'calendar_id' => $post['calendar_id'],
            'action_id' => $post['action_id'],
            'level_id' => $post['level_id'],
            'semester_id' => $post['semester_id'],
            'owner_id' => Config::$SYSTEM_ID
        ]);
        $calendar_item->calendar_item_date_start = $post['calendar_item_date_start'];
        $calendar_item->calendar_item_date_end = $post['calendar_item_date_end'];
        if (!$calendar_item->save()) {
            return Json::encode($calendar_item->errors);
        }
        return Json::encode(1);
    }
}