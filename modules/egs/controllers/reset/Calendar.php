<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\models\EgsActionBypass;
use app\modules\egs\models\EgsActionCompleteStep;
use app\modules\egs\models\EgsActionDocument;
use app\modules\egs\models\EgsActionFor;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionStep;
use app\modules\egs\models\EgsActionSubmitStep;
use app\modules\egs\models\EgsActionType;
use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDocument;
use app\modules\egs\models\EgsDocumentType;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsProgram;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsRequestInit;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsStatus;
use app\modules\egs\models\EgsStatusLabel;
use app\modules\egs\models\EgsStep;
use app\modules\egs\models\EgsStepType;
use app\modules\egs\models\EgsSubject;
use app\modules\egs\models\EgsSubjectFor;
use app\modules\egs\models\EgsSubmitType;
use app\modules\egs\models\EgsTodo;
use app\modules\egs\models\EgsTodoFor;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\Json;

class Calendar
{
    public function delete()
    {
        EgsActionBypass::deleteAll();
        EgsSubjectFor::deleteAll();
        EgsSubject::deleteAll();
        EgsActionStep::deleteAll();
        EgsStep::deleteAll();
        EgsStepType::deleteAll();
        EgsActionFor::deleteAll();
        EgsStatus::deleteAll();
        EgsStatusLabel::deleteAll();
        EgsActionDocument::deleteAll();
        EgsDocument::deleteAll();
        EgsSubmitType::deleteAll();
        EgsCalendarItem::deleteAll();
        EgsCalendar::deleteAll();
        EgsActionItem::deleteAll();
        EgsRequestDefense::deleteAll();
        EgsRequestInit::deleteAll();
        EgsAction::deleteAll();
        EgsActionType::deleteAll();
        EgsSemester::deleteAll();
        EgsTodoFor::deleteAll();
        EgsTodo::deleteAll();
    }

    public function insert()
    {
        $this->todo();
        $this->todo_for();
        $this->status_label();
        $this->status();
        $this->action_type();
        $this->action();
        $this->request_defense();
        $this->request_init();
        $this->semester();
        $this->action_item();
        $this->submit_type();
        $this->document();
        $this->action_document();
        $this->action_for();
        $this->step();
        $this->step_type();
        $this->action_step();
        $this->subject();
        $this->subject_for();
    }

    private function subject_insert($id, $nameTH, $nameEN)
    {
        $subject = new EgsSubject();
        $subject->subject_id = $id;
        $subject->subject_name_th = $nameTH;
        $subject->subject_name_en = $nameEN;
        if (!$subject->save()) {
            echo Json::encode($subject->errors);
            exit();
        }
    }

    private function subject()
    {
        $this->subject_insert(322721, 'Theory', 'Theory');
        $this->subject_insert(322723, 'OS', 'OS');
        $this->subject_insert(322722, 'Algorithm', 'Algorithm');
        $this->subject_insert(322741, 'Com Architecture', 'Com Architecture');

        $this->subject_insert(322724, 'SA', 'SA');
        $this->subject_insert(322731, 'IT&WEB', 'IT&WEB');
        $this->subject_insert(322733, 'DB', 'DB');
        $this->subject_insert(322734, 'ITM', 'ITM');
        $this->subject_insert(322766, 'Com network', 'Com network');

        $this->subject_insert(320761, 'PRINCIPLES OF REMOTE SENSING ', 'PRINCIPLES OF REMOTE SENSING ');
        $this->subject_insert(320781, 'GEOGRAPHIC INFORMATION SYSTEM', 'GEOGRAPHIC INFORMATION SYSTEM');
        $this->subject_insert(320783, 'DATABASE MANAGEMENT SYSTEM AND GEOGRAPHIC INFORMATION SYSTEM STANDARD', 'DATABASE MANAGEMENT SYSTEM AND GEOGRAPHIC INFORMATION SYSTEM STANDARD');
    }

    private function subject_for_insert($actions, $subjects, $programs)
    {
        foreach ($actions as $action) {
            foreach ($subjects as $subject) {
                foreach ($programs as $program) {
                    $subject_for = new EgsSubjectFor();
                    $subject_for->subject_id = $subject;
                    $subject_for->program_id = $program;
                    $subject_for->action_id = $action;
                    if (!$subject_for->save()) {
                        echo Json::encode($subject_for->errors);
                        exit();
                    }
                }
            }
        }
    }

