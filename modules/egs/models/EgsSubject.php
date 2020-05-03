<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_subject".
 *
 * @property integer $subject_id
 * @property string $subject_name_th
 * @property string $subject_name_en
 *
 * @property EgsDefenseSubject[] $egsDefenseSubjects
 * @property EgsDefense[] $defenseTypes
 * @property EgsSubjectFor[] $egsSubjectFors
 */
class EgsSubject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_subject';
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
            [['subject_id', 'subject_name_th', 'subject_name_en'], 'required'],
            [['subject_id'], 'integer'],
            [['subject_name_th', 'subject_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subject_id' => 'Subject ID',
            'subject_name_th' => 'Subject Name Th',
            'subject_name_en' => 'Subject Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenseSubjects()
    {
        return $this->hasMany(EgsDefenseSubject::className(), ['subject_id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseTypes()
    {
        return $this->hasMany(EgsDefense::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id'])->viaTable('egs_defense_subject', ['subject_id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsSubjectFors()
    {
        return $this->hasMany(EgsSubjectFor::className(), ['subject_id' => 'subject_id']);
    }
}
