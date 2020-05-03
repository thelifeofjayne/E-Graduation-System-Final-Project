<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_load".
 *
 * @property integer $plan_type_id
 * @property double $load_amount
 *
 * @property EgsAdvisor[] $egsAdvisors
 * @property EgsPlanType $planType
 */
class EgsLoad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_load';
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
            [['plan_type_id', 'load_amount'], 'required'],
            [['plan_type_id'], 'integer'],
            [['load_amount'], 'number'],
            [['plan_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlanType::className(), 'targetAttribute' => ['plan_type_id' => 'plan_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_type_id' => 'Plan Type ID',
            'load_amount' => 'Load Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisors()
    {
        return $this->hasMany(EgsAdvisor::className(), ['load_id' => 'plan_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanType()
    {
        return $this->hasOne(EgsPlanType::className(), ['plan_type_id' => 'plan_type_id']);
    }
}
