<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsAction;
use app\modules\egs\models\EgsActionBypass;
use app\modules\egs\models\EgsActionFor;
use app\modules\egs\models\EgsActionItem;
use app\modules\egs\models\EgsActionOnStatus;
use app\modules\egs\models\EgsActionStep;
use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsBranch;
use app\modules\egs\models\EgsBranchBinder;
use app\modules\egs\models\EgsCalendarItem;
use app\modules\egs\models\EgsCalendarLevel;
use app\modules\egs\models\EgsCommittee;
use app\modules\egs\models\EgsDefense;
use app\modules\egs\models\EgsDefenseAdvisor;
use app\modules\egs\models\EgsDefenseDocument;
use app\modules\egs\models\EgsDefenseSubject;
use app\modules\egs\models\EgsDocument;
use app\modules\egs\models\EgsDocumentType;
use app\modules\egs\models\EgsEvaluation;
use app\modules\egs\models\EgsEvaluationTopicGroup;
use app\modules\egs\models\EgsLevelBinder;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsPlanType;
use app\modules\egs\models\EgsPosition;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingType;
use app\modules\egs\models\EgsProgram;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsRequestDefense;
use app\modules\egs\models\EgsRequestDocument;
use app\modules\egs\models\EgsRequestInit;
use app\modules\egs\models\EgsRoom;
use app\modules\egs\models\EgsSemester;
use app\modules\egs\models\EgsStatus;
use app\modules\egs\models\EgsStep;
use app\modules\egs\models\EgsSubmitType;
use app\modules\egs\models\EgsTodo;
use app\modules\egs\models\EgsUserEvaluation;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\ArrayHelper;

class Format
{

    static public function action_bypass($action_bypasses)
    {
        return ArrayHelper::toArray($action_bypasses, ['app\modules\egs\models\EgsActionBypass' => [
            'action_id',
            'semester_id',
            'student' => function ($action_bypass) {
                /* @var $action_bypass EgsActionBypass */
                $person = Config::get_one_user($action_bypass->student_id);
                return Format::personNameOnly($person);
            }
        ]]);
    }

