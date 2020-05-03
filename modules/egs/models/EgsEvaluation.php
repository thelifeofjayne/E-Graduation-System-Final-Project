<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_evaluation".
 *
 * @property integer $evaluation_id
 * @property string $evaluation_name_th
 * @property string $evaluation_name_en
 * @property string $evaluation_path
 * @property integer $evaluation_active
 *
 * @property EgsEvaluationTopicGroup[] $egsEvaluationTopicGroups
 * @property EgsUserEvaluation[] $egsUserEvaluations
 * @property EgsUserRequest[] $calendars
 */
class EgsEvaluation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_evaluation';
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
            [['evaluation_name_th', 'evaluation_name_en', 'evaluation_path', 'evaluation_active'], 'required'],
            [['evaluation_active'], 'integer'],
            [['evaluation_name_th', 'evaluation_name_en', 'evaluation_path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'evaluation_id' => 'Evaluation ID',
            'evaluation_name_th' => 'Evaluation Name Th',
            'evaluation_name_en' => 'Evaluation Name En',
            'evaluation_path' => 'Evaluation Path',
            'evaluation_active' => 'Evaluation Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsEvaluationTopicGroups()
    {
        return $this->hasMany(EgsEvaluationTopicGroup::className(), ['evaluation_id' => 'evaluation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserEvaluations()
    {
        return $this->hasMany(EgsUserEvaluation::className(), ['evaluation_id' => 'evaluation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id'])->viaTable('egs_user_evaluation', ['evaluation_id' => 'evaluation_id']);
    }
}
