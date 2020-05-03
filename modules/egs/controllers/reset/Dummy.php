<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\controllers\Config;
use app\modules\egs\models\EgsActionDocument;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionType;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsCommitteeFee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDocument;
use app\modules\egs\models\EgsDocumentType;
use app\modules\egs\models\EgsEvaluation;
use app\modules\egs\models\EgsEvaluationTopic;
use app\modules\egs\models\EgsEvaluationTopicGroup;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsSubmitType;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\Json;


class Dummy
{
    public function insert()
    {
        $this->INITIAL_CALENDAR_DELETE_THIS_IN_PRODUCTION();
        $this->INIT_EVALUATION_DELETE_THIS_IN_PRODUCTION();
    }

    PRIVATE FUNCTION INITIAL_CALENDAR_DELETE_THIS_IN_PRODUCTION()
    {
        $calendar = new EgsCalendar();
        $calendar->calendar_id = 2569;
        $calendar->calendar_active = 1;
        if (!$calendar->save()) return Json::encode($calendar->errors);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 1, 1, '2018-01-01', '2018-12-31', [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13, 14
        ]);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 1, 2, '2018-01-01', '2018-12-31', [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13, 14
        ]);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 1, 3, '2018-01-01', '2018-12-31', [
            10, 11, 12, 13, 14
        ]);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 2, 1, '2018-02-01', '2018-12-31', [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13, 14
        ]);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 2, 2, '2018-02-01', '2018-12-31', [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13, 14
        ]);
        $this->INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar->calendar_id, 2, 3, '2018-02-01', '2018-12-31', [
            10, 11, 12, 13, 14
        ]);
    }

    PRIVATE FUNCTION INIT_CALENDAR_ITEM_DELETE_THIS_IN_PRODUCTION($calendar_id, $level_id, $semster_id, $start, $end, $actions)
    {
        foreach ($actions as $action) {
            $calendar_item = new EgsCalendarItem();
            $calendar_item->calendar_id = $calendar_id;
            $calendar_item->action_id = $action;
            $calendar_item->level_id = $level_id;
            $calendar_item->semester_id = $semster_id;
            $calendar_item->calendar_item_date_start = $start;
            $calendar_item->calendar_item_date_end = $end;
            $calendar_item->owner_id = Config::$SYSTEM_ID;
            if (!$calendar_item->save()) {
                echo Json::encode($calendar_item->errors);
                exit();
            }
            if ($action === 11 || $action === 12) {
                $calendar_item->calendar_item_date_start = $start;
                $calendar_item->calendar_item_date_end = $start;
                if (!$calendar_item->save()) {
                    echo $calendar_item->errors;
                    exit();
                }
            }
            if ($action === 13) {
                $calendar_item->calendar_item_date_start = $start;
                $calendar_item->calendar_item_date_end = $end;
                if (!$calendar_item->save()) {
                    echo $calendar_item->errors;
                    exit();
                }
                $user_request = new EgsUserRequest();
                $user_request->student_id = Config::$SYSTEM_ID;
                $user_request->calendar_id = $calendar_item->calendar_id;
                $user_request->action_id = $calendar_item->action_id;
                $user_request->level_id = $calendar_item->level_id;
                $user_request->semester_id = $calendar_item->semester_id;
                $user_request->owner_id = $calendar_item->owner_id;
                $user_request->request_fee = 0;
                $user_request->request_fee_status_id = 1;
                if (!$user_request->save()) {
                    echo Json::encode(['USER_REQUEST', $user_request->errors]);
                    exit();
                }
                $request_defenses = EgsRequestDefense::find()->where(['request_type_id' => $user_request->action_id])->all();
                foreach ($request_defenses as $request_defense) {
                    $defense = new EgsDefense();
                    $defense->student_id = $user_request->student_id;
                    $defense->calendar_id = $user_request->calendar_id;
                    $defense->action_id = $user_request->action_id;
                    $defense->level_id = $user_request->level_id;
                    $defense->semester_id = $user_request->semester_id;
                    $defense->defense_type_id = $request_defense->defense_type_id;
                    $defense->defense_date = $start;
                    $defense->owner_id = $user_request->owner_id;
                    $defense->defense_time_start = '12:00';
                    $defense->defense_time_end = '14:00';
                    $defense->room_id = 1;
                    $defense->defense_status_id = 1;
                    if (!$defense->save()) {
                        echo Json::encode(['DEFENSE', $defense->errors]);
                        exit();
                    }
                    $committee = new EgsCommittee();
                    $committee->student_id = $defense->student_id;
                    $committee->calendar_id = $defense->calendar_id;
                    $committee->action_id = $defense->action_id;
                    $committee->level_id = $defense->level_id;
                    $committee->semester_id = $defense->semester_id;
                    $committee->defense_type_id = $defense->defense_type_id;
                    $committee->owner_id = $defense->owner_id;
                    $committee->committee_fee = 0;
                    $committee->teacher_id = 2;
                    $committee->position_id = 3;
                    if (!$committee->save()) {
                        echo Json::encode(['COMMITTEE', $committee->errors]);
                        exit();
                    }
                }
            }
        }
    }

    PRIVATE FUNCTION INIT_EVALUATION_DELETE_THIS_IN_PRODUCTION()
    {
        $evaluation = new EgsEvaluation();
        $evaluation->evaluation_name_th = 'ประเมิน LOLLLLLLL';
        $evaluation->evaluation_name_en = 'EVAL LOLLLLLLL';
        $evaluation->evaluation_path = 'XD';
        $evaluation->evaluation_active = 1;
        if (!$evaluation->save()) {
            echo Json::encode($evaluation->errors);
            exit();
        } else {
            $groups = [
                'หลักสูตร' => [
                    'การจัดการศึกษาสอดคล้องกับปรัชญาและวัตถุประสงค์ของหลักสูตร',
                    'มีการจัดแผนการศึกษาตลอดหลักสูตรอย่างชัดเจน',
                    'มีปฏิทินการศึกษาและโปรแกรมการศึกษาแต่ละภาคการศึกษาอย่างชัดเจน',
                    'หลักสูตรมีความทันสมัยสอดคล้องกับความต้องการของตลาดแรงงาน',
                    'วิชาเรียนมีความเหมาะสมและสอดคล้องกับความต้องการของนักศึกษา'
                ],
                'อาจารย์ผู้สอน' => [
                    'อาจารย์มีคุณวุฒิและประสบการณ์เหมาะสมกับรายวิชาที่สอน',
                    'อาจารย์สอน เนื้อหา ตรงตามวัตถุประสงค์ โดยใช้วิธีการที่หลากหลาย',
                    'อาจารย์สนับสนุนส่งเสริมให้นักศึกษาเรียนรู้ และพัฒนาตนเองอย่างสม่ำเสมอ',
                    'อาจารย์ให้คำปรึกษาด้านวิชาการและการพัฒนานักศึกษาได้อย่างเหมาะสม',
                    'อาจารย์เป็นผู้มีคุณธรรม และจิตสำนึกในความเป็นครู'
                ],
                'สภาพแวดล้อมการเรียนรู้' => [
                    'ห้องเรียนมีอุปกรณ์เหมาะสม เอื้อต่อการเรียนรู้ และเพียงพอต่อนักศึกษา',
                    'ห้องปฏิบัติการมีอุปกรณ์เหมาะสม เอื้อต่อการเรียนรู้ และเพียงพอต่อนักศึกษา',
                    'ระบบบริการสารสนเทศเหมาะสม เอื้อต่อการเรียนรู้และเพียงพอต่อนักศึกษา'
                ],
                'การจัดการเรียนการสอน' => [
                    'การจัดการเรียนการสอนสอดคล้องกับลักษณะวิชาและวัตถุประสงค์การเรียนรู้',
                    'การใช้สื่อประกอบการสอนอย่างเหมาะสม',
                    'วิธีการสอนส่งเสริมให้นักศึกษาได้ประยุกต์แนวคิดศาสตร์ทางวิชาชีพและ/หรือศาสตร์ที่เกี่ยวข้องในการพัฒนาการเรียนรู้',
                    'มีการใช้เทคโนโลยีสารสนเทศประกอบการเรียนการสอน',
                    'การจัดการเรียนการสอนที่ส่งเสริมทักษะทางภาษาสากล'
                ],
                'การวัดและประเมินผล' => [
                    'วิธีการวัดประเมินผลสอดคล้องกับวัตถุประสงค์ และกิจกรรมการเรียนการสอน',
                    'การวัดและประเมินผลเป็นไปตามระเบียบกฎเกณฑ์ และข้อตกลง ที่กำหนดไว้ล่วงหน้า'
                ],
                'การเรียนรู้ตลอดหลักสูตรได้พัฒนาคุณลักษณะของนักศึกษา' => [
                    'ด้านคุณธรรม จริยธรรม',
                    'ด้านความรู้',
                    'ด้านทักษะทางปัญญา',
                    'ด้านความสัมพันธ์ระหว่างบุคคลและความรับผิดชอบ',
                    'ด้านทักษะการวิเคราะห์เชิงตัวเลข การสื่อสาร และการใช้เทคโนโลยีสารสนเทศ'
                ]
            ];
            $this->INIT_EVALUATION_DATA_DELETE_THIS_IN_PRODUCTION($groups, $evaluation->evaluation_id);
        }
    }

    PRIVATE FUNCTION INIT_EVALUATION_DATA_DELETE_THIS_IN_PRODUCTION($groups, $evaluation_id)
    {
        foreach ($groups as $group_name => $topics) {
            $eval_topic_group = new EgsEvaluationTopicGroup();
            $eval_topic_group->evaluation_topic_group_name_th = $group_name;
            $eval_topic_group->evaluation_topic_group_name_en = $group_name;
            $eval_topic_group->evaluation_id = $evaluation_id;
            if (!$eval_topic_group->save()) {
                echo Json::encode($eval_topic_group->errors);
                exit();
            } else {
                foreach ($topics as $topic) {
                    $eval_topic = new EgsEvaluationTopic();
                    $eval_topic->evaluation_topic_name_th = $topic;
                    $eval_topic->evaluation_topic_name_en = $topic;
                    $eval_topic->evaluation_topic_group_id = $eval_topic_group->evaluation_topic_group_id;
                    if (!$eval_topic->save()) {
                        echo Json::encode($eval_topic->errors);
                        exit();
                    }
                }
            }
        }
    }
}