<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_plan_type".
 *
 * @property integer $plan_type_id
 * @property string $plan_type_name_th
 * @property string $plan_type_name_en
 *
 * @property EgsCommitteeFee[] $egsCommitteeFees
 * @property EgsLoad $egsLoad
 * @property EgsPlan[] $egsPlans
 */
class EgsPlanType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_plan_type';
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
            [['plan_type_id', 'plan_type_name_th', 'plan_type_name_en'], 'required'],
            [['plan_type_id'], 'integer'],
            [['plan_type_name_th', 'plan_type_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_type_id' => 'Plan Type ID',
            'plan_type_name_th' => 'Plan Type Name Th',
            'plan_type_name_en' => 'Plan Type Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommitteeFees()
    {
        return $this->hasMany(EgsCommitteeFee::className(), ['plan_type_id' => 'plan_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsLoad()
    {
        return $this->hasOne(EgsLoad::className(), ['plan_type_id' => 'plan_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsPlans()
    {
        return $this->hasMany(EgsPlan::className(), ['plan_type_id' => 'plan_type_id']);
    }
}