    private function subject_for()
    {
        $this->subject_for_insert([11], [322721, 322723, 322722, 322741], [1]);
        $this->subject_for_insert([11], [322724, 322731, 322733, 322734, 322766], [2]);
        $this->subject_for_insert([11], [320761, 320781, 320783], [3]);
    }

    private function step()
    {
        $step = new EgsStep();
        $step->step_id = 1;
        $step->step_name_th = 'รายละเอียด';
        $step->step_name_en = 'DETAIL[EN]';
        $step->step_component = 'Detail';
        $step->step_icon = 'message';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 2;
        $step->step_name_th = 'เพิ่มข้อมูลอาจารย์';
        $step->step_name_en = 'TEACHER[EN]';
        $step->step_component = 'Teacher';
        $step->step_icon = 'usergroup-add';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 3;
        $step->step_name_th = 'เพิ่มข้อมูลการสอบ';
        $step->step_name_en = 'DEFENSE[EN]';
        $step->step_component = 'Defense';
        $step->step_icon = 'calendar';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 4;
        $step->step_name_th = 'สรุป';
        $step->step_name_en = 'SUMMARY[EN]';
        $step->step_component = 'Summary';
        $step->step_icon = 'profile';
        $step->save();

        $step = new EgsStep();
        $step->step_id = 5;
        $step->step_name_th = 'ส่งคำร้องและจ่ายค่าธรรมเนียม';
        $step->step_name_en = 'PETITION_BEFORE+FEE[EN]';
        $step->step_component = 'PetBefore';
        $step->step_icon = 'file-text';
        $step->step_validation = 'request_document_before_and_fee_all_submit';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 6;
        $step->step_name_th = 'อัพโหลดเอกสารสอบ';
        $step->step_name_en = 'DOC_BEFORE[EN]';
        $step->step_component = 'DocBefore';
        $step->step_icon = 'cloud-upload-o';
        $step->step_validation = 'defense_document_before_all_submit';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 7;
        $step->step_name_th = 'ผลสอบ';
        $step->step_name_en = 'DEFENSE[EN]';
        $step->step_component = 'DefenseResult';
        $step->step_icon = 'line-chart';
        $step->step_validation = 'defense_resulted';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 8;
        $step->step_name_th = 'ส่งคำร้องหลังสอบ';
        $step->step_name_en = 'PETITION_AFTER[EN]';
        $step->step_component = 'PetAfter';
        $step->step_icon = 'file-text';
        $step->step_validation = 'request_document_after_all_submit';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 9;
        $step->step_name_th = 'อัพโหลดเอกสารสอบที่แก้ไขแล้ว';
        $step->step_name_en = 'DOC_AFTER[EN]';
        $step->step_component = 'DocAfter';
        $step->step_icon = 'cloud-upload-o';
        $step->step_validation = 'defense_document_after_all_submit';
        $step->save();
        $step = new EgsStep();
        $step->step_id = 10;
        $step->step_name_th = 'สิ้นสุด';
        $step->step_name_en = 'FINAL[EN]';
        $step->step_component = 'Final';
        $step->step_icon = 'check';
        $step->save();

        $step = new EgsStep();
        $step->step_id = 11;
        $step->step_name_th = 'ประเมินภาควิชา';
        $step->step_name_en = 'EVALUATION-ADD[EN]';
        $step->step_component = 'EvaluationSubmit';
        $step->step_icon = 'area-chart';
        $step->save();

    }

    private function step_type()
    {
        $step_type = new EgsStepType();
        $step_type->step_type_id = 1;
        $step_type->step_type_name_th = 'SUBMIT';
        $step_type->step_type_name_en = 'SUBMIT';
        $step_type->save();
        $step_type = new EgsStepType();
        $step_type->step_type_id = 2;
        $step_type->step_type_name_th = 'PROCESS';
        $step_type->step_type_name_en = 'PROCESS';
        $step_type->save();
    }

