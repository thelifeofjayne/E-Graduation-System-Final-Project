<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_todo".
 *
 * @property integer $todo_id
 * @property string $todo_name_th
 * @property string $todo_name_en
 * @property string $todo_validation
 *
 * @property EgsAction[] $egsActions
 * @property EgsTodoFor[] $egsTodoFors
 */
class EgsTodo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_todo';
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
            [['todo_id', 'todo_name_th', 'todo_name_en', 'todo_validation'], 'required'],
            [['todo_id'], 'integer'],
            [['todo_name_th', 'todo_name_en', 'todo_validation'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'todo_id' => 'Todo ID',
            'todo_name_th' => 'Todo Name Th',
            'todo_name_en' => 'Todo Name En',
            'todo_validation' => 'Todo Validation',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActions()
    {
        return $this->hasMany(EgsAction::className(), ['todo_id' => 'todo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsTodoFors()
    {
        return $this->hasMany(EgsTodoFor::className(), ['todo_id' => 'todo_id']);
    }
}
