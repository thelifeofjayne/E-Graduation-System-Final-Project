<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_evaluation_topic_group".
 *
 * @property integer $evaluation_topic_group_id
 * @property string $evaluation_topic_group_name_th
 * @property string $evaluation_topic_group_name_en
 * @property integer $evaluation_id
 *
 * @property EgsEvaluationTopic[] $egsEvaluationTopics
 * @property EgsEvaluation $evaluation
 */
class EgsEvaluationTopicGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_evaluation_topic_group';
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
            [['evaluation_topic_group_name_th', 'evaluation_topic_group_name_en', 'evaluation_id'], 'required'],
            [['evaluation_id'], 'integer'],
            [['evaluation_topic_group_name_th', 'evaluation_topic_group_name_en'], 'string', 'max' => 255],
            [['evaluation_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsEvaluation::className(), 'targetAttribute' => ['evaluation_id' => 'evaluation_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'evaluation_topic_group_id' => 'Evaluation Topic Group ID',
            'evaluation_topic_group_name_th' => 'Evaluation Topic Group Name Th',
            'evaluation_topic_group_name_en' => 'Evaluation Topic Group Name En',
            'evaluation_id' => 'Evaluation ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsEvaluationTopics()
    {
        return $this->hasMany(EgsEvaluationTopic::className(), ['evaluation_topic_group_id' => 'evaluation_topic_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvaluation()
    {
        return $this->hasOne(EgsEvaluation::className(), ['evaluation_id' => 'evaluation_id']);
    }
}
