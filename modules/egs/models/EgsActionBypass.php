<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action_bypass".
 *
 * @property integer $student_id
 * @property integer $action_id
 * @property integer $semester_id
 *
 * @property EgsAction $action
 * @property EgsSemester $semester
 */
class EgsActionBypass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action_bypass';
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
            [['student_id', 'action_id', 'semester_id'], 'required'],
            [['student_id', 'action_id', 'semester_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsSemester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'student_id' => 'Student ID',
            'action_id' => 'Action ID',
            'semester_id' => 'Semester ID',
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
    public function getSemester()
    {
        return $this->hasOne(EgsSemester::className(), ['semester_id' => 'semester_id']);
    }
}
