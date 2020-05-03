<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionType;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsAdvisorFee;
use app\modules\egs\models\EgsBranchBinder;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsCommitteeFee;
use app\modules\egs\models\EgsBranch;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDefenseStatus;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsLoad;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsPlanType;
use app\modules\egs\models\EgsPosition;
use app\modules\egs\models\EgsPositionType;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsRequestDocStatus;
use app\modules\egs\models\EgsRequestFee;
use app\modules\egs\models\EgsRequestStatus;
use app\modules\egs\models\EgsRequestStatusType;
use app\modules\egs\models\EgsRoom;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsStatus;
use app\modules\egs\models\EgsStatusLabel;
use app\modules\egs\models\EgsStatusType;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\Json;

class Fee
{
    public function delete()
    {
        EgsLoad::deleteAll();
        EgsRequestFee::deleteAll();
        EgsCommitteeFee::deleteAll();
        EgsAdvisorFee::deleteAll();
    }

    public function insert()
    {
        $this->committee_fee();
        $this->load();
        $this->request_fee();
        $this->advisor_fee();
    }

    private function advisor_fee_insert($actions, $plans, $branchs)
    {
        foreach ($actions as $action) {
            foreach ($plans as $plan) {
                foreach ($branchs as $branch => $amount) {
                    $advisor_fee = new EgsAdvisorFee();
                    $advisor_fee->plan_id = $plan;
                    $advisor_fee->branch_id = $branch;
                    $advisor_fee->action_id = $action;
                    $advisor_fee->advisor_fee_amount = $amount;
                    if (!$advisor_fee->save()) {
                        echo Json::encode($advisor_fee->errors);
                        exit();
                    }
                }
            }
        }
    }

    private function advisor_fee()
    {
        $this->advisor_fee_insert([4, 5], [1, 2], [
            1 => 5000,
            2 => 8400
        ]);
        $this->advisor_fee_insert([4, 5], [3], [
            1 => 3500,
            2 => 7000
        ]);
        $this->advisor_fee_insert([4, 5], [4, 5], [
            1 => 20000,
            3 => 84000
        ]);
        $this->advisor_fee_insert([4, 5], [6, 7], [
            1 => 15000,
            3 => 72000
        ]);
        $this->advisor_fee_insert([2], [1, 2], [
            1 => 4000,
            3 => 6000
        ]);
        $this->advisor_fee_insert([2], [4, 5], [
            1 => 3000,
            3 => 3500
        ]);
        $this->advisor_fee_insert([2], [6, 7], [
            1 => 4000,
            3 => 6000
        ]);
    }

    private function request_fee_insert($branch, $actions, $plans)
    {
        foreach ($actions as $action) {
            foreach ($plans as $plan => $amount) {
                $request_fee = new EgsRequestFee();
                $request_fee->plan_id = $plan;
                $request_fee->branch_id = $branch;
                $request_fee->action_id = $action;
                $request_fee->request_fee_amount = $amount;
                if (!$request_fee->save()) {
                    return Json::encode($request_fee->errors);
                    exit();
                }
            }
        }
    }

    private function request_fee()
    {
        $this->request_fee_insert(1, [4, 5], [
            1 => 500,
            2 => 500,
            3 => 500
        ]);
        $this->request_fee_insert(2, [4, 5], [
            1 => 0,
            2 => 5000,
            3 => 4000
        ]);
        $this->request_fee_insert(1, [10], [
            1 => 2000,
            2 => 2000,
            3 => 2000
        ]);
        $this->request_fee_insert(2, [10], [
            1 => 2000,
            2 => 2000,
            3 => 2000
        ]);
        $this->request_fee_insert(1, [4, 5], [
            4 => 500,
            5 => 500,
            6 => 500,
            7 => 500
        ]);
        $this->request_fee_insert(3, [4, 5], [
            4 => 3000,
            5 => 3000,
            6 => 3000,
            7 => 3000
        ]);
        $this->request_fee_insert(1, [10], [
            4 => 500,
            5 => 500,
            6 => 500,
            7 => 500
        ]);
        $this->request_fee_insert(3, [10], [
            4 => 1500,
            5 => 1500,
            6 => 1500,
            7 => 1500
        ]);
    }

    private function load()
    {
        $load = new EgsLoad();
        $load->plan_type_id = 1;
        $load->load_amount = 3;
        $load->save();
        $load = new EgsLoad();
        $load->plan_type_id = 2;
        $load->load_amount = 1;
        $load->save();
    }

    private function committee_fee_insert($level_id, $branch_id, $plan_type_id, $fees)
    {
        foreach ($fees as $action_id => $committee_fee_amount) {
            $committee_fee = new EgsCommitteeFee();
            $committee_fee->level_id = $level_id;
            $committee_fee->branch_id = $branch_id;
            $committee_fee->plan_type_id = $plan_type_id;
            $committee_fee->action_id = $action_id;
            $committee_fee->committee_fee_amount = $committee_fee_amount;
            if (!$committee_fee->save()) {
                echo Json::encode($committee_fee->errors);
                exit();
            }
        }
    }

    private function committee_fee()
    {
        $this->committee_fee_insert(1, 1, 1, [
            3 => 3000,
            6 => 4000,
            7 => 4000,
            11 => 5000,
            12 => 0
        ]);
        $this->committee_fee_insert(1, 1, 2, [
            3 => 3000,
            6 => 3500,
            7 => 3500,
            11 => 5000,
            12 => 0
        ]);
        $this->committee_fee_insert(1, 2, 1, [
            3 => 3000,
            6 => 5000,
            7 => 5000,
            11 => 5000,
            12 => 0
        ]);
        $this->committee_fee_insert(1, 2, 2, [
            3 => 3000,
            6 => 3500,
            7 => 3500,
            11 => 5000,
            12 => 0
        ]);
        $this->committee_fee_insert(2, 1, 1, [
            3 => 4000,
            6 => 5000,
            7 => 5000,
            11 => 3500
        ]);
        $this->committee_fee_insert(2, 3, 1, [
            3 => 6000,
            6 => 10000,
            7 => 10000,
            11 => 5000
        ]);
    }
}