<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsActionStep;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsUserRequest;
use Yii;
use yii\helpers\Json;

class Validation
{

    static public function request_proposal($calendar_item)
    {
        /* @var $calendar_item EgsCalendarItem */
        $advisor_added = Validation::advisor_added();

        $student = Config::get_current_user();
        $reg_program_id = $student['program_id'];
        $level = EgsLevelBinder::find()->where(['reg_program_id' => $reg_program_id])->one();
        $level_id = empty($level) ? null : $level->level_id;

        $defense_compre_qe_passed = true;
        if ($level_id === Config::$LEVEL_DOCTOR) {
            $defense_compre_qe_passed = Validation::defense_compre_qe_passed();
        }

        return $advisor_added && $defense_compre_qe_passed;
    }

    static public function request_final($calendar_item)
    {
        /* @var $calendar_item EgsCalendarItem */
        $todo = $calendar_item->semester->action->todo;
        $defense_proposal_passed = Validation::defense_proposal_passed();
        return $defense_proposal_passed;
    }

    static public function request_progress()
    {
        $advisor_added = Validation::advisor_added();
        return $advisor_added;
    }

    static public function request_compre_qe()
    {
        return true;
    }

    static public function advisor_added($student_id = null)
    {
        if ($student_id === null) {
            $student_id = Config::get_user_id();
        }
        $action_id = Config::$REQUEST_ADVISOR;
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->all();
        $action_steps = EgsActionStep::find()->where([
            'action_id' => $action_id
        ])->all();
        foreach ($user_requests as $user_request) {
            $current_step = Validation::current_step($user_request);
            if ($action_steps[sizeof($action_steps) - 1]->step->step_id === $current_step) {
                return true;
            }
        }
        return false;
    }

