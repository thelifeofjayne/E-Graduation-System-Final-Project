<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_user_evaluation".
 *
 * @property integer $evaluation_id
 * @property string $user_evaluation_data
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property integer $student_id
 *
 * @property EgsEvaluation $evaluation
 * @property EgsUserRequest $calendar
 * @property EgsUserEvaluationRate[] $egsUserEvaluationRates
 * @property EgsEvaluationTopic[] $evaluationTopics
 */
class EgsUserEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_user_evaluation';
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
            [['evaluation_id', 'user_evaluation_data', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'required'],
            [['evaluation_id', 'calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'integer'],
            [['user_evaluation_data'], 'string', 'max' => 6969],
            [['evaluation_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsEvaluation::className(), 'targetAttribute' => ['evaluation_id' => 'evaluation_id']],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id', 'student_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsUserRequest::className(), 'targetAttribute' => ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'evaluation_id' => 'Evaluation ID',
            'user_evaluation_data' => 'User Evaluation Data',
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'student_id' => 'Student ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluation()
    {
        return $this->hasOne(EgsEvaluation::className(), ['evaluation_id' => 'evaluation_id']);
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
    public function getEgsUserEvaluationRates()
    {
        return $this->hasMany(EgsUserEvaluationRate::className(), ['evaluation_id' => 'evaluation_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluationTopics()
    {
        return $this->hasMany(EgsEvaluationTopic::className(), ['evaluation_topic_id' => 'evaluation_topic_id'])->viaTable('egs_user_evaluation_rate', ['evaluation_id' => 'evaluation_id', 'calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id']);
    }
}
