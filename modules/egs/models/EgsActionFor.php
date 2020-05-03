<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action_for".
 *
 * @property integer $action_id
 * @property integer $program_id
 * @property integer $plan_id
 *
 * @property EgsAction $action
 * @property EgsPlan $plan
 * @property EgsProgram $program
 */
class EgsActionFor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action_for';
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
            [['action_id', 'program_id', 'plan_id'], 'required'],
            [['action_id', 'program_id', 'plan_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsProgram::className(), 'targetAttribute' => ['program_id' => 'program_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'program_id' => 'Program ID',
            'plan_id' => 'Plan ID',
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
    public function getPlan()
    {
        return $this->hasOne(EgsPlan::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(EgsProgram::className(), ['program_id' => 'program_id']);
    }
}
