<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_document".
 *
 * @property integer $document_id
 * @property string $document_name_th
 * @property string $document_name_en
 * @property integer $submit_type_id
 *
 * @property EgsActionDocument[] $egsActionDocuments
 * @property EgsAction[] $actions
 * @property EgsDefenseDocument[] $egsDefenseDocuments
 * @property EgsDefense[] $defenseTypes
 * @property EgsSubmitType $submitType
 * @property EgsRequestDocument[] $egsRequestDocuments
 * @property EgsUserRequest[] $calendars
 */
class EgsDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_document';
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
            [['document_id', 'document_name_th', 'document_name_en', 'submit_type_id'], 'required'],
            [['document_id', 'submit_type_id'], 'integer'],
            [['document_name_th', 'document_name_en'], 'string', 'max' => 255],
            [['submit_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsSubmitType::className(), 'targetAttribute' => ['submit_type_id' => 'submit_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'document_name_th' => 'Document Name Th',
            'document_name_en' => 'Document Name En',
            'submit_type_id' => 'Submit Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionDocuments()
    {
        return $this->hasMany(EgsActionDocument::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'action_id'])->viaTable('egs_action_document', ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseDocuments()
    {
        return $this->hasMany(EgsDefenseDocument::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseTypes()
    {
        return $this->hasMany(EgsDefense::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id'])->viaTable('egs_defense_document', ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubmitType()
    {
        return $this->hasOne(EgsSubmitType::className(), ['submit_type_id' => 'submit_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestDocuments()
    {
        return $this->hasMany(EgsRequestDocument::className(), ['document_id' => 'document_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id'])->viaTable('egs_request_document', ['document_id' => 'document_id']);
    }
}
