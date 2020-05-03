<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_advisor_fee".
 *
 * @property integer $advisor_fee_amount
 * @property integer $plan_id
 * @property integer $branch_id
 * @property integer $action_id
 *
 * @property EgsAction $action
 * @property EgsBranch $branch
 * @property EgsPlan $plan
 */
class EgsAdvisorFee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_advisor_fee';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_egs');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advisor_fee_amount', 'plan_id', 'branch_id', 'action_id'], 'required'],
            [['advisor_fee_amount', 'plan_id', 'branch_id', 'action_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsBranch::className(), 'targetAttribute' => ['branch_id' => 'branch_id']],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advisor_fee_amount' => 'Advisor Fee Amount',
            'plan_id' => 'Plan ID',
            'branch_id' => 'Branch ID',
            'action_id' => 'Action ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(EgsAction::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(EgsBranch::className(), ['branch_id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(EgsPlan::className(), ['plan_id' => 'plan_id']);
    }
}
