<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsActionDocument;
use app\modules\egs\models\EgsActionOnStatus;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsAdvisorFee;
use app\modules\egs\models\EgsBranch;
use app\modules\egs\models\EgsBranchBinder;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsCommitteeFee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDefenseAdvisor;
use app\modules\egs\models\EgsDefenseDocument;
use app\modules\egs\models\EgsDefenseSubject;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsLoad;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsProject;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsRequestDocument;
use app\modules\egs\models\EgsRequestFee;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsSubjectFor;
use app\modules\egs\models\EgsUserRequest;
use Yii;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class UserRequestController extends Controller
{
    private $COMMITTEE_MAIN_FEE_PERCENTAGE = .4;
    private $COMMITTEE_CO_FEE_PERCENTAGE = .6;

    public function actionList($calendar, $level, $semester, $action)
    {
        $calendar_id = $calendar === 'null' ? EgsCalendar::find()->where(['calendar_active' => 1])->one()->calendar_id : $calendar;
        $level_id = $level === 'null' ? EgsLevel::find()->one()->level_id : $level;
        $semester_id = $semester === 'null' ? EgsSemester::find()->one()->semester_id : $semester;
        $action_id = $action === 'null' ? EgsAction::find()->where(['action_type_id' => Config::$ACTION_REQUEST_TYPE])->one()->action_id : $action;
        $user_request = EgsUserRequest::find()->where([
            'calendar_id' => $calendar_id,
            'level_id' => $level_id,
            'semester_id' => $semester_id,
            'action_id' => $action_id
        ])->andWhere(['!=', 'student_id', Config::$SYSTEM_ID])->all();
        $user = Config::get_current_user();
        return Json::encode(Format::userRequestForListing($user_request, $user['user_type_id']));
    }

    private function committeeFeeCalculator($action_id, $branch_id, $level_id, $plan_type_id, $committee_amount)
    {
        $amount = [];
        $committee_fee = EgsCommitteeFee::findOne([
            'action_id' => $action_id,
            'branch_id' => $branch_id,
            'level_id' => $level_id,
            'plan_type_id' => $plan_type_id
        ]);
        if (empty($committee_fee)) {
            $amount[Config::$COMMITTEE_MAIN_POSITION] = 0;
            $amount[Config::$COMMITTEE_CO_POSITION] = 0;
        } else {
            $amount[Config::$COMMITTEE_MAIN_POSITION] = $committee_fee->committee_fee_amount * $this->COMMITTEE_MAIN_FEE_PERCENTAGE;
            $amount[Config::$COMMITTEE_CO_POSITION] = ($committee_fee->committee_fee_amount * $this->COMMITTEE_CO_FEE_PERCENTAGE) / $committee_amount;
        }
        return $amount;
    }

    public function actionUpdateFee()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $user_request = EgsUserRequest::findOne([
            'student_id' => $post['studentId'],
            'calendar_id' => $post['calendarId'],
            'action_id' => $post['actionId'],
            'level_id' => $post['levelId'],
            'semester_id' => $post['semesterId'],
            'owner_id' => $post['owner_id']
        ]);
        $user_request->request_fee_status_id = $post['paid'] ? Config::$FEE_STATUS_PAID : Config::$FEE_STATUS_NOT_PAY;
        if (!$user_request->save()) return $user_request->errors;
        return Json::encode(Format::userRequestForListing($user_request));
    }

    public function actionInsert()
    {
        /* NOTE: INITIALIZE DATA */
        $post = Json::decode(Yii::$app->request->post('json'));
        $calendar_item = $post['calendarItem'];
        $teachers = $post['teachers'];
        $defenses = $post['defenses'];
        $student = Config::get_current_user();
        $student_id = (int)$student['user_type_id'] === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : $student['id'];
        $reg_program_id = $student['program_id'];
        $branch = EgsBranchBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $level = EgsLevelBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $plan = EgsPlanBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $program = EgsProgramBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $project = EgsProject::findOne(['student_id' => $student_id]);
        $branch_id = empty($branch) ? null : $branch->branch_id;
        $level_id = empty($level) ? null : $level->level_id;
        $plan_id = empty($plan) ? null : $plan->plan->plan_id;
        $plan_type_id = empty($plan) ? null : $plan->plan->plan_type_id;
        $program_id = empty($program) ? null : $program->program_id;
        $project_id = empty($project) ? null : $project->project_id;
        /* NOTE: FIND IF USER REQUEST EXIST */
        $user_request = EgsUserRequest::findOne([
            'student_id' => $student_id,
            'calendar_id' => $calendar_item['calendarId'],
            'action_id' => $calendar_item['actionId'],
            'level_id' => $calendar_item['levelId'],
            'semester_id' => $calendar_item['semesterId'],
            'owner_id' => $calendar_item['owner_id']
        ]);

        $advisor = EgsAdvisor::find()->where([
            'student_id' => $student_id,
            'position_id' => Config::$ADVISOR_MAIN_POSITION
        ])->one();

        $advisor_fee = EgsAdvisorFee::findOne([
            'plan_id' => $plan_id,
            'branch_id' => $branch_id,
            'action_id' => $calendar_item['actionId']
        ]);

        if (empty($user_request)) {
            /* NOTE: IF NOT INSERT */
            $user_request = new EgsUserRequest();
            $user_request->student_id = $student_id;
            $user_request->calendar_id = $calendar_item['calendarId'];
            $user_request->action_id = $calendar_item['actionId'];
            $user_request->level_id = $calendar_item['levelId'];
            $user_request->semester_id = $calendar_item['semesterId'];
            $user_request->owner_id = $calendar_item['owner_id'];
        } else {
            /* NOTE: IF EXIST DELETE ALL CHILDS */
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
        }
        $request_fee = EgsRequestFee::findOne([
            'plan_id' => $plan_id,
            'branch_id' => $branch_id,
            'action_id' => $calendar_item['actionId']
        ]);
        $user_request->request_fee = empty($request_fee) ? 0 : $request_fee->request_fee_amount;
        $user_request->request_fee_status_id = $user_request->request_fee === 0 ? Config::$DONT_NEED_TO_PAY : Config::$FEE_STATUS_NOT_PAY;
        /* NOTE: IF SAVE AND NOT INITAILIZE */
        if ($user_request->save()) {
            $is_defense = !empty(EgsRequestDefense::find()->where(['request_type_id' => $user_request->calendar->semester->action_id])->all());

            /* NOTE: FIND & INSERT DOCUMENT OF THIS ACTION */
            $action_documents = EgsActionDocument::findAll([
                'action_id' => $user_request->action_id
            ]);
            foreach ($action_documents as $action_document) {
                $request_document = new EgsRequestDocument();
                $request_document->student_id = $student_id;
                $request_document->calendar_id = $user_request->calendar_id;
                $request_document->action_id = $user_request->action_id;
                $request_document->level_id = $user_request->level_id;
                $request_document->semester_id = $user_request->semester_id;
                $request_document->owner_id = $user_request->owner_id;
                $request_document->document_id = $action_document->document_id;
                $request_document->request_document_status_id = Config::$DOC_STATUS_NOT_SUBMITTED;
                if (!$request_document->save()) return Json::encode($request_document->errors);
            }
            if (!$is_defense) {
                /* NOTE: IF NOT DEFENSE INSERT ADVISOR */
                foreach ($teachers as $teacher) {
                    $advisor = new EgsAdvisor();
                    $advisor->teacher_id = $teacher['teacher'];
                    $advisor->position_id = $teacher['position'];
                    $advisor->student_id = $user_request->student_id;
                    $advisor->action_id = $user_request->action_id;
                    $advisor->calendar_id = $user_request->calendar_id;
                    $advisor->level_id = $user_request->level_id;
                    $advisor->semester_id = $user_request->semester_id;
                    $advisor->student_id = $user_request->student_id;
                    $advisor->owner_id = $user_request->owner_id;
                    $advisor->load_id = $plan_type_id;
                    if (!$advisor->save()) return Json::encode($advisor->errors);
                }
            } else {
                /* NOTE: IF DEFENSE INSERT DEFENSE & COMMITEE */
                foreach ($defenses as $defense_) {
                    $defense_type_id = $defense_['type'];
                    $defense = new EgsDefense();
                    $defense->student_id = $user_request->student_id;
                    $defense->calendar_id = $user_request->calendar_id;
                    $defense->action_id = $user_request->action_id;
                    $defense->level_id = $user_request->level_id;
                    $defense->semester_id = $user_request->semester_id;
                    $defense->owner_id = $user_request->owner_id;
                    $defense->defense_type_id = $defense_type_id;
                    $defense->defense_date = $defense_['date'];
                    $defense->defense_time_start = $defense_['start'];
                    $defense->defense_time_end = $defense_['end'];
                    $defense->room_id = $defense_['room'];
                    $defense->defense_status_id = Config::$DEFENSE_STATUS_DEFAULT;
                    $defense->project_id = $project_id;
                    if ($defense->save()) {
                        $subject_fors = EgsSubjectFor::find()->where([
                            'action_id' => $defense->defense_type_id,
                            'program_id' => $program_id
                        ])->all();
                        if (!empty($subject_fors)) {
                            foreach ($subject_fors as $subject_for) {
                                $defense_subject = EgsDefenseSubject::find()->where([
                                    'defense_type_id' => $defense->defense_type_id,
                                    'action_id' => $defense->action_id,
                                    'owner_id' => $defense->owner_id,
                                    'level_id' => $defense->level_id,
                                    'student_id' => $defense->student_id,
                                    'subject_id' => $subject_for->subject_id
                                ])->one();
                                $already_passed = empty($defense_subject) ? 0 : $defense_subject->subject_pass ? 1 : 0;
                                $defense_subject = new EgsDefenseSubject();
                                $defense_subject->defense_type_id = $defense->defense_type_id;
                                $defense_subject->student_id = $student_id;
                                $defense_subject->calendar_id = $user_request->calendar_id;
                                $defense_subject->action_id = $user_request->action_id;
                                $defense_subject->level_id = $user_request->level_id;
                                $defense_subject->semester_id = $user_request->semester_id;
                                $defense_subject->owner_id = $user_request->owner_id;
                                $defense_subject->subject_id = $subject_for->subject_id;
                                $defense_subject->subject_pass = $already_passed;
                                $defense_subject->already_passed = $already_passed;
                                $defense_subject->defense_subject_status_id = $already_passed ? Config::$SUBJECT_STATUS_ALREADY_PASSED : Config::$DEFENSE_STATUS_FAIL;
                                if (!$defense_subject->save()) return Json::encode($subject_for->errors);
                            }
                        }
                        $action_documents = EgsActionDocument::findAll([
                            'action_id' => $defense->defense_type_id
                        ]);
                        foreach ($action_documents as $action_document) {
                            $defense_document = new EgsDefenseDocument();
                            $defense_document->defense_type_id = $defense->defense_type_id;
                            $defense_document->student_id = $student_id;
                            $defense_document->calendar_id = $user_request->calendar_id;
                            $defense_document->action_id = $user_request->action_id;
                            $defense_document->level_id = $user_request->level_id;
                            $defense_document->semester_id = $user_request->semester_id;
                            $defense_document->owner_id = $user_request->owner_id;
                            $defense_document->document_id = $action_document->document_id;
                            $defense_document->defense_document_status_id = Config::$DOC_STATUS_NOT_SUBMITTED;
                            if (!$defense_document->save()) return Json::encode($defense_document->errors);
                        }
                        if (!empty($advisor_fee)) {
                            $defense_advisor = new EgsDefenseAdvisor();
                            $defense_advisor->teacher_id = $advisor->teacher_id;
                            $defense_advisor->student_id = $defense->student_id;
                            $defense_advisor->action_id = $defense->action_id;
                            $defense_advisor->calendar_id = $defense->calendar_id;
                            $defense_advisor->level_id = $defense->level_id;
                            $defense_advisor->semester_id = $defense->semester_id;
                            $defense_advisor->defense_type_id = $defense->defense_type_id;
                            $defense_advisor->owner_id = $defense->owner_id;
                            $defense_advisor->advisor_fee_amount = $advisor_fee->advisor_fee_amount;
                            if (!$defense_advisor->save()) return Json::encode($defense_advisor->errors);
                        }
                        $fee = $this->committeeFeeCalculator($defense->defense_type_id, $branch_id, $level_id, $plan_type_id, sizeof($teachers));
                        foreach ($teachers as $teacher) {
                            $committee = new EgsCommittee();
                            $committee->teacher_id = $teacher['teacher'];
                            $committee->position_id = $teacher['position'];
                            $committee->student_id = $defense->student_id;
                            $committee->action_id = $defense->action_id;
                            $committee->calendar_id = $defense->calendar_id;
                            $committee->level_id = $defense->level_id;
                            $committee->semester_id = $defense->semester_id;
                            $committee->defense_type_id = $defense->defense_type_id;
                            $committee->owner_id = $defense->owner_id;
                            $committee->committee_fee = $fee[$teacher['position']];
                            if (!$committee->save()) return Json::encode($committee->errors);
                        }
                    } else {
                        return Json::encode($defense->errors);
                    }
                }
            }
        } else {
            return Json::encode($user_request->errors);
        }
        return Json::encode(null);
    }

    public function actionDelete()
    {
        $post = Json::decode(Yii::$app->request->post('json'));
        $user_request = EgsUserRequest::findOne([
            'student_id' => $post['student_id'],
            'calendar_id' => $post['calendar_id'],
            'action_id' => $post['action_id'],
            'level_id' => $post['level_id'],
            'semester_id' => $post['semester_id'],
            'owner_id' => $post['owner_id']
        ]);
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
        return Json::encode(null);
    }
}