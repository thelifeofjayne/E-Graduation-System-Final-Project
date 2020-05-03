<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_position_type".
 *
 * @property integer $position_type_id
 * @property string $position_type_name
 *
 * @property EgsPosition[] $egsPositions
 */
class EgsPositionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_position_type';
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
            [['position_type_id', 'position_type_name'], 'required'],
            [['position_type_id'], 'integer'],
            [['position_type_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'position_type_id' => 'Position Type ID',
            'position_type_name' => 'Position Type Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsPositions()
    {
        return $this->hasMany(EgsPosition::className(), ['position_type_id' => 'position_type_id']);
    }
}
