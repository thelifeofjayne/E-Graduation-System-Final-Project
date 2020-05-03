<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_committee_fee".
 *
 * @property integer $action_id
 * @property integer $branch_id
 * @property integer $level_id
 * @property integer $plan_type_id
 * @property integer $committee_fee_amount
 *
 * @property EgsAction $action
 * @property EgsBranch $branch
 * @property EgsLevel $level
 * @property EgsPlanType $planType
 */
class EgsCommitteeFee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_committee_fee';
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
            [['action_id', 'branch_id', 'level_id', 'plan_type_id', 'committee_fee_amount'], 'required'],
            [['action_id', 'branch_id', 'level_id', 'plan_type_id', 'committee_fee_amount'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsBranch::className(), 'targetAttribute' => ['branch_id' => 'branch_id']],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
            [['plan_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlanType::className(), 'targetAttribute' => ['plan_type_id' => 'plan_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'branch_id' => 'Branch ID',
            'level_id' => 'Level ID',
            'plan_type_id' => 'Plan Type ID',
            'committee_fee_amount' => 'Committee Fee Amount',
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
    public function getLevel()
    {
        return $this->hasOne(EgsLevel::className(), ['level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanType()
    {
        return $this->hasOne(EgsPlanType::className(), ['plan_type_id' => 'plan_type_id']);
    }
}
