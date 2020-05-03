<?php

namespace app\modules\egs\controllers;

use app\modules\egs\models\EgsAdvisor;
use app\modules\egs\models\EgsAdvisorFee;
use app\modules\egs\models\EgsBranchBinder;
use app\modules\egs\models\EgsPlan;
use app\modules\egs\models\EgsPlanBinder;
use app\modules\egs\models\EgsProgramBinder;
use app\modules\egs\models\EgsSubject;
use app\modules\egs\models\EgsSubjectFor;
use Yii;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class RequestController extends Controller
{
    public function actionIndex()
    {
        return $this->render('/default/index');
    }
}
