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
use app\modules\egs\models\EgsEvaluation;
use app\modules\egs\models\EgsEvaluationTopic;
use app\modules\egs\models\EgsEvaluationTopicGroup;
use app\modules\egs\models\EgsLevel;
use app\modules\egs\models\EgsLoad;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsPlanType;
use app\modules\egs\models\EgsPosition;
use app\modules\egs\models\EgsPositionType;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingComponent;
use app\modules\egs\models\EgsPrintingType;
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
use app\modules\egs\models\EgsUserEvaluation;
use app\modules\egs\models\EgsUserEvaluationRate;
use app\modules\egs\models\EgsUserRequest;
use yii\helpers\Json;

class Evaluation
{
    public function delete()
    {
        EgsUserEvaluationRate::deleteAll();
        EgsUserEvaluation::deleteAll();
        EgsEvaluationTopic::deleteAll();
        EgsEvaluationTopic::getDb()->createCommand('ALTER TABLE ' . EgsEvaluationTopic::tableName() . ' AUTO_INCREMENT = 1')->execute();
        EgsEvaluationTopicGroup::deleteAll();
        EgsEvaluationTopic::getDb()->createCommand('ALTER TABLE ' . EgsEvaluationTopicGroup::tableName() . ' AUTO_INCREMENT = 1')->execute();
        EgsEvaluation::deleteAll();
        EgsEvaluation::getDb()->createCommand('ALTER TABLE ' . EgsEvaluation::tableName() . ' AUTO_INCREMENT = 1')->execute();
    }

    public function insert()
    {
    }
}