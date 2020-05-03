<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_step_type".
 *
 * @property integer $step_type_id
 * @property string $step_type_name_th
 * @property string $step_type_name_en
 *
 * @property EgsActionStep[] $egsActionSteps
 */
class EgsStepType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_step_type';
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
            [['step_type_id', 'step_type_name_th', 'step_type_name_en'], 'required'],
            [['step_type_id'], 'integer'],
            [['step_type_name_th', 'step_type_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'step_type_id' => 'Step Type ID',
            'step_type_name_th' => 'Step Type Name Th',
            'step_type_name_en' => 'Step Type Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionSteps()
    {
        return $this->hasMany(EgsActionStep::className(), ['step_type_id' => 'step_type_id']);
    }
}
