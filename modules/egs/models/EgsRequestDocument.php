<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_request_document".
 *
 * @property integer $document_id
 * @property integer $request_document_id
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $request_document_status_id
 *
 * @property EgsDocument $document
 * @property EgsStatus $requestDocumentStatus
 * @property EgsUserRequest $calendar
 */
class EgsRequestDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_request_document';
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
            [['document_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'request_document_status_id'], 'required'],
            [['document_id', 'request_document_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'request_document_status_id'], 'integer'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDocument::className(), 'targetAttribute' => ['document_id' => 'document_id']],
            [['request_document_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatus::className(), 'targetAttribute' => ['request_document_status_id' => 'status_id']],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsUserRequest::className(), 'targetAttribute' => ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'request_document_id' => 'Request Document ID',
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
            'request_document_status_id' => 'Request Document Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(EgsDocument::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDocumentStatus()
    {
        return $this->hasOne(EgsStatus::className(), ['status_id' => 'request_document_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }
}