    private function action_step_insert($actions, $steps, $setp_type)
    {
        foreach ($actions as $action) {
            foreach ($steps as $index => $step) {
                $action_step = new EgsActionStep();
                $action_step->action_id = $action;
                $action_step->step_id = $step;
                $action_step->step_type_id = $setp_type;
                $action_step->action_step_index = $index;
                if (!$action_step->save()) {
                    echo Json::encode($action_step->errors);
                    exit();
                }

            }
        }
    }

    private function action_step()
    {
        $step_type = 1;
        $this->action_step_insert([1], [1, 2, 4], $step_type);
        $this->action_step_insert([2, 4, 5, 8], [1, 2, 3, 4], $step_type);
        $this->action_step_insert([10], [1, 4], $step_type);
        $this->action_step_insert([13], [2, 3, 4], $step_type);
        $this->action_step_insert([14], [11], $step_type);

        $step_type = 2;
        $this->action_step_insert([1], [5, 10], $step_type);
        $this->action_step_insert([2], [5, 6, 7, 8, 10], $step_type);
        $this->action_step_insert([4, 5], [5, 6, 7, 9, 10], $step_type);
        $this->action_step_insert([8], [5, 6, 7, 10], $step_type);
        $this->action_step_insert([10], [5, 7, 10], $step_type);
    }

    private function action_for_insert($action_id, $plans, $programs)
    {
        foreach ($plans as $plan) {
            foreach ($programs as $program) {
                $action_for = new EgsActionFor();
                $action_for->action_id = $action_id;
                $action_for->plan_id = $plan;
                $action_for->program_id = $program;
                if (!$action_for->save()) {
                    echo Json::encode($action_for->errors);
                    exit();
                }
            }
        }
    }

