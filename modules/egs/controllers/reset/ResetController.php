<?php

namespace app\modules\egs\controllers\reset;

use app\modules\egs\Module;
use yii\helpers\Url;
use yii\web\Controller;

class ResetController extends Controller
{

    private $calendar, $request, $fee, $dummy, $evaluation, $binder;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->calendar = new Calendar();
        $this->request = new Request();
        $this->fee = new Fee();
        $this->dummy = new Dummy();
        $this->evaluation = new Evaluation();
        $this->binder = new Binder();
    }

    public function actionIndex()
    {
        $this->delete();
        $this->insert();
        $this->quote();
    }

    private function delete()
    {
        $this->evaluation->delete();
        $this->request->delete();
        $this->fee->delete();
        $this->calendar->delete();
        $this->binder->delete();
    }

    private function insert()
    {
        $this->binder->insert();
        $this->calendar->insert();
        $this->request->insert();
        $this->fee->insert();
        $this->dummy->insert();
        $this->evaluation->insert();
    }

    private function quote()
    {
        $quote = [
            'DON\'T RUN BECAUSE YOU HAVE TO<br>RUN BECAUSE YOU CAN',
            'WITHOUT A GOAL<br>YOU CAN\'T SCORE',
        ];
        $url = Url::base() . '/egs';
        $index = rand(0, sizeof($quote) - 1);
        echo '
			<body style="background-color: #F15757;">
			  <h1 style="text-align: center;color: white;margin-top: 24%;">
				  <div>' . $quote[$index] . '</div>
				  <a style="text-decoration: none;color: black;" href="' . $url . '">GO HOME</a>
			  </h1>
			</body>
			';
    }

}
