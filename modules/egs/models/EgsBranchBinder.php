<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_branch_binder".
 *
 * @property string $reg_program_id
 * @property integer $branch_id
 *
 * @property EgsBranch $branch
 */
class EgsBranchBinder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_branch_binder';
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
            [['reg_program_id', 'branch_id'], 'required'],
            [['branch_id'], 'integer'],
            [['reg_program_id'], 'string', 'max' => 255],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsBranch::className(), 'targetAttribute' => ['branch_id' => 'branch_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reg_program_id' => 'Reg Program ID',
            'branch_id' => 'Branch ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(EgsBranch::className(), ['branch_id' => 'branch_id']);
    }
}