    static public function defense_proposal_passed()
    {
        $action_id = Config::$REQUEST_PROPOSAL;
        $student_id = Config::get_user_id();
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->all();
        $action_steps = EgsActionStep::find()->where([
            'action_id' => $action_id
        ])->all();
        foreach ($user_requests as $user_request) {
            $current_step = Validation::current_step($user_request);
            if ($action_steps[sizeof($action_steps) - 1]->step->step_id === $current_step) {
                foreach ($user_request->egsDefenses as $defense) {
                    $defense_status = $defense->defense_status_id;
                    if ($defense_status === Config::$DEFENSE_STATUS_PASS || $defense_status === Config::$DEFENSE_STATUS_PASS_CON) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    static public function defense_final_passed()
    {
        $action_id = Config::$REQUEST_FINAL_1;
        $action_id_ = Config::$REQUEST_FINAL_2;
        $student_id = Config::get_user_id();
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->orWhere(['action_id' => $action_id_])->all();
        $action_steps = EgsActionStep::find()->where([
            'action_id' => $action_id
        ])->all();
        foreach ($user_requests as $user_request) {
            $current_step = Validation::current_step($user_request);
            if ($action_steps[sizeof($action_steps) - 1]->step->step_id === $current_step) {
                foreach ($user_request->egsDefenses as $defense) {
                    $defense_status = $defense->defense_status_id;
                    if ($defense_status === Config::$DEFENSE_STATUS_PASS || $defense_status === Config::$DEFENSE_STATUS_PASS_CON) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    static public function defense_progress_passed()
    {
        $student_id = Config::get_user_id();
        $credited = 0;
        $action_id = Config::$REQUEST_PROGRESS;
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->all();
        $action_steps = EgsActionStep::find()->where([
            'action_id' => $action_id
        ])->all();
        foreach ($user_requests as $user_request) {
            $current_step = Validation::current_step($user_request);
            if ($action_steps[sizeof($action_steps) - 1]->step->step_id === $current_step) {
                foreach ($user_request->egsDefenses as $defense) {
                    $credited += $defense->defense_credit;
                }
            }
        }
        return $credited >= Config::$REQUIRE_CREDITED;
    }

    static public function defense_compre_qe_passed()
    {
        $student_id = Config::get_user_id();
        $action_id = Config::$REQUEST_COMPRE_QE;
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->all();
        $action_steps = EgsActionStep::find()->where([
            'action_id' => $action_id
        ])->all();
        $defense_writing_passed = false;
        $defense_oral_passed = false;
        foreach ($user_requests as $user_request) {
            $current_step = Validation::current_step($user_request);
            if ($action_steps[sizeof($action_steps) - 1]->step->step_id === $current_step) {
                foreach ($user_request->egsDefenses as $defense) {
                    if ($defense->defense_type_id === Config::$DEFENSE_WIRTE) {
                        if ($defense->defense_status_id === Config::$SUBJECT_STATUS_PASS_ALL) {
                            $defense_writing_passed = true;
                        }
                    } else if ($defense->defense_type_id === Config::$DEFENSE_ORAL) {
                        if ($defense->defense_status_id === Config::$DEFENSE_STATUS_PASS) {
                            $defense_oral_passed = true;
                        }
                    }
                }
            }
        }
        return $defense_writing_passed && $defense_oral_passed;
    }

    static public function publication_submitted()
    {
        return false;
    }

    static public function evaluation_submitted()
    {
        $student_id = Config::get_user_id();
        $action_id = Config::$EVALUATION;
        $user_requests = EgsUserRequest::find()->where([
            'student_id' => $student_id,
            'action_id' => $action_id
        ])->all();

        return !empty($user_requests);
    }

    static public function current_step($user_request)
    {
        $action_steps = EgsActionStep::find()->where(['action_id' => $user_request->action_id])->all();
        foreach ($action_steps as $action_step) {
            $validator = $action_step->step->step_validation;
            if ($validator !== null)
                if (!Validation::$validator($user_request))
                    return $action_step->step->step_id;
        }
        return $action_steps[sizeof($action_steps) - 1]->step->step_id;
    }

    static public function request_document_before_and_fee_all_submit($user_request)
    {
        /* @var $user_request EgsUserRequest */
        $fee_valid = true;
        if ($user_request->request_fee_status_id === Config::$FEE_STATUS_NOT_PAY)
            $fee_valid = false;
        $doc_valid = true;
        foreach ($user_request->egsRequestDocuments as $request_document)
            if ($request_document->document->submit_type_id === Config::$SUBMIT_TYPE_BEFORE)
                if ($request_document->request_document_status_id === Config::$DOC_STATUS_NOT_SUBMITTED)
                    $doc_valid = false;
        return $fee_valid && $doc_valid;
    }

    static public function request_document_after_all_submit($user_request)
    {
        /* @var $user_request EgsUserRequest */
        $valid = true;
        foreach ($user_request->egsRequestDocuments as $request_document)
            if ($request_document->document->submit_type_id === Config::$SUBMIT_TYPE_AFTER)
                if ($request_document->request_document_status_id === Config::$DOC_STATUS_NOT_SUBMITTED)
                    $valid = false;
        return $valid;
    }

    static public function defense_resulted($user_request)
    {
        /* @var $user_request EgsUserRequest */
        $valid = true;
        foreach ($user_request->egsDefenses as $defense)
            if ($defense->defense_status_id === Config::$DEFENSE_STATUS_DEFAULT)
                $valid = false;
        return $valid;
    }

    static public function defense_document_before_all_submit($user_request)
    {
        /* @var $user_request EgsUserRequest */
        $valid = true;
        foreach ($user_request->egsDefenses as $defense)
            foreach ($defense->egsDefenseDocuments as $defense_document)
                if ($defense_document->document->submit_type_id === Config::$SUBMIT_TYPE_BEFORE)
                    if ($defense_document->defense_document_status_id === Config::$DOC_STATUS_NOT_SUBMITTED)
                        $valid = false;
        return $valid;
    }

    static public function defense_document_after_all_submit($user_request)
    {
        /* @var $user_request EgsUserRequest */
        $valid = true;
        foreach ($user_request->egsDefenses as $defense)
            foreach ($defense->egsDefenseDocuments as $defense_document)
                if ($defense_document->document->submit_type_id = Config::$SUBMIT_TYPE_AFTER)
                    if ($defense_document->defense_document_status_id === Config::$DOC_STATUS_NOT_SUBMITTED)
                        $valid = false;
        return $valid;
    }

}