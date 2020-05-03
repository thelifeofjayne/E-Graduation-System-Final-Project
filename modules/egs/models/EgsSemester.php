<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_semester".
 *
 * @property integer $semester_id
 * @property string $semester_name_th
 * @property string $semester_name_en
 *
 * @property EgsActionBypass[] $egsActionBypasses
 * @property EgsActionItem[] $egsActionItems
 */
class EgsSemester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_semester';
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
            [['semester_id', 'semester_name_th', 'semester_name_en'], 'required'],
            [['semester_id'], 'integer'],
            [['semester_name_th', 'semester_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'semester_id' => 'Semester ID',
            'semester_name_th' => 'Semester Name Th',
            'semester_name_en' => 'Semester Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionBypasses()
    {
        return $this->hasMany(EgsActionBypass::className(), ['semester_id' => 'semester_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionItems()
    {
        return $this->hasMany(EgsActionItem::className(), ['semester_id' => 'semester_id']);
    }
}