    static public function branch($branchs)
    {
        return ArrayHelper::toArray($branchs, ['app\modules\egs\models\EgsBranch' => [
            'branch_id',
            'branch_name' => function ($branch) {
                /* @var $branch EgsBranch */
                return $branch['branch_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function plan($plans)
    {
        return ArrayHelper::toArray($plans, ['app\modules\egs\models\EgsPlan' => [
            'plan_id',
            'plan_name' => function ($plan) {
                /* @var $plan EgsPlan */
                return $plan['plan_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function plan_type($plan_types)
    {
        return ArrayHelper::toArray($plan_types, ['app\modules\egs\models\EgsPlanType' => [
            'plan_type_id',
            'plan_type_name' => function ($plan_type) {
                /* @var $plan_type EgsPlanType */
                return $plan_type['plan_type_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function program($programs)
    {
        return ArrayHelper::toArray($programs, ['app\modules\egs\models\EgsProgram' => [
            'program_id',
            'program_name' => function ($program) {
                /* @var $program EgsProgram */
                return $program['program_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function defense_advisor($defense_advisors)
    {
        return ArrayHelper::toArray($defense_advisors, ['app\modules\egs\models\EgsDefenseAdvisor' => [
            'teacher' => function ($defense_advisor) {
                /* @var $defense_advisor EgsDefenseAdvisor */
                return Format::personNameOnly(Config::get_one_user($defense_advisor->teacher_id));
            },
            'advisor_fee_amount'
        ]]);
    }

    static public function defense_print($defenses)
    {
        return ArrayHelper::toArray($defenses, ['app\modules\egs\models\EgsDefense' => [
            'student' => function ($defense) {
                /* @var $defense EgsDefense */
                $user = Config::get_one_user($defense->student_id);
                $reg_program_id = $user['program_id'];
                $level = EgsLevelBinder::find()->where(['reg_program_id' => $reg_program_id])->one()->level;
                $branch = EgsBranchBinder::find()->where(['reg_program_id' => $reg_program_id])->one()->branch;
                $plan = EgsPlanBinder::find()->where(['reg_program_id' => $reg_program_id])->one()->plan;
                $plan_type = $plan->planType;
                $program = EgsProgramBinder::find()->where(['reg_program_id' => $reg_program_id])->one()->program;
                return [
                    'person' => Format::personNameOnly($user),
                    'level' => Format::level($level),
                    'branch' => Format::branch($branch),
                    'program' => Format::program($program),
                    'plan' => Format::plan($plan),
                    'plan_type' => Format::plan_type($plan_type)
                ];
            },
            'defense_date',
            'defense_time_start',
            'defense_time_end',
            'defense_type' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::action($defense->defenseType);
            },
            'room' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::room($defense->room);
            },
            'committees' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::committee($defense->egsCommittees);
            },
            'defense_advisor' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::defense_advisor($defense->egsDefenseAdvisors);
            },
            'project' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::project($defense->project);
            }
        ]]);
    }

    static public function user_evaluation_full($user_evaluations)
    {
        return ArrayHelper::toArray($user_evaluations, ['app\modules\egs\models\EgsUserEvaluation' => [
            'evaluation_id',
            'student' => function ($user_evaluation) {
                /* @var $user_evaluation EgsUserEvaluation */
                return Format::personNameOnly(Config::get_one_user($user_evaluation->student_id));
            },
            'user_evaluation_data',
            'user_evaluation_rate' => function ($user_evaluation) {
                /* @var $user_evaluation EgsUserEvaluation */
                return $user_evaluation->egsUserEvaluationRates;
            }
        ]]);
    }

    static public function user_evaluation($user_evaluations)
    {
        return ArrayHelper::toArray($user_evaluations, ['app\modules\egs\models\EgsUserEvaluation' => [
            'evaluation_id',
            'evaluation' => function ($user_evaluation) {
                /* @var $user_evaluation EgsUserEvaluation */
                return Format::evaluation($user_evaluation->evaluation);
            },
            'student' => function ($user_evaluation) {
                /* @var $user_evaluation EgsUserEvaluation */
                return Format::personNameOnly(Config::get_one_user($user_evaluation->student_id));
            },
            'user_evaluation_data'
        ]]);
    }

    static public function evaluation($evaluations)
    {
        return ArrayHelper::toArray($evaluations, ['app\modules\egs\models\EgsEvaluation' => [
            'evaluation_id',
            'evaluation_name' => function ($evaluation) {
                /* @var $evaluation EgsEvaluation */
                return $evaluation['evaluation_name_' . Config::get_language()];
            },
            'evaluation_active'
        ]]);
    }

    static public function evaluationFull($evaluations)
    {
        return ArrayHelper::toArray($evaluations, ['app\modules\egs\models\EgsEvaluation' => [
            'evaluation_id',
            'evaluation_name' => function ($evaluation) {
                /* @var $evaluation EgsEvaluation */
                return $evaluation['evaluation_name_' . Config::get_language()];
            },
            'evaluation_topic_group' => function ($evaluation) {
                /* @var $evaluation EgsEvaluation */
                return Format::evaluationTopicGroup($evaluation->egsEvaluationTopicGroups);
            }
        ]]);
    }

    static public function evaluationTopicGroup($evaluation_topic_groups)
    {
        return ArrayHelper::toArray($evaluation_topic_groups, ['app\modules\egs\models\EgsEvaluationTopicGroup' => [
            'evaluation_topic_group_id',
            'evaluation_topic_group_name' => function ($evaluation_topic_group) {
                /* @var $evaluation_topic_group EgsEvaluationTopicGroup */
                return $evaluation_topic_group['evaluation_topic_group_name_' . Config::get_language()];
            },
            'evaluation_topic' => function ($evaluation_topic_group) {
                /* @var $evaluation_topic_group EgsEvaluationTopicGroup */
                return Format::evaluationTopic($evaluation_topic_group->egsEvaluationTopics);
            }
        ]]);
    }

    static public function evaluationTopic($evaluation_topics)
    {
        return ArrayHelper::toArray($evaluation_topics, ['app\modules\egs\models\EgsEvaluationTopic' => [
            'evaluation_topic_id',
            'evaluation_topic_name' => function ($evaluation_topic) {
                /* @var $evaluation_topic EgsEvaluationTopic */
                return $evaluation_topic['evaluation_topic_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function todo($todos)
    {
        return ArrayHelper::toArray($todos, ['app\modules\egs\models\EgsTodo' => [
            'todo_id',
            'todo_name' => function ($todo) {
                /* @var $todo EgsTodo */
                return $todo['todo_name_' . Config::get_language()];
            },
            'pass' => function ($todo) {
                /* @var $todo EgsTodo */
                $validation = $todo->todo_validation;
                return Validation::$validation();
            }
        ]]);
    }

    static public function action_step($action_steps)
    {
        return ArrayHelper::toArray($action_steps, ['app\modules\egs\models\EgsActionStep' => [
            'step' => function ($action_step) {
                /* @var $action_step EgsActionStep */
                return Format::step($action_step->step);
            },
            'action_step_index'
        ]]);
    }

    static public function step($steps)
    {
        return ArrayHelper::toArray($steps, ['app\modules\egs\models\EgsStep' => [
            'step_id',
            'step_name' => function ($step) {
                /* @var $step EgsStep */
                return $step['step_name_' . Config::get_language()];
            },
            'step_component',
            'step_icon',
        ]]);
    }

//    static public function defenseTeacherEvent($datas)
//    {
//        /* @var $defense EgsDefense */
//        $events = [];
//        foreach ($datas as $temp) {
//            $defense = $temp['defense'];
//            $teacher = $temp['teacher'];
//            $event = [];
//            $event['type'] = 'teacher';
//            $event['backgroundColor'] = '#D68231';
//            $event['borderColor'] = '#D68231';
//            $event['title'] = $teacher['prefix'] . ' ' . $teacher['person_fname'] . $teacher['person_lname'];
//            $event['start'] = $defense->defense_date . 'T' . $defense->defense_time_start;
//            $event['end'] = $defense->defense_date . 'T' . $defense->defense_time_end;
//            $event['room'] = $defense->room_id;
//            array_push($events, $event);
//        }
//        return $events;
//    }

    static public function defenseEvent($defenses, $teachers)
    {
        /* @var $defense EgsDefense */
        $user = Config::get_current_user();
        $user_id = (int)$user['user_type_id'] === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : (int)$user['id'];
        $events = [];
        foreach ($defenses as $defense) {
            $defense_have_teacher = [];
            foreach ($defense->egsCommittees as $committee) {
                foreach ($teachers as $teacher) {
                    if ($committee->teacher_id === $teacher['id']) {
                        array_push($defense_have_teacher, $teacher['person_fname']);
                    }
                }
            }
            $event = [];
            $event['type'] = $defense->student_id === $user_id ? 'current' : 'defense';
            $event['className'] = $defense->student_id === $user_id ? 'selected-current' : 'selected-defense';
            $event['title'] = $defense->project_id === null ? $defense->defenseType['action_name_' . Config::get_language()] : $defense->project->project_name_th . ' (' . $defense->project->project_name_en . ')';
            $event['start'] = $defense->defense_date . 'T' . $defense->defense_time_start;
            $event['end'] = $defense->defense_date . 'T' . $defense->defense_time_end;
            $event['teacher'] = $defense->student_id === $user_id ? [] : $defense_have_teacher;
            $event['room'] = $defense->room_id;
            array_push($events, $event);
        }
        return $events;
    }

    static public function room($rooms)
    {
        return ArrayHelper::toArray($rooms, ['app\modules\egs\models\EgsRoom' => [
            'room_id',
            'room_name' => function ($room) {
                /* @var $room EgsRoom */
                return $room['room_name_' . Config::get_language()];
            }
        ]]);
    }

    static private function setPerson($person)
    {
        $person_ = [
            'id' => (int)$person['id'],
            'prefix' => $person['prefix_' . Config::get_language()],
            'student_fname' => $person['student_fname_' . Config::get_language()],
            'student_lname' => $person['student_lname_' . Config::get_language()],
            'person_fname' => $person['person_fname_' . Config::get_language()],
            'person_lname' => $person['person_lname_' . Config::get_language()],
            'user_type_id' => (int)$person['user_type_id'],
            'user_id' => $person['user_id']
        ];
        return $person_;
    }

    static public function personNameOnly($persons, $load = false)
    {
        $new_persons = [];
        if (array_key_exists(0, $persons)) {
            foreach ($persons as $person) {
                $new_person = Format::setPerson($person);
                if ($load) {
                    $loaded = 0;
                    /* TODO : STUDENT WHO SILL IN SYSTEM */
                    $advisors = EgsAdvisor::find()->where(['teacher_id' => $new_person['id']])->all();
                    foreach ($advisors as $advisor) {
                        $loaded += $advisor->load->load_amount;
                    }
                    /* TODO : MAX LOADED CONDITIONALLY TO ACADEMIC POSITION */
                    $max = 6;
                    $new_person['load'] = $loaded >= $max;
                }
                array_push($new_persons, $new_person);
            }
        } else {
            $new_persons = Format::setPerson($persons);
        }
        return $new_persons;
    }

    static public function calendarItem($calendar_items)
    {
        return ArrayHelper::toArray($calendar_items, ['app\modules\egs\models\EgsCalendarItem' => [
            'calendar_id',
            'level_id',
            'semester_id',
            'action_id',
            'calendar_item_date_start',
            'calendar_item_date_end',
            'owner_id'
        ]]);
    }

    static private $plan_id;
    static private $program_id;
    static private $user_type_id;

    static public function calendarItemFull($calendar_items, $user_type_id, $plan_id, $program_id)
    {
        Format::$plan_id = $plan_id;
        Format::$program_id = $program_id;
        Format::$user_type_id = (int)$user_type_id;
        $pattern = [
            'calendar' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                return $calendar_item->calendar;
            },
            'level' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                return Format::level($calendar_item->semester->level);
            },
            'semester' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                return Format::semester($calendar_item->semester->semester);
            },
            'action' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                return Format::action($calendar_item->semester->action);
            },
            'request_defense' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                $request_defenses = $calendar_item->semester->action->egsRequestDefenses0;
                $calendar_items = [];
                foreach ($request_defenses as $request_defense) {
                    $action_for = EgsActionFor::findOne([
                        'action_id' => $request_defense->defense_type_id,
                        'program_id' => Format::$program_id,
                        'plan_id' => Format::$plan_id
                    ]);
                    if (!empty($action_for) || Format::$user_type_id === Config::$PERSON_STAFF_TYPE) {
                        $calendar_item_temp = EgsCalendarItem::findOne([
                            'calendar_id' => $calendar_item->calendar_id,
                            'action_id' => $request_defense->defense_type_id,
                            'level_id' => $calendar_item->level_id,
                            'semester_id' => $calendar_item->semester_id,
                            'owner_id' => $calendar_item->owner_id
                        ]);
                        array_push($calendar_items, $calendar_item_temp);
                    }
                }
                return $calendar_items;
            },
            'owner_id',
            'user_request' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                $student_id = Format::$user_type_id === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : Config::get_user_id();
                $request_init = EgsRequestInit::find()->where(['request_type_id' => $calendar_item->action_id])->one();
                $user_request = EgsUserRequest::findOne([
                    'student_id' => empty($request_init) ? $student_id : Config::$SYSTEM_ID,
                    'calendar_id' => $calendar_item->calendar_id,
                    'action_id' => empty($request_init) ? $calendar_item->action_id : $request_init->init_type_id,
                    'level_id' => $calendar_item->level_id,
                    'semester_id' => $calendar_item->semester_id,
                    'owner_id' => $calendar_item->owner_id
                ]);
                if (empty($user_request)) return null;
                return Format::userRequest($user_request);
            },
            'ex_committee' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                $student_id = Format::$user_type_id === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : Config::get_user_id();
                $defense = EgsDefense::find()->where([
                    'student_id' => $student_id,
                    'calendar_id' => $calendar_item->calendar_id,
                    'level_id' => $calendar_item->level_id,
                    'semester_id' => $calendar_item->semester_id,
                    'owner_id' => Config::$SYSTEM_ID
                ])
                    ->andWhere(['!=', 'action_id', $calendar_item->action_id])
                    ->andWhere(['!=', 'defense_type_id', Config::$DEFENSE_WIRTE])
                    ->andWhere(['!=', 'defense_type_id', Config::$DEFENSE_ORAL])
                    ->one();
                if (empty($defense)) {
                    return [];
                }
                return $defense->egsCommittees;
            },
            'calendar_item_date_start',
            'calendar_item_date_end',
        ];
        return ArrayHelper::toArray($calendar_items, ['app\modules\egs\models\EgsCalendarItem' => $pattern]);
    }

    static public function document_type($document_types)
    {
        return ArrayHelper::toArray($document_types, ['app\modules\egs\models\EgsDocumentType' => [
            'document_type_id',
            'document_type_name' => function ($document_type) {
                /* @var $document_type EgsDocumentType */
                return $document_type['document_type_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function submit_type($submit_type)
    {
        return ArrayHelper::toArray($submit_type, ['app\modules\egs\models\EgsSubmitType' => [
            'submit_type_id',
            'submit_type_name' => function ($submit_type) {
                /* @var $submit_type EgsSubmitType */
                return $submit_type['submit_type_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function document($documents)
    {
        return ArrayHelper::toArray($documents, ['app\modules\egs\models\EgsDocument' => [
            'document_id',
            'document_name' => function ($document) {
                /* @var $document EgsDocument */
                return $document['document_name_' . Config::get_language()];
            },
            'submit' => function ($document) {
                /* @var $document EgsDocument */
                return Format::submit_type($document->submitType);
            },
        ]]);
    }

    static public function printing_component($printing_components)
    {
        return ArrayHelper::toArray($printing_components, ['app\modules\egs\models\EgsPrintingComponent' => [
            'printing_component_id',
            'printing_value',
        ]]);
    }

    static public function defense_document($defense_documents)
    {
        return ArrayHelper::toArray($defense_documents, ['app\modules\egs\models\EgsDefenseDocument' => [
            'document' => function ($defense_document) {
                /* @var $request_document EgsDefenseDocument */
                return Format::document($defense_document->document);
            },
            'defense_document_path',
            'defense_document_status_id',
        ]]);
    }

    static public function request_document($request_documents)
    {
        return ArrayHelper::toArray($request_documents, ['app\modules\egs\models\EgsRequestDocument' => [
            'document' => function ($request_document) {
                /* @var $request_document EgsRequestDocument */
                return Format::document($request_document->document);
            },
            'request_document_id',
            'request_document_status_id'
        ]]);
    }

    static public function userRequestForListing($user_requests)
    {
        return ArrayHelper::toArray($user_requests, ['app\modules\egs\models\EgsUserRequest' => [
            'student' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return Format::personNameOnly(Config::get_one_user($user_request->student_id));
            },
            'calendar_item' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return $user_request->calendar;
            },
            'advisors' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return Format::advisor($user_request->egsAdvisors);
            },
            'defenses' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return Format::defenseDetail($user_request->egsDefenses);
            },
            'request_fee' => function ($user_request) {
                /*  @var $user_request EgsUserRequest */
                $data['amount'] = $user_request->request_fee;
                $data['fee_status_id'] = $user_request->request_fee_status_id;
                return $data;
            },
            'request_document' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                $request_documents = [];
                foreach ($user_request->egsRequestDocuments as $request_document)
                    if ($request_document->document->submit_type_id === Config::$SUBMIT_TYPE_BEFORE)
                        array_push($request_documents, $request_document);
                return Format::request_document($request_documents);
            },
            'defense_document' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                $defense_documents = [];
                foreach ($user_request->egsDefenses as $defense) {
                    foreach ($defense->egsDefenseDocuments as $defense_document)
                        if ($defense_document->document->submit_type_id === Config::$SUBMIT_TYPE_BEFORE)
                            array_push($defense_documents, $defense_document);
                }
                return Format::defense_document($defense_documents);
            },
            'post_request_document' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                $request_documents = [];
                foreach ($user_request->egsRequestDocuments as $request_document)
                    if ($request_document->document->submit_type_id === Config::$SUBMIT_TYPE_AFTER)
                        array_push($request_documents, $request_document);
                return Format::request_document($request_documents);
            },
            'post_defense_document' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                $defense_documents = [];
                foreach ($user_request->egsDefenses as $defense) {
                    foreach ($defense->egsDefenseDocuments as $defense_document)
                        if ($defense_document->document->submit_type_id === Config::$SUBMIT_TYPE_AFTER)
                            array_push($defense_documents, $defense_document);
                }
                return Format::defense_document($defense_documents);
            },
            'step' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                $action_step = EgsActionStep::find()->where([
                    'step_type_id' => Config::$STEP_TYPE_PROCESS,
                    'action_id' => $user_request->calendar->semester->action_id
                ])->orderBy('action_step_index')->all();
                return Format::action_step($action_step);
            },
            'current_step' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return Validation::current_step($user_request);
            }
        ]]);
    }

    static public function userRequest($user_request)
    {
        return ArrayHelper::toArray($user_request, ['app\modules\egs\models\EgsUserRequest' => [
            'student_id',
            'advisors' => function ($user_request) {
                /*  @var $user_request EgsUserRequest */
                return $user_request->egsAdvisors;
            },
            'defenses' => function ($user_request) {
                /* @var $user_request EgsUserRequest */
                return Format::defense($user_request->egsDefenses);
            }
        ]]);
    }

    static public function advisor($advisor)
    {
        return ArrayHelper::toArray($advisor, ['app\modules\egs\models\EgsAdvisor' => [
            'teacher' => function ($advisor) {
                /* @var $advisor EgsAdvisor */
                return Format::personNameOnly(Config::get_one_user($advisor->teacher_id));
            },
            'position' => function ($advisor) {
                /* @var $advisor EgsAdvisor */
                return Format::position($advisor->position);
            }
        ]]);
    }

    static public function committee($committees)
    {
        return ArrayHelper::toArray($committees, ['app\modules\egs\models\EgsCommittee' => [
            'teacher' => function ($committee) {
                /* @var $committee EgsCommittee */
                return Format::personNameOnly(Config::get_one_user($committee->teacher_id));
            },
            'position' => function ($committee) {
                /* @var $committee EgsCommittee */
                return Format::position($committee->position);
            },
            'fee' => function ($committee) {
                /* @var $committee EgsCommittee */
                return $committee->committee_fee;
            },
        ]]);
    }

    static public function defenseDetail($defenses)
    {
        return ArrayHelper::toArray($defenses, ['app\modules\egs\models\EgsDefense' => [
            'defense_date',
            'defense_time_start',
            'defense_time_end',
            'defense_score',
            'defense_credit',
            'defense_comment',
            'defense_status_id',
            'defense_type_id',
            'defense_cond' => function ($defense) {
                /* @var $defense EgsDefense */
                return $defense->defenseType->action_cond;
            },
            'score' => function ($defense) {
                /* @var $defense EgsDefense */
                return $defense->defenseType->action_score;
            },
            'credit' => function ($defense) {
                /* @var $defense EgsDefense */
                return $defense->defenseType->action_credit;
            },
            'defense_type' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::action($defense->defenseType);
            },
            'room' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::room($defense->room);
            },
            'committees' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::committee($defense->egsCommittees);
            },
            'subject' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::defense_subject($defense->egsDefenseSubjects);
            },
            'project' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::project($defense->project);
            }
        ]]);
    }

    static public function defense_subject($defense_subjects)
    {
        return ArrayHelper::toArray($defense_subjects, ['app\modules\egs\models\EgsDefenseSubject' => [
            'subject_id',
            'subject_name' => function ($defense_subject) {
                /* @var $defense_subject EgsDefenseSubject */
                return $defense_subject->subject['subject_name_' . Config::get_language()];
            },
            'subject_pass',
            'already_passed',
            'defense_subject_status_id'
        ]]);
    }

    static public function status($statuses)
    {
        return ArrayHelper::toArray($statuses, ['app\modules\egs\models\EgsStatus' => [
            'status_id',
            'status_name' => function ($status) {
                /* @var $status EgsStatus */
                return $status['status_name_' . Config::get_language()];
            },
            'status_label' => function ($status) {
                /* @var $status EgsStatus */
                return $status->statusLabel->status_label_name;
            }
        ]]);
    }

    static public function project($projects)
    {
        return ArrayHelper::toArray($projects, ['app\modules\egs\models\EgsProject' => [
            'student_id',
            'project_name_th',
            'project_name_en',
            'project_active'
        ]]);
    }

    static public function defense($defenses)
    {
        return ArrayHelper::toArray($defenses, ['app\modules\egs\models\EgsDefense' => [
            'defense_date',
            'defense_time_start',
            'defense_time_end',
            'defense_type' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::action($defense->defenseType);
            },
            'defense_project' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::project($defense->project);
            },
            'room' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::room($defense->room);
            },
            'committees' => function ($defense) {
                /* @var $defense EgsDefense */
                return $defense->egsCommittees;
            },
            'defense_status' => function ($defense) {
                /* @var $defense EgsDefense */
                return Format::status($defense->defenseStatus);
            }
        ]]);
    }

    static public function calendarItemWithStatus($calendar_items, $user_type_id, $program_id, $plan_id)
    {
        Format::$program_id = $program_id;
        Format::$plan_id = $plan_id;
        Format::$user_type_id = $user_type_id === null ? null : (int)$user_type_id;
        return ArrayHelper::toArray($calendar_items, ['app\modules\egs\models\EgsCalendarItem' => [
            'calendar_id',
            'level_id',
            'semester_id',
            'owner_id',
            'action' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                return Format::action($calendar_item->semester->action);
            },
            'calendar_item_date_start',
            'calendar_item_date_end',
            'calendar_item_added' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                $added = false;
                $user_id = Format::$user_type_id === Config::$PERSON_STAFF_TYPE ? Config::$SYSTEM_ID : Config::get_user_id();
                foreach ($calendar_item->egsUserRequests as $user_request)
                    if ($user_request->student_id === $user_id)
                        $added = true;
                return $added;
            },
            'calendar_item_open' => function ($calendar_item) {
                /* @var $calendar_item EgsCalendarItem */
                if (Format::$user_type_id === Config::$PERSON_STAFF_TYPE) {
                    return ['open' => true];
                }
                $action_for = EgsActionFor::findAll([
                    'action_id' => $calendar_item->action_id,
                    'program_id' => Format::$program_id,
                    'plan_id' => Format::$plan_id
                ]);
                if (empty($action_for)) {
                    return [
                        'open' => false,
                        'status' => Format::status(EgsStatus::findOne(['status_id' => Config::$STATUS_DONT_NEED_TODO]))
                    ];
                } else {
                    $validation = $calendar_item->semester->action->action_validation;
                    $condition = true;
                    if ($validation !== null) {
                        $condition = Validation::$validation($calendar_item);
                    }
                    if (!$condition) {
                        return [
                            'open' => false,
                            'status' => Format::status(EgsStatus::findOne(['status_id' => Config::$STATUS_NOT_CONDITION]))
                        ];
                    } else {
                        $passed = false;
                        $validation = $calendar_item->semester->action->todo->todo_validation;
                        if (Validation::$validation()) {
                            $passed = true;
                        }
                        if ($passed) {
                            return [
                                'open' => false,
                                'status' => Format::status(EgsStatus::findOne(['status_id' => Config::$STATUS_ALREADY_PASSED]))
                            ];
                        } else {
                            $student = Config::get_user_id();
                            $ation_bypass = EgsActionBypass::findOne([
                                'student_id' => $student,
                                'semester_id' => $calendar_item->semester_id,
                                'action_id' => $calendar_item->action_id
                            ]);
                            if (!empty($ation_bypass)) {
                                return ['open' => true];
                            }
                            $today = date('Y-m-d');
                            $calendar_item->calendar_item_date_start;
                            $calendar_item->calendar_item_date_end;
                            return $today >= $calendar_item->calendar_item_date_start &&
                            $today <= $calendar_item->calendar_item_date_end ? ['open' => true] :
                                [
                                    'open' => false,
                                    'status' => Format::status(EgsStatus::findOne(['status_id' => Config::$STATUS_NOT_IN_TIME]))
                                ];
                        }
                    }
                }
            }
        ]]);
    }

    static public function semester($semesters)
    {
        return ArrayHelper::toArray($semesters, ['app\modules\egs\models\EgsSemester' => [
            'semester_id',
            'semester_name' => function ($semester) {
                /* @var EgsSemester $semester */
                return $semester['semester_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function semesterWithActionItem($semesters)
    {
        return ArrayHelper::toArray($semesters, ['app\modules\egs\models\EgsSemester' => [
            'semester_id',
            'semester_name' => function ($semester) {
                /* @var EgsSemester $semester */
                return $semester['semester_name_' . Config::get_language()];
            },
            'action_items' => function ($semester) {
                /* @var EgsSemester $semester */
                return Format::actionItemActionOnly($semester->egsActionItems);
            }
        ]]);
    }

    static public function level($levels)
    {
        return ArrayHelper::toArray($levels, ['app\modules\egs\models\EgsLevel' => [
            'level_id',
            'level_name' => function ($level) {
                /* @var $level EgsCalendarLevel */
                return $level['level_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function actionItemActionOnly($action_items)
    {
        return ArrayHelper::toArray($action_items, ['app\modules\egs\models\EgsActionItem' => [
            'action' => function ($action_item) {
                /* @var $action_item EgsActionItem */
                return Format::action($action_item->action);
            },
            'level_id',
            'semester_id'
        ]]);
    }

    static public function action($actions)
    {
        return ArrayHelper::toArray($actions, ['app\modules\egs\models\EgsAction' => [
            'action_id',
            'action_project',
            'action_type_id',
            'action_name' => function ($action) {
                /* @var $action EgsAction */
                return $action['action_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function position($position)
    {
        return ArrayHelper::toArray($position, ['app\modules\egs\models\EgsPosition' => [
            'position_id',
            'position_minimum',
            'position_maximum',
            'position_name' => function ($position) {
                /* @var $position EgsPosition */

                return $position['position_name_' . Config::get_language()];
            }
        ]]);
    }

    static public function actionItem($action_items)
    {
        return ArrayHelper::toArray($action_items, ['app\modules\egs\models\EgsActionItem' => [
            'action' => function ($action_item) {
                /* @var $action_item EgsActionItem */
                return $action_item->action;
            },
            'level' => function ($action_item) {
                /* @var $action_item EgsActionItem */
                return Format::level($action_item->level);
            },
            'semester' => function ($action_item) {
                /* @var $action_item EgsActionItem */
                return $action_item->semester;
            }
        ]]);
    }
}