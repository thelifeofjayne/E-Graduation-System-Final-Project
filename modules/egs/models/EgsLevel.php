<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_level".
 *
 * @property integer $level_id
 * @property string $level_name_th
 * @property string $level_name_en
 *
 * @property EgsActionItem[] $egsActionItems
 * @property EgsCommitteeFee[] $egsCommitteeFees
 * @property EgsLevelBinder[] $egsLevelBinders
 */
class EgsLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_level';
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
            [['level_id', 'level_name_th', 'level_name_en'], 'required'],
            [['level_id'], 'integer'],
            [['level_name_th', 'level_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'level_id' => 'Level ID',
            'level_name_th' => 'Level Name Th',
            'level_name_en' => 'Level Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionItems()
    {
        return $this->hasMany(EgsActionItem::className(), ['level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommitteeFees()
    {
        return $this->hasMany(EgsCommitteeFee::className(), ['level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsLevelBinders()
    {
        return $this->hasMany(EgsLevelBinder::className(), ['level_id' => 'level_id']);
    }
}
