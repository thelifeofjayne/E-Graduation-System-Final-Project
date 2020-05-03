<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_defense_document".
 *
 * @property integer $defense_type_id
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $document_id
 * @property string $defense_document_path
 * @property integer $defense_document_status_id
 *
 * @property EgsDefense $defenseType
 * @property EgsDocument $document
 * @property EgsStatus $defenseDocumentStatus
 */
class EgsDefenseDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_defense_document';
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
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'document_id', 'defense_document_status_id'], 'required'],
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'document_id', 'defense_document_status_id'], 'integer'],
            [['defense_document_path'], 'string', 'max' => 255],
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDefense::className(), 'targetAttribute' => ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDocument::className(), 'targetAttribute' => ['document_id' => 'document_id']],
            [['defense_document_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatus::className(), 'targetAttribute' => ['defense_document_status_id' => 'status_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'defense_type_id' => 'Defense Type ID',
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
            'document_id' => 'Document ID',
            'defense_document_path' => 'Defense Document Path',
            'defense_document_status_id' => 'Defense Document Status ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseType()
    {
        return $this->hasOne(EgsDefense::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
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
    public function getDefenseDocumentStatus()
    {
        return $this->hasOne(EgsStatus::className(), ['status_id' => 'defense_document_status_id']);
    }
}
