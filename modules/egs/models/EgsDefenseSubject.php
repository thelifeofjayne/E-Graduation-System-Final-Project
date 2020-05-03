<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_defense_subject".
 *
 * @property integer $subject_id
 * @property integer $defense_type_id
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $subject_pass
 * @property integer $already_passed
 * @property integer $defense_subject_status_id
 *
 * @property EgsDefense $defenseType
 * @property EgsStatus $defenseSubjectStatus
 * @property EgsSubject $subject
 */
class EgsDefenseSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_defense_subject';
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
            [['subject_id', 'defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'subject_pass', 'already_passed', 'defense_subject_status_id'], 'required'],
            [['subject_id', 'defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'subject_pass', 'already_passed', 'defense_subject_status_id'], 'integer'],
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDefense::className(), 'targetAttribute' => ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
            [['defense_subject_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatus::className(), 'targetAttribute' => ['defense_subject_status_id' => 'status_id']],
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
            'defense_type_id' => 'Defense Type ID',
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
            'subject_pass' => 'Subject Pass',
            'already_passed' => 'Already Passed',
            'defense_subject_status_id' => 'Defense Subject Status ID',
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
    public function getDefenseSubjectStatus()
    {
        return $this->hasOne(EgsStatus::className(), ['status_id' => 'defense_subject_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(EgsSubject::className(), ['subject_id' => 'subject_id']);
    }
}
