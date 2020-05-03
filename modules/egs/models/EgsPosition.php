<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_position".
 *
 * @property integer $position_id
 * @property string $position_name_th
 * @property string $position_name_en
 * @property integer $position_maximum
 * @property integer $position_type_id
 * @property integer $position_minimum
 *
 * @property EgsAdvisor[] $egsAdvisors
 * @property EgsCommittee[] $egsCommittees
 * @property EgsPositionType $positionType
 */
class EgsPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_position';
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
            [['position_id', 'position_name_th', 'position_name_en', 'position_maximum', 'position_type_id', 'position_minimum'], 'required'],
            [['position_id', 'position_maximum', 'position_type_id', 'position_minimum'], 'integer'],
            [['position_name_th', 'position_name_en'], 'string', 'max' => 255],
            [['position_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsPositionType::className(), 'targetAttribute' => ['position_type_id' => 'position_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'position_id' => 'Position ID',
            'position_name_th' => 'Position Name Th',
            'position_name_en' => 'Position Name En',
            'position_maximum' => 'Position Maximum',
            'position_type_id' => 'Position Type ID',
            'position_minimum' => 'Position Minimum',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisors()
    {
        return $this->hasMany(EgsAdvisor::className(), ['position_id' => 'position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommittees()
    {
        return $this->hasMany(EgsCommittee::className(), ['position_id' => 'position_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPositionType()
    {
        return $this->hasOne(EgsPositionType::className(), ['position_type_id' => 'position_type_id']);
    }
}
