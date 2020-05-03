<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_program_binder".
 *
 * @property string $reg_program_id
 * @property integer $program_id
 *
 * @property EgsProgram $program
 */
class EgsProgramBinder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_program_binder';
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
            [['reg_program_id', 'program_id'], 'required'],
            [['program_id'], 'integer'],
            [['reg_program_id'], 'string', 'max' => 255],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsProgram::className(), 'targetAttribute' => ['program_id' => 'program_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reg_program_id' => 'Reg Program ID',
            'program_id' => 'Program ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(EgsProgram::className(), ['program_id' => 'program_id']);
    }
}
