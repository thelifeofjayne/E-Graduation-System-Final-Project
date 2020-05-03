<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_evaluation_topic".
 *
 * @property integer $evaluation_topic_id
 * @property string $evaluation_topic_name_th
 * @property string $evaluation_topic_name_en
 * @property integer $evaluation_topic_group_id
 *
 * @property EgsEvaluationTopicGroup $evaluationTopicGroup
 * @property EgsUserEvaluationRate[] $egsUserEvaluationRates
 * @property EgsUserEvaluation[] $egsUserEvaluationEvaluations
 */
class EgsEvaluationTopic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_evaluation_topic';
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
            [['evaluation_topic_name_th', 'evaluation_topic_name_en', 'evaluation_topic_group_id'], 'required'],
            [['evaluation_topic_group_id'], 'integer'],
            [['evaluation_topic_name_th', 'evaluation_topic_name_en'], 'string', 'max' => 255],
            [['evaluation_topic_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsEvaluationTopicGroup::className(), 'targetAttribute' => ['evaluation_topic_group_id' => 'evaluation_topic_group_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'evaluation_topic_id' => 'Evaluation Topic ID',
            'evaluation_topic_name_th' => 'Evaluation Topic Name Th',
            'evaluation_topic_name_en' => 'Evaluation Topic Name En',
            'evaluation_topic_group_id' => 'Evaluation Topic Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluationTopicGroup()
    {
        return $this->hasOne(EgsEvaluationTopicGroup::className(), ['evaluation_topic_group_id' => 'evaluation_topic_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserEvaluationRates()
    {
        return $this->hasMany(EgsUserEvaluationRate::className(), ['evaluation_topic_id' => 'evaluation_topic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserEvaluationEvaluations()
    {
        return $this->hasMany(EgsUserEvaluation::className(), ['evaluation_id' => 'egs_user_evaluation_evaluation_id', 'student_id' => 'egs_user_evaluation_student_id'])->viaTable('egs_user_evaluation_rate', ['evaluation_topic_id' => 'evaluation_topic_id']);
    }
}
