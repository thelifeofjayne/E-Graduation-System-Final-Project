<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_todo_for".
 *
 * @property integer $todo_id
 * @property integer $program_id
 * @property integer $plan_id
 *
 * @property EgsPlan $plan
 * @property EgsProgram $program
 * @property EgsTodo $todo
 */
class EgsTodoFor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_todo_for';
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
            [['todo_id', 'program_id', 'plan_id'], 'required'],
            [['todo_id', 'program_id', 'plan_id'], 'integer'],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsProgram::className(), 'targetAttribute' => ['program_id' => 'program_id']],
            [['todo_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsTodo::className(), 'targetAttribute' => ['todo_id' => 'todo_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'todo_id' => 'Todo ID',
            'program_id' => 'Program ID',
            'plan_id' => 'Plan ID',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTodo()
    {
        return $this->hasOne(EgsTodo::className(), ['todo_id' => 'todo_id']);
    }
}
