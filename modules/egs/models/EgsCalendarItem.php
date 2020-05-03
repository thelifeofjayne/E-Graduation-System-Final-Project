<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_calendar_item".
 *
 * @property integer $calendar_id
 * @property integer $action_id
 * @property integer $level_id
 * @property integer $semester_id
 * @property integer $owner_id
 * @property string $calendar_item_date_start
 * @property string $calendar_item_date_end
 *
 * @property EgsCalendar $calendar
 * @property EgsActionItem $semester
 * @property EgsUserRequest[] $egsUserRequests
 */
class EgsCalendarItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_calendar_item';
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
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id'], 'required'],
            [['calendar_id', 'action_id', 'level_id', 'semester_id', 'owner_id'], 'integer'],
            [['calendar_item_date_start', 'calendar_item_date_end'], 'safe'],
            [['calendar_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsCalendar::className(), 'targetAttribute' => ['calendar_id' => 'calendar_id']],
            [['semester_id', 'action_id', 'level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsActionItem::className(), 'targetAttribute' => ['semester_id' => 'semester_id', 'action_id' => 'action_id', 'level_id' => 'level_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'calendar_id' => 'Calendar ID',
            'action_id' => 'Action ID',
            'level_id' => 'Level ID',
            'semester_id' => 'Semester ID',
            'owner_id' => 'Owner ID',
            'calendar_item_date_start' => 'Calendar Item Date Start',
            'calendar_item_date_end' => 'Calendar Item Date End',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(EgsCalendar::className(), ['calendar_id' => 'calendar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSemester()
    {
        return $this->hasOne(EgsActionItem::className(), ['semester_id' => 'semester_id', 'action_id' => 'action_id', 'level_id' => 'level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsUserRequests()
    {
        return $this->hasMany(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id']);
    }
}
