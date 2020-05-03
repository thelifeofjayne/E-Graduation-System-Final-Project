<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_project".
 *
 * @property integer $project_id
 * @property integer $student_id
 * @property string $project_name_th
 * @property string $project_name_en
 * @property integer $project_active
 *
 * @property EgsDefense[] $egsDefenses
 */
class EgsProject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_project';
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
            [['student_id', 'project_name_th', 'project_name_en', 'project_active'], 'required'],
            [['student_id', 'project_active'], 'integer'],
            [['project_name_th', 'project_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id' => 'Project ID',
            'student_id' => 'Student ID',
            'project_name_th' => 'Project Name Th',
            'project_name_en' => 'Project Name En',
            'project_active' => 'Project Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenses()
    {
        return $this->hasMany(EgsDefense::className(), ['project_id' => 'project_id']);
    }
}
