<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_plan_binder".
 *
 * @property integer $reg_program_id
 * @property integer $plan_id
 *
 * @property EgsPlan $plan
 */
class EgsPlanBinder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_plan_binder';
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
            [['reg_program_id', 'plan_id'], 'required'],
            [['reg_program_id', 'plan_id'], 'integer'],
            [['plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPlan::className(), 'targetAttribute' => ['plan_id' => 'plan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reg_program_id' => 'Reg Program ID',
            'plan_id' => 'Plan ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(EgsPlan::className(), ['plan_id' => 'plan_id']);
    }
}
