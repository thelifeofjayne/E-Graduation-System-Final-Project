<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_user_request".
 *
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $request_fee
 * @property integer $request_fee_status_id
 *
 * @property EgsAdvisor[] $egsAdvisors
 * @property EgsDefense[] $egsDefenses
 * @property EgsAction[] $defenseTypes
 * @property EgsRequestDocument[] $egsRequestDocuments
 * @property EgsDocument[] $documents
 * @property EgsUserEvaluation[] $egsUserEvaluations
 * @property EgsEvaluation[] $evaluations
 * @property EgsCalendarItem $calendar
 * @property EgsStatus $requestFeeStatus
 */
class EgsUserRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_user_request';
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
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'request_fee', 'request_fee_status_id'], 'required'],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'request_fee', 'request_fee_status_id'], 'integer'],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsCalendarItem::className(), 'targetAttribute' => ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id']],
            [['request_fee_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatus::className(), 'targetAttribute' => ['request_fee_status_id' => 'status_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
            'request_fee' => 'Request Fee',
            'request_fee_status_id' => 'Request Fee Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisors()
    {
        return $this->hasMany(EgsAdvisor::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenses()
    {
        return $this->hasMany(EgsDefense::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseTypes()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'defense_type_id'])->viaTable('egs_defense', ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestDocuments()
    {
        return $this->hasMany(EgsRequestDocument::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(EgsDocument::className(), ['document_id' => 'document_id'])->viaTable('egs_request_document', ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserEvaluations()
    {
        return $this->hasMany(EgsUserEvaluation::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluations()
    {
        return $this->hasMany(EgsEvaluation::className(), ['evaluation_id' => 'evaluation_id'])->viaTable('egs_user_evaluation', ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(EgsCalendarItem::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestFeeStatus()
    {
        return $this->hasOne(EgsStatus::className(), ['status_id' => 'request_fee_status_id']);
    }
}
