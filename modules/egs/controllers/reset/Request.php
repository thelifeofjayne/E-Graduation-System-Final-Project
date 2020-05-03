<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionOnStatus;
use app\modules\egs\models\EgsActionType;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDefenseAdvisor;
use app\modules\egs\models\EgsDefenseDocument;
use app\modules\egs\models\EgsDefenseStatus;
use app\modules\egs\models\EgsDefenseSubject;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsPosition;
use app\modules\egs\models\EgsPositionType;
use app\modules\egs\models\EgsProject;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsRequestDocStatus;
use app\modules\egs\models\EgsRequestDocument;
use app\modules\egs\models\EgsRequestStatus;
use app\modules\egs\models\EgsRequestStatusType;
use app\modules\egs\models\EgsRoom;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsStatus;
use app\modules\egs\models\EgsStatusLabel;
use app\modules\egs\models\EgsStatusType;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\Json;

class Request
{
    public function delete()
    {
        EgsRequestDocument::deleteAll();
        EgsDefenseDocument::deleteAll();
        EgsDefenseSubject::deleteAll();
        EgsCommittee::deleteAll();
        EgsDefenseAdvisor::deleteAll();
        EgsDefense::deleteAll();
        EgsProject::deleteAll();
        EgsAdvisor::deleteAll();
        EgsUserRequest::deleteAll();
        EgsPosition::deleteAll();
        EgsPositionType::deleteAll();
        EgsRoom::deleteAll();
    }

    public function insert()
    {
        $this->position_type();
        $this->position();
        $this->room();
    }

    private function position_type()
    {
        $position_type = new EgsPositionType();
        $position_type->position_type_id = 1;
        $position_type->position_type_name = "comittee's position";
        if (!$position_type->save()) return Json::encode($position_type->errors);
        $position_type = new EgsPositionType();
        $position_type->position_type_id = 2;
        $position_type->position_type_name = "advisor's position";
        if (!$position_type->save()) return Json::encode($position_type->errors);
    }

    private function position()
    {
        $position = new EgsPosition();
        $position->position_id = 1;
        $position->position_name_th = 'อาจารย์ที่ปรึกษาหลัก';
        $position->position_name_en = 'main-advisor';
        $position->position_minimum = 1;
        $position->position_maximum = 1;
        $position->position_type_id = 2;
        if (!$position->save()) return Json::encode($position->errors);
        $position = new EgsPosition();
        $position->position_id = 2;
        $position->position_name_th = 'อาจารย์ที่ปรึกษาร่วม';
        $position->position_name_en = 'co-advisor';
        $position->position_minimum = 0;
        $position->position_maximum = 2;
        $position->position_type_id = 2;
        if (!$position->save()) return Json::encode($position->errors);
        $position = new EgsPosition();
        $position->position_id = 3;
        $position->position_name_th = 'ประธานกรรมการ';
        $position->position_name_en = 'main-committee';
        $position->position_minimum = 1;
        $position->position_maximum = 1;
        $position->position_type_id = 1;
        if (!$position->save()) return Json::encode($position->errors);
        $position = new EgsPosition();
        $position->position_id = 4;
        $position->position_name_th = 'กรรมการ';
        $position->position_name_en = 'committee';
        $position->position_minimum = 0;
        $position->position_maximum = 3;
        $position->position_type_id = 1;
        if (!$position->save()) return Json::encode($position->errors);
    }

    private function room()
    {
        $room = new EgsRoom();
        $room->room_id = 1;
        $room->room_name_th = "ห้อง1";
        $room->room_name_en = "ROOM1";
        if (!$room->save()) return Json::encode($room->errors);
        $room = new EgsRoom();
        $room->room_id = 2;
        $room->room_name_th = "ห้อง2";
        $room->room_name_en = "ROOM2";
        if (!$room->save()) return Json::encode($room->errors);
        $room = new EgsRoom();
        $room->room_id = 3;
        $room->room_name_th = "ห้อง3";
        $room->room_name_en = "ROOM3";
        if (!$room->save()) return Json::encode($room->errors);
    }
}