<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_defense_advisor".
 *
 * @property integer $defense_type_id
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 * @property integer $teacher_id
 * @property integer $advisor_fee_amount
 *
 * @property EgsDefense $defenseType
 */
class EgsDefenseAdvisor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_defense_advisor';
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
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'teacher_id', 'advisor_fee_amount'], 'required'],
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id', 'teacher_id', 'advisor_fee_amount'], 'integer'],
            [['defense_type_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDefense::className(), 'targetAttribute' => ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
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
            'teacher_id' => 'Teacher ID',
            'advisor_fee_amount' => 'Advisor Fee Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseType()
    {
        return $this->hasOne(EgsDefense::className(), ['defense_type_id' => 'defense_type_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }
}
