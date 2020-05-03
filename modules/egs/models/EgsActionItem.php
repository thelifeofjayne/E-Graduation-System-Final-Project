<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action_item".
 *
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 *
 * @property EgsLevel $level
 * @property EgsAction $action
 * @property EgsSemester $semester
 * @property EgsCalendarItem[] $egsCalendarItems
 */
class EgsActionItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action_item';
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
            [['action_id', 'level_id', 'semester_id'], 'required'],
            [['action_id', 'level_id', 'semester_id'], 'integer'],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsLevel::className(), 'targetAttribute' => ['level_id' => 'level_id']],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['semester_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsSemester::className(), 'targetAttribute' => ['semester_id' => 'semester_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(EgsLevel::className(), ['level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(EgsAction::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(EgsSemester::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCalendarItems()
    {
        return $this->hasMany(EgsCalendarItem::className(), ['semester_id' => 'semester_id', 'action_id' => 'action_id', 'level_id' => 'level_id']);
    }
}
