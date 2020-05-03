<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_defense".
 *
 * @property integer $defense_type_id
 * @property string $defense_date
 * @property string $defense_time_start
 * @property string $defense_time_end
 * @property integer $room_id
 * @property integer $defense_status_id
 * @property integer $defense_score
 * @property integer $defense_credit
 * @property string $defense_comment
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $project_id
 *
 * @property EgsCommittee[] $egsCommittees
 * @property EgsAction $defenseType
 * @property EgsProject $project
 * @property EgsRoom $room
 * @property EgsStatus $defenseStatus
 * @property EgsUserRequest $calendar
 * @property EgsDefenseAdvisor[] $egsDefenseAdvisors
 * @property EgsDefenseDocument[] $egsDefenseDocuments
 * @property EgsDocument[] $documents
 * @property EgsDefenseSubject[] $egsDefenseSubjects
 * @property EgsSubject[] $subjects
 */
class EgsDefense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_defense';
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
            [['defense_type_id', 'defense_date', 'defense_time_start', 'defense_time_end', 'room_id', 'defense_status_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'required'],
            [['defense_type_id', 'room_id', 'defense_status_id', 'defense_score', 'defense_credit', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'project_id'], 'integer'],
            [['defense_date', 'defense_time_start', 'defense_time_end'], 'safe'],
            [['defense_comment'], 'string', 'max' => 2560],
            [['defense_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['defense_type_id' => 'action_id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsProject::className(), 'targetAttribute' => ['project_id' => 'project_id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsRoom::className(), 'targetAttribute' => ['room_id' => 'room_id']],
            [['defense_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsStatus::className(), 'targetAttribute' => ['defense_status_id' => 'status_id']],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsUserRequest::className(), 'targetAttribute' => ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'defense_type_id' => 'Defense Type ID',
            'defense_date' => 'Defense Date',
            'defense_time_start' => 'Defense Time Start',
            'defense_time_end' => 'Defense Time End',
            'room_id' => 'Room ID',
            'defense_status_id' => 'Defense Status ID',
            'defense_score' => 'Defense Score',
            'defense_credit' => 'Defense Credit',
            'defense_comment' => 'Defense Comment',
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
            'project_id' => 'Project ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommittees()
    {
        return $this->hasMany(EgsCommittee::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseType()
    {
        return $this->hasOne(EgsAction::className(), ['action_id' => 'defense_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(EgsProject::className(), ['project_id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(EgsRoom::className(), ['room_id' => 'room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseStatus()
    {
        return $this->hasOne(EgsStatus::className(), ['status_id' => 'defense_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseAdvisors()
    {
        return $this->hasMany(EgsDefenseAdvisor::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseDocuments()
    {
        return $this->hasMany(EgsDefenseDocument::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(EgsDocument::className(), ['document_id' => 'document_id'])->viaTable('egs_defense_document', ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseSubjects()
    {
        return $this->hasMany(EgsDefenseSubject::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(EgsSubject::className(), ['subject_id' => 'subject_id'])->viaTable('egs_defense_subject', ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }
}
