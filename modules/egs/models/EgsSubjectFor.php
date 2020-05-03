<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_subject_for".
 *
 * @property integer $subject_id
 * @property integer $program_id
 * @property integer $action_id
 *
 * @property EgsAction $action
 * @property EgsProgram $program
 * @property EgsSubject $subject
 */
class EgsSubjectFor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_subject_for';
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
            [['subject_id', 'program_id', 'action_id'], 'required'],
            [['subject_id', 'program_id', 'action_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsProgram::className(), 'targetAttribute' => ['program_id' => 'program_id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsSubject::className(), 'targetAttribute' => ['subject_id' => 'subject_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subject_id' => 'Subject ID',
            'program_id' => 'Program ID',
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
    public function getProgram()
    {
        return $this->hasOne(EgsProgram::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(EgsSubject::className(), ['subject_id' => 'subject_id']);
    }
}
