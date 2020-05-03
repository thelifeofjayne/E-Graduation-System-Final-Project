<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsRequestInit;
use Yii;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class CalendarController extends Controller
{
    public function actionFindAll()
    {
        $calendar = EgsCalendar::find()->orderBy(['calendar_id' => SORT_DESC])->all();
        return Json::encode($calendar);
    }

    public function actionDelete($calendar_id)
    {
        $calendar = EgsCalendar::findOne(['calendar_id' => $calendar_id]);
        foreach ($calendar->egsCalendarItems as $calendar_item) {
            foreach ($calendar_item->egsUserRequests as $user_request) {
                foreach ($user_request->egsRequestDocuments as $request_document) $request_document->delete();
                foreach ($user_request->egsAdvisors as $advisor) $advisor->delete();
                foreach ($user_request->egsDefenses as $defense) {
                    foreach ($defense->egsDefenseDocuments as $defense_document)
                        $defense_document->delete();
                    foreach ($defense->egsCommittees as $committee)
                        $committee->delete();
                    foreach ($defense->egsDefenseSubjects as $defense_subject)
                        $defense_subject->delete();
                    foreach ($defense->egsDefenseAdvisors as $defense_advisor)
                        $defense_advisor->delete();
                    $defense->delete();
                }
                $user_request->delete();
            }
            $calendar_item->delete();
        }
        $calendar->delete();
        return Json::encode($calendar);
    }

    public function actionFind($calendar_id)
    {
        $calendar = EgsCalendar::findOne($calendar_id);
        return Json::encode($calendar);
    }

    public function actionActivate()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $old_calendar = EgsCalendar::find()->where(['calendar_active' => 1])->one();
        if (!empty($old_calendar)) {
            $old_calendar->calendar_active = 0;
            $old_calendar->save();
        }
        $calendar = EgsCalendar::findOne($post['calendar_id']);
        $calendar->calendar_active = 1;
        if (!$calendar->save()) return Json::encode($calendar->errors);
        return Json::encode(1);
    }

    public function actionCalendarInsert()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $year = $post['year'];
        if (EgsCalendar::findOne($year) !== null)
            return Json::encode(0);
        $calendar = new EgsCalendar();
        $calendar->calendar_id = $year;
        $calendar->calendar_active = 0;
        if ($calendar->save()) {
            $action_items = EgsActionItem::find()->joinWith(['action a'])->where(['!=', 'a.action_type_id', Config::$ACTION_INIT_TYPE])->all();
            foreach ($action_items as $action_item) {
                $calendar_item = new EgsCalendarItem();
                $calendar_item->calendar_id = $calendar->calendar_id;
                $calendar_item->action_id = $action_item->action_id;
                $calendar_item->level_id = $action_item->level_id;
                $calendar_item->semester_id = $action_item->semester_id;
                $calendar_item->owner_id = Config::$SYSTEM_ID;
                if ($calendar_item->save()) {
                    $request_init = EgsRequestInit::find()->where(['request_type_id' => $calendar_item->action_id])->one();
                    if (!empty($request_init)) {
                        $calendar_item_ = new EgsCalendarItem();
                        $calendar_item_->calendar_id = $calendar_item->calendar_id;
                        $calendar_item_->action_id = $request_init->init_type_id;
                        $calendar_item_->level_id = $calendar_item->level_id;
                        $calendar_item_->semester_id = $calendar_item->semester_id;
                        $calendar_item_->owner_id = Config::$SYSTEM_ID;
                        if (!$calendar_item_->save()) return Json::encode($calendar_item_->errors);
                    }
                } else {
                    return Json::encode($calendar_item->errors);
                }
            }
        } else {
            return Json::encode($calendar->errors);
        }
        return Json::encode(1);
    }
}
