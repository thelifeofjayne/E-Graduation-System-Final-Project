<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_program".
 *
 * @property integer $program_id
 * @property string $program_name_th
 * @property string $program_name_en
 *
 * @property EgsActionFor[] $egsActionFors
 * @property EgsProgramBinder[] $egsProgramBinders
 * @property EgsSubjectFor[] $egsSubjectFors
 * @property EgsTodoFor[] $egsTodoFors
 */
class EgsProgram extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_program';
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
            [['program_id', 'program_name_th', 'program_name_en'], 'required'],
            [['program_id'], 'integer'],
            [['program_name_th', 'program_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'program_id' => 'Program ID',
            'program_name_th' => 'Program Name Th',
            'program_name_en' => 'Program Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionFors()
    {
        return $this->hasMany(EgsActionFor::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsProgramBinders()
    {
        return $this->hasMany(EgsProgramBinder::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsSubjectFors()
    {
        return $this->hasMany(EgsSubjectFor::className(), ['program_id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsTodoFors()
    {
        return $this->hasMany(EgsTodoFor::className(), ['program_id' => 'program_id']);
    }
}
