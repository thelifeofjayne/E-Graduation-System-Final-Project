<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionType;
use app\modules\egs\models\EgsAdvisor;
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
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsLoad;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsPlanType;
use app\modules\egs\models\EgsPosition;
use app\modules\egs\models\EgsPositionType;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingComponent;
use app\modules\egs\models\EgsPrintingType;
use app\modules\egs\models\EgsProgram;
use app\modules\egs\models\EgsProgramBinder;
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

class Binder
{
    public function delete()
    {
        EgsLevelBinder::deleteAll();
        EgsLevel::deleteAll();
        EgsProgramBinder::deleteAll();
        EgsProgram::deleteAll();
        EgsBranchBinder::deleteAll();
        EgsBranch::deleteAll();
        EgsPlanBinder::deleteAll();
        EgsPlan::deleteAll();
        EgsPlanType::deleteAll();
    }

    public function insert()
    {
        $this->level();
        $this->level_binder();
        $this->program();
        $this->program_binder();
        $this->branch();
        $this->branch_binder();
        $this->plan_type();
        $this->plan();
        $this->plan_binder();
    }

    private function level_binder()
    {
        $level_id = 1;
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '510208113160';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '510265460';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '540263460';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '540264460';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '550208122159';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '550208123154';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '550208132154';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '550208133047';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);

        $level_id = 2;
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '710208112055';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '710208211051';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
        $level_binder = new EgsLevelBinder();
        $level_binder->level_id = $level_id;
        $level_binder->reg_program_id = '770208211051';
        if (!$level_binder->save()) return Json::encode($level_binder->errors);
    }

    private function level()
    {
        $level = new EgsLevel();
        $level->level_id = 1;
        $level->level_name_th = 'ปริญญาโท';
        $level->level_name_en = 'Master\'s degree';
        if (!$level->save()) return Json::encode($level->errors);
        $level = new EgsLevel();
        $level->level_id = 2;
        $level->level_name_th = 'ปริญญาเอก';
        $level->level_name_en = 'Doctor\'s degree';
        if (!$level->save()) return Json::encode($level->errors);
    }

    private function program()
    {
        $program = new EgsProgram();
        $program->program_id = 1;
        $program->program_name_th = 'วิทยาการคอมพิวเตอร์';
        $program->program_name_en = 'Computer Science';
        $program->save();
        $program = new EgsProgram();
        $program->program_id = 2;
        $program->program_name_th = 'เทคโนโลยีสารสนเทศ';
        $program->program_name_en = 'Information Technology';
        $program->save();
        $program = new EgsProgram();
        $program->program_id = 3;
        $program->program_name_th = 'การรับรู้จากระยะไกลและระบบสารสนเทศภูมิศาสตร์';
        $program->program_name_en = 'Geographic Information System';
        $program->save();
    }

    private function program_binder()
    {
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '510208113160';
        $program_binder->program_id = 2;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '510265460';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '540263460';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '540264460';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '550208122159';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '550208123154';
        $program_binder->program_id = 2;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '550208132154';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '550208133047';
        $program_binder->program_id = 2;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '710208112055';
        $program_binder->program_id = 2;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '710208211051';
        $program_binder->program_id = 1;
        $program_binder->save();
        $program_binder = new EgsProgramBinder();
        $program_binder->reg_program_id = '770208211051';
        $program_binder->program_id = 1;
        $program_binder->save();
    }

    private function branch()
    {
        $branch = new EgsBranch();
        $branch->branch_id = 1;
        $branch->branch_name_th = 'ภาคปกติ';
        $branch->branch_name_en = 'LEG';
        if (!$branch->save()) return Json::encode($branch->errors);
        $branch = new EgsBranch();
        $branch->branch_id = 2;
        $branch->branch_name_th = 'ภาคพิเศษ';
        $branch->branch_name_en = 'SPE';
        if (!$branch->save()) return Json::encode($branch->errors);
        $branch = new EgsBranch();
        $branch->branch_id = 3;
        $branch->branch_name_th = 'หลักสูตรภาษาอังกฤษ';
        $branch->branch_name_en = 'ENG';
        if (!$branch->save()) return Json::encode($branch->errors);
    }

    private function branch_binder()
    {
        $branch_binder_id = 1;
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '510208113160';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '510265460';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '550208122159';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '550208123154';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '550208132154';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '550208133047';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '710208112055';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '710208211051';
        if (!$branch_binder->save()) {
            echo Json::encode($branch_binder->errors);
            exit();
        }
        $branch_binder_id = 2;
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '540263460';
        if (!$branch_binder->save()) return Json::encode($branch_binder->errors);
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '540264460';
        if (!$branch_binder->save()) return Json::encode($branch_binder->errors);

        $branch_binder_id = 3;
        $branch_binder = new EgsBranchBinder();
        $branch_binder->branch_id = $branch_binder_id;
        $branch_binder->reg_program_id = '770208211051';
        if (!$branch_binder->save()) return Json::encode($branch_binder->errors);
    }

    private function plan_binder()
    {
        $plan_id = 1;
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '510208113160';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '510265460';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);

        $plan_id = 2;
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '540263460';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '550208122159';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '550208123154';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);

        $plan_id = 3;
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '540264460';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '550208132154';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '550208133047';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);

        $plan_id = 4;
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '710208112055';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '710208211051';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
        $plan_binder = new EgsPlanBinder();
        $plan_binder->plan_id = $plan_id;
        $plan_binder->reg_program_id = '770208211051';
        if (!$plan_binder->save()) return Json::encode($plan_binder->errors);
    }

    private function plan()
    {
        $plan = new EgsPlan();
        $plan->plan_id = 1;
        $plan->plan_name_th = 'แผน ก 1';
        $plan->plan_name_en = 'PLAN A 1';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 2;
        $plan->plan_name_th = 'แผน ก 2';
        $plan->plan_name_en = 'PLAN A 2';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 3;
        $plan->plan_name_th = 'แผน ข';
        $plan->plan_name_en = 'PLAN B';
        $plan->plan_type_id = 2;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 4;
        $plan->plan_name_th = 'แบบ 1.1';
        $plan->plan_name_en = 'TYPE 1.1';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 5;
        $plan->plan_name_th = 'แบบ 1.2';
        $plan->plan_name_en = 'TYPE 1.2';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 6;
        $plan->plan_name_th = 'แบบ 2.1';
        $plan->plan_name_en = 'TYPE 2.1';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
        $plan = new EgsPlan();
        $plan->plan_id = 7;
        $plan->plan_name_th = 'แบบ 2.2';
        $plan->plan_name_en = 'TYPE 2.2';
        $plan->plan_type_id = 1;
        if (!$plan->save()) return Json::encode($plan->errors);
    }

    private function plan_type()
    {
        $plan_type = new EgsPlanType();
        $plan_type->plan_type_id = 1;
        $plan_type->plan_type_name_th = 'วิทยานิพนธ์';
        $plan_type->plan_type_name_en = 'THESIS';
        if (!$plan_type->save()) return Json::encode($plan_type->errors);
        $plan_type = new EgsPlanType();
        $plan_type->plan_type_id = 2;
        $plan_type->plan_type_name_th = 'การศึกษาอิสระ';
        $plan_type->plan_type_name_en = 'INDEPENDENT STUDY';
        if (!$plan_type->save()) return Json::encode($plan_type->errors);
    }

}