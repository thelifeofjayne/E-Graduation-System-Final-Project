<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_request_defense".
 *
 * @property integer $request_type_id
 * @property integer $defense_type_id
 *
 * @property EgsAction $defenseType
 * @property EgsAction $requestType
 */
class EgsRequestDefense extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_request_defense';
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
            [['request_type_id', 'defense_type_id'], 'required'],
            [['request_type_id', 'defense_type_id'], 'integer'],
            [['defense_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['defense_type_id' => 'action_id']],
            [['request_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['request_type_id' => 'action_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'request_type_id' => 'Request Type ID',
            'defense_type_id' => 'Defense Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseType()
    {
        return $this->hasOne(EgsAction::className(), ['action_id' => 'defense_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestType()
    {
        return $this->hasOne(EgsAction::className(), ['action_id' => 'request_type_id']);
    }
}
