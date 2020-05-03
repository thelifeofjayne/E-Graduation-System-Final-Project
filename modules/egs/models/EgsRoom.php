<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_room".
 *
 * @property integer $room_id
 * @property string $room_name_th
 * @property string $room_name_en
 *
 * @property EgsDefense[] $egsDefenses
 */
class EgsRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_room';
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
            [['room_id', 'room_name_th', 'room_name_en'], 'required'],
            [['room_id'], 'integer'],
            [['room_name_th', 'room_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'room_id' => 'Room ID',
            'room_name_th' => 'Room Name Th',
            'room_name_en' => 'Room Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenses()
    {
        return $this->hasMany(EgsDefense::className(), ['room_id' => 'room_id']);
    }
}