    private function action_for()
    {
        $this->action_for_insert(1, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(2, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(3, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(4, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(5, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(6, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(7, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(8, [4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(9, [4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(10, [3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(11, [3, 4, 5, 6, 7], [1, 2, 3]);
        $this->action_for_insert(12, [3], [1]);
        $this->action_for_insert(14, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
    }

    private function todo_for_insert($todo_id, $plans, $programs)
    {
        foreach ($plans as $plan) {
            foreach ($programs as $program) {
                $todo_for = new EgsTodoFor();
                $todo_for->todo_id = $todo_id;
                $todo_for->plan_id = $plan;
                $todo_for->program_id = $program;
                if (!$todo_for->save()) {
                    echo Json::encode($todo_for->errors);
                    exit();
                }
            }
        }
    }

    private function todo_for()
    {
        $this->todo_for_insert(1, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(2, [3, 4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(3, [4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(4, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(5, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(6, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
        $this->todo_for_insert(7, [1, 2, 3, 4, 5, 6, 7], [1, 2, 3]);
    }

    private function todo_insert($id, $name_th, $name_en, $validate)
    {
        $todo = new EgsTodo();
        $todo->todo_id = $id;
        $todo->todo_name_th = $name_th;
        $todo->todo_name_en = $name_en;
        $todo->todo_validation = $validate;
        if (!$todo->save()) {
            echo Json::encode($todo->errors);
            exit();
        }
    }

    private function todo()
    {
        $this->todo_insert(1, 'แต่งตั้งอาจารย์ที่ปรึกษาแล้ว', 'แต่งตั้งอาจารย์ที่ปรึกษาแล้ว', 'advisor_added');
        $this->todo_insert(2, 'สอบวัด/ประมวลผ่านครบทุกวิชาแล้ว', 'สอบวัด/ประมวลผ่านครบทุกวิชาแล้ว', 'defense_compre_qe_passed');
        $this->todo_insert(3, 'สอบก้าวหน้าผ่านครบ x หน่วย', 'สอบก้าวหน้าผ่านครบ x หน่วย', 'defense_progress_passed');
        $this->todo_insert(4, 'สอบเค้งโครงผ่าน', 'สอบเค้งโครง่าน', 'defense_proposal_passed');
        $this->todo_insert(5, 'สอบจบผ่าน', 'สอบจบผ่าน', 'defense_final_passed');
        $this->todo_insert(6, 'มีผลงานตีพิมพ์', 'มีผลงานตีพิมพ์', 'publication_submitted');
        $this->todo_insert(7, 'ประเมินหลักสูตร', 'ประเมินหลักสูตร', 'evaluation_submitted');
    }

    private function status()
    {
        $status = new EgsStatus();
        $status->status_id = 1;
        $status->status_name_th = 'ยังไม่ได้สอบ';
        $status->status_name_en = 'Waiting';
        $status->status_label_id = 3;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 2;
        $status->status_name_th = 'สอบไม่ผ่าน';
        $status->status_name_en = 'Not Pass';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 3;
        $status->status_name_th = 'สอบผ่าน';
        $status->status_name_en = 'Pass';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 4;
        $status->status_name_th = 'สอบผ่านแบบมีเงื่อยไข';
        $status->status_name_en = 'Pass Conditionally';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }

        $status = new EgsStatus();
        $status->status_id = 5;
        $status->status_name_th = 'ส่งแล้ว';
        $status->status_name_en = 'Submitted';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 6;
        $status->status_name_th = 'ยังไม่ส่ง';
        $status->status_name_en = 'Not Submitted';
        $status->status_label_id = 3;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 7;
        $status->status_name_th = 'ไม่ต้องส่ง';
        $status->status_name_en = 'No Need';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }

        $status = new EgsStatus();
        $status->status_id = 8;
        $status->status_name_th = 'จ่ายแล้ว';
        $status->status_name_en = 'Paid';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 9;
        $status->status_name_th = 'ยังไม่จ่าย';
        $status->status_name_en = 'Not Pay Yet';
        $status->status_label_id = 3;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 10;
        $status->status_name_th = 'ไม่ต้องจ่าย';
        $status->status_name_en = 'Don\'t Need to Pay';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }

        $status = new EgsStatus();
        $status->status_id = 11;
        $status->status_name_th = 'สอบผ่าน(ครบ)';
        $status->status_name_en = 'Pass(All)';
        $status->status_label_id = 1;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 12;
        $status->status_name_th = 'สอบผ่าน(ยังไม่ครบ)';
        $status->status_name_en = 'Pass(Some)';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 13;
        $status->status_name_th = 'สอบไม่ผ่านเลย';
        $status->status_name_en = 'Fail(XD)';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 14;
        $status->status_name_th = 'สอบผ่านอยู่แล้ว';
        $status->status_name_en = 'Already Passed';
        $status->status_label_id = 3;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }

        $status = new EgsStatus();
        $status->status_id = 15;
        $status->status_name_th = 'ไม่อยู่ในช่วงเวลา';
        $status->status_name_en = 'NOT IN TIME XD';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 16;
        $status->status_name_th = 'ทำผ่านแล้ว';
        $status->status_name_en = 'ALREADY PASSED XD';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 17;
        $status->status_name_th = 'ยังไม่ผ่านเงื่อนไข';
        $status->status_name_en = 'NOT CONDITION XD';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }
        $status = new EgsStatus();
        $status->status_id = 18;
        $status->status_name_th = 'ไม่ต้องทำ';
        $status->status_name_en = 'NOPE XD';
        $status->status_label_id = 2;
        if (!$status->save()) {
            echo Json::encode($status->errors);
            exit();
        }

    }

    private function status_label()
    {
        $status_label = new EgsStatusLabel();
        $status_label->status_label_id = 1;
        $status_label->status_label_name = 'success';
        if (!$status_label->save()) {
            echo Json::encode($status_label->errors);
            exit();
        }
        $status_label = new EgsStatusLabel();
        $status_label->status_label_id = 2;
        $status_label->status_label_name = 'error';
        if (!$status_label->save()) {
            echo Json::encode($status_label->errors);
            exit();
        }
        $status_label = new EgsStatusLabel();
        $status_label->status_label_id = 3;
        $status_label->status_label_name = 'default';
        if (!$status_label->save()) {
            echo Json::encode($status_label->errors);
            exit();
        }
    }

    private function submit_type()
    {
        $submit_type = new EgsSubmitType();
        $submit_type->submit_type_id = 1;
        $submit_type->submit_type_name_th = 'ก่อนสอบ';
        $submit_type->submit_type_name_en = 'BEFORE';
        if (!$submit_type->save()) {
            echo Json::encode($submit_type->errors);
            exit();
        }
        $submit_type = new EgsSubmitType();
        $submit_type->submit_type_id = 2;
        $submit_type->submit_type_name_th = 'หลังสอบ';
        $submit_type->submit_type_name_en = 'AFTER';
        if (!$submit_type->save()) {
            echo Json::encode($submit_type->errors);
            exit();
        }
    }

    private function document()
    {
        $document = new EgsDocument();
        $document->document_id = 1;
        $document->document_name_th = 'บว.21 คำร้องขอเสนอชื่ออาจารย์ที่ปรึกษา/เปลี่ยนแปลงอาจารย์ที่ปรึกษาวิทยานิพนธ์';
        $document->document_name_en = 'GS21 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 2;
        $document->document_name_th = 'วท.บศ.05 แบบเสนอแต่งตั้งคณะกรรมการสอบเค้าโครงวิทยานิพนธ์/การศึกษาอิสระ';
        $document->document_name_en = 'GS05 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 3;
        $document->document_name_th = 'บว.23 แบบเสนอเค้าโครงวิทยานิพนธ์/การศึกษาอิสระ';
        $document->document_name_en = 'GS23 [NAME ENG]';
        $document->submit_type_id = 2;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 4;
        $document->document_name_th = 'บว.26 แบบเสนอแต่งตั้งคณะกรรมการสอบวิทยานิพนธ์/การศึกษาอิสระ';
        $document->document_name_en = 'GS26 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 5;
        $document->document_name_th = 'บว.25 คำร้องขอสอบวิทยานิพนธ์/การศึกษาอิสระ';
        $document->document_name_en = 'GS25 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 6;
        $document->document_name_th = 'แบบเสนอแต่งตั้งคณะกรรมการสอบความก้าวหน้าดุษฎีนิพนธ์';
        $document->document_name_en = 'GS[XX] [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 7;
        $document->document_name_th = 'บว.30 คำร้องขอสอบประมวลความรู้/สอบวัดคุณสมบัติ';
        $document->document_name_en = 'GS30 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 8;
        $document->document_name_th = 'บว.31 แบบเสนอแต่งตั้งคณะกรรมการสอบประมวลความรู้ฯ';
        $document->document_name_en = 'GS31 [NAME ENG]';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 9;
        $document->document_name_th = 'รายงานเค้าโครงงาน';
        $document->document_name_en = 'Proposal\'s Paper';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 10;
        $document->document_name_th = 'เอกสารโครงงาน';
        $document->document_name_en = 'Final\'s Paper';
        $document->submit_type_id = 1;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 11;
        $document->document_name_th = 'เอกสารโครงงานที่แก้ไขแล้ว';
        $document->document_name_en = 'Edited Final\'s Paper';
        $document->submit_type_id = 2;
        $document->save();
        $document = new EgsDocument();
        $document->document_id = 12;
        $document->document_name_th = 'รายงานความก้าวหน้าโครงงาน';
        $document->document_name_en = 'Progress\'s Paper';
        $document->submit_type_id = 1;
        $document->save();
    }

    private function action_document_insert($actions, $documents)
    {
        foreach ($actions as $action) {
            foreach ($documents as $document) {
                $action_document = new EgsActionDocument();
                $action_document->action_id = $action;
                $action_document->document_id = $document;
                if (!$action_document->save()) {
                    echo Json::encode($action_document->errors);
                    exit();
                }
            }
        }
    }

    private function action_document()
    {
        $this->action_document_insert([1], [1]);
        $this->action_document_insert([2], [2, 3]);
        $this->action_document_insert([3], [9]);
        $this->action_document_insert([4, 5], [4, 5]);
        $this->action_document_insert([6, 7], [10, 11]);
        $this->action_document_insert([8], [6]);
        $this->action_document_insert([9], [12]);
        $this->action_document_insert([10], [7, 8]);
        $this->action_document_insert([11, 12], []);
    }

    private function semester()
    {
        $semester = new EgsSemester();
        $semester->semester_id = 1;
        $semester->semester_name_th = 'ภาคต้น';
        $semester->semester_name_en = '1st Semester';
        if (!$semester->save()) return Json::encode($semester->errors);
        $semester = new EgsSemester();
        $semester->semester_id = 2;
        $semester->semester_name_th = 'ภาคปลาย';
        $semester->semester_name_en = '2nd Semester';
        if (!$semester->save()) return Json::encode($semester->errors);
        $semester = new EgsSemester();
        $semester->semester_id = 3;
        $semester->semester_name_th = 'พิเศษ';
        $semester->semester_name_en = 'Summer Semester';
        if (!$semester->save()) return Json::encode($semester->errors);
    }

    private function action_type()
    {
        $action_type = new EgsActionType();
        $action_type->action_type_id = 1;
        $action_type->action_type_name_th = 'ยื่นเสนอ';
        $action_type->actiont_type_name_en = 'Request';
        if (!$action_type->save()) return Json::encode($action_type->errors);
        $action_type = new EgsActionType();
        $action_type->action_type_id = 2;
        $action_type->action_type_name_th = 'สอบ';
        $action_type->actiont_type_name_en = 'Defense';
        if (!$action_type->save()) return Json::encode($action_type->errors);
        $action_type = new EgsActionType();
        $action_type->action_type_id = 3;
        $action_type->action_type_name_th = 'INIT';
        $action_type->actiont_type_name_en = 'INIT';
        if (!$action_type->save()) return Json::encode($action_type->errors);
        $action_type = new EgsActionType();
        $action_type->action_type_id = 4;
        $action_type->action_type_name_th = 'EVALUATION';
        $action_type->actiont_type_name_en = 'EVALUATION';
        if (!$action_type->save()) return Json::encode($action_type->errors);
    }

    private function action()
    {
        $action = new EgsAction();
        $action->action_id = 1;
        $action->action_name_th = 'ขอแต่งตั้งอาจารย์ที่ปรึกษา';
        $action->action_name_en = 'Request for Appointment Advisor';
        $action->action_type_id = 1;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 1;
        $action->action_score = 0;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 2;
        $action->action_name_th = 'ขอสอบเค้าโครงการศึกษาอิสระ/วิทยานิพนธ์';
        $action->action_name_en = 'Request for Defense of Thesis/ Independent Study\'s Proposal';
        $action->action_type_id = 1;
        $action->action_project = 1;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 4;
        $action->action_score = 0;
        $action->action_validation = 'request_proposal';
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 3;
        $action->action_name_th = 'สอบเค้าโครงการศึกษาอิสระ/วิทยานิพนธ์';
        $action->action_name_en = 'Defense of Thesis/Independent Study\'s Proposal';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->action_score = 1;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 4;
        $action->action_name_th = 'ขอสอบการศึกษาอิสระ/วิทยานิพนธ์ (ยื่นขอครั้งที่ 1)';
        $action->action_name_en = 'Request for Defense of Thesis/ Independent Study (1st)';
        $action->action_type_id = 1;
        $action->action_project = 1;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 5;
        $action->action_score = 0;
        $action->action_validation = 'request_final';
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 5;
        $action->action_name_th = 'ขอสอบการศึกษาอิสระ/วิทยานิพนธ์ (ยื่นขอครั้งที่ 2)';
        $action->action_name_en = 'Request for Defense of Thesis/ Independent Study (2nd)';
        $action->action_type_id = 1;
        $action->action_project = 1;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 5;
        $action->action_score = 0;
        $action->action_validation = 'request_final';
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 6;
        $action->action_name_th = 'สอบการศึกษาอิสระ/วิทยานิพนธ์ (ครั้งที่ 1)';
        $action->action_name_en = 'Defense of Thesis/Independent Study (1st)';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 1;
        $action->action_credit = 0;
        $action->action_cond = 1;
        $action->action_score = 1;
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 7;
        $action->action_name_th = 'สอบการศึกษาอิสระ/วิทยานิพนธ์ (ครั้งที่ 2)';
        $action->action_name_en = 'Defense of Thesis/Independent Study (2nd)';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 1;
        $action->action_credit = 0;
        $action->action_cond = 1;
        $action->action_score = 1;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 8;
        $action->action_name_th = 'ขอสอบความก้าวหน้าดุษฎีนิพนธ์';
        $action->action_name_en = 'Request for Taking Dissertation Progressing Examination';
        $action->action_type_id = 1;
        $action->action_project = 1;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 3;
        $action->action_score = 0;
        $action->action_validation = 'request_progress';
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 9;
        $action->action_name_th = 'สอบความก้าวหน้าดุษฎีนิพนธ์';
        $action->action_name_en = 'Dissertation Progressing Examination';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 1;
        $action->action_cond = 0;
        $action->action_score = 1;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 10;
        $action->action_name_th = 'ขอสอบประมวลความรู้/วัดคุณสมบัติ';
        $action->action_name_en = 'Request for Taking Comprehensive/Qualifying Examination';
        $action->action_type_id = 1;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->todo_id = 2;
        $action->action_score = 0;
        $action->action_validation = 'request_compre_qe';
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 11;
        $action->action_name_th = 'สอบประมวลความรู้/วัดคุณสมบัติ (ข้อเขียน)';
        $action->action_name_en = 'Take Comprehensive/Qualifying Examination (Writing)';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->action_score = 0;
        if (!$action->save()) return Json::encode($action->errors);
        $action = new EgsAction();
        $action->action_id = 12;
        $action->action_name_th = 'สอบประมวลความรู้/วัดคุณสมบัติ (ปากเปล่า)';
        $action->action_name_en = 'Take Comprehensive/Qualifying Examination (Oral)';
        $action->action_type_id = 2;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->action_score = 0;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 13;
        $action->action_name_th = 'ตั้งค่าเริ่มต้นสอบประมวลความรู้/วัดคุณสมบัติ';
        $action->action_name_en = 'Request for Taking Comprehensive/Qualifying Examination';
        $action->action_type_id = 3;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->action_score = 0;
        if (!$action->save()) return Json::encode($action->errors);

        $action = new EgsAction();
        $action->action_id = 14;
        $action->action_name_th = 'ประเมินภาควิชา';
        $action->action_name_en = 'EVALUATION';
        $action->action_type_id = 4;
        $action->action_project = 0;
        $action->action_redo = 0;
        $action->action_credit = 0;
        $action->action_cond = 0;
        $action->action_score = 0;
        $action->todo_id = 7;
        if (!$action->save()) return Json::encode($action->errors);
    }

    private function request_init()
    {
        $request_init = new EgsRequestInit();
        $request_init->request_type_id = 10;
        $request_init->init_type_id = 13;
        if (!$request_init->save()) return Json::encode($request_init->errors);
    }

    private function request_defense()
    {
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 2;
        $request_defense->defense_type_id = 3;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 4;
        $request_defense->defense_type_id = 6;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 5;
        $request_defense->defense_type_id = 7;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 8;
        $request_defense->defense_type_id = 9;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 10;
        $request_defense->defense_type_id = 11;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 10;
        $request_defense->defense_type_id = 12;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 13;
        $request_defense->defense_type_id = 11;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
        $request_defense = new EgsRequestDefense();
        $request_defense->request_type_id = 13;
        $request_defense->defense_type_id = 12;
        if (!$request_defense->save()) return Json::encode($request_defense->errors);
    }

    private function action_item()
    {
        $this->insert_action_item(1, 1, [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13,
            14
        ]);
        $this->insert_action_item(1, 2, [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13,
            14
        ]);
        $this->insert_action_item(1, 3, [
            10, 11, 12, 13,
            14
        ]);
        $this->insert_action_item(2, 1, [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13,
            14
        ]);
        $this->insert_action_item(2, 2, [
            1,
            2, 3,
            4, 5, 6, 7,
            8, 9,
            10, 11, 12, 13,
            14
        ]);
        $this->insert_action_item(2, 3, [
            10, 11, 12, 13,
            14
        ]);
    }

    private function insert_action_item($level, $semester, $actions)
    {
        foreach ($actions as $action) {
            $action_item = new EgsActionItem();
            $action_item->action_id = $action;
            $action_item->semester_id = $semester;
            $action_item->level_id = $level;
            if (!$action_item->save()) return Json::encode($action_item->errors);
        }
    }

}