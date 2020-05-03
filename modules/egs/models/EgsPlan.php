<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_plan".
 *
 * @property integer $plan_id
 * @property string $plan_name_th
 * @property string $plan_name_en
 * @property integer $plan_type_id
 *
 * @property EgsActionFor[] $egsActionFors
 * @property EgsAdvisorFee[] $egsAdvisorFees
 * @property EgsPlanType $planType
 * @property EgsPlanBinder[] $egsPlanBinders
 * @property EgsRequestFee[] $egsRequestFees
 * @property EgsTodoFor[] $egsTodoFors
 */
class EgsPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_plan';
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
            [['plan_id', 'plan_name_th', 'plan_name_en', 'plan_type_id'], 'required'],
            [['plan_id', 'plan_type_id'], 'integer'],
            [['plan_name_th', 'plan_name_en'], 'string', 'max' => 255],
            [['plan_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlanType::className(), 'targetAttribute' => ['plan_type_id' => 'plan_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'Plan ID',
            'plan_name_th' => 'Plan Name Th',
            'plan_name_en' => 'Plan Name En',
            'plan_type_id' => 'Plan Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionFors()
    {
        return $this->hasMany(EgsActionFor::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisorFees()
    {
        return $this->hasMany(EgsAdvisorFee::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanType()
    {
        return $this->hasOne(EgsPlanType::className(), ['plan_type_id' => 'plan_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsPlanBinders()
    {
        return $this->hasMany(EgsPlanBinder::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestFees()
    {
        return $this->hasMany(EgsRequestFee::className(), ['plan_id' => 'plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsTodoFors()
    {
        return $this->hasMany(EgsTodoFor::className(), ['plan_id' => 'plan_id']);
    }
}
