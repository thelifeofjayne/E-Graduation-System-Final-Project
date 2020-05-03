<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_level_binder".
 *
 * @property integer $level_id
 * @property string $reg_program_id
 *
 * @property EgsLevel $level
 */
class EgsLevelBinder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_level_binder';
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
            [['level_id', 'reg_program_id'], 'required'],
            [['level_id'], 'integer'],
            [['reg_program_id'], 'string', 'max' => 255],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'level_id' => 'Level ID',
            'reg_program_id' => 'Reg Program ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(EgsLevel::className(), ['level_id' => 'level_id']);
    }
}
