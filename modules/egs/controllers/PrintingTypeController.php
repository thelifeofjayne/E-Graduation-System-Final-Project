<?php

namespace app\modules\egs\controllers;


use app\modules\egs\models\EgsCalendar;
use app\modules\egs\models\EgsPrinting;
use app\modules\egs\models\EgsPrintingComponent;
use app\modules\egs\models\EgsPrintingType;
use Yii;

use yii\base\Module;
use yii\helpers\Json;
use yii\web\Controller;


class PrintingTypeController extends Controller
{

    public function actionGetReview()
    {
        $printing_type = EgsPrintingType::find()->all();
        return Json::encode(Format::printing_type($printing_type));
    }
}
