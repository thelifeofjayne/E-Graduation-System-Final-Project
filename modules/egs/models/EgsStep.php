<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_step".
 *
 * @property integer $step_id
 * @property string $step_name_th
 * @property string $step_name_en
 * @property string $step_component
 * @property string $step_icon
 * @property string $step_validation
 *
 * @property EgsActionStep[] $egsActionSteps
 * @property EgsAction[] $actions
 */
class EgsStep extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_step';
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
            [['step_id', 'step_name_th', 'step_name_en', 'step_component', 'step_icon'], 'required'],
            [['step_id'], 'integer'],
            [['step_name_th', 'step_name_en', 'step_component', 'step_icon', 'step_validation'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'step_id' => 'Step ID',
            'step_name_th' => 'Step Name Th',
            'step_name_en' => 'Step Name En',
            'step_component' => 'Step Component',
            'step_icon' => 'Step Icon',
            'step_validation' => 'Step Validation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionSteps()
    {
        return $this->hasMany(EgsActionStep::className(), ['step_id' => 'step_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'action_id'])->viaTable('egs_action_step', ['step_id' => 'step_id']);
    }
}
