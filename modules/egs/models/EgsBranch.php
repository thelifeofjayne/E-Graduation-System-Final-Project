<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_branch".
 *
 * @property integer $branch_id
 * @property string $branch_name_th
 * @property string $branch_name_en
 *
 * @property EgsAdvisorFee[] $egsAdvisorFees
 * @property EgsBranchBinder[] $egsBranchBinders
 * @property EgsCommitteeFee[] $egsCommitteeFees
 * @property EgsRequestFee[] $egsRequestFees
 */
class EgsBranch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_branch';
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
            [['branch_id', 'branch_name_th', 'branch_name_en'], 'required'],
            [['branch_id'], 'integer'],
            [['branch_name_th', 'branch_name_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'branch_id' => 'Branch ID',
            'branch_name_th' => 'Branch Name Th',
            'branch_name_en' => 'Branch Name En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisorFees()
    {
        return $this->hasMany(EgsAdvisorFee::className(), ['branch_id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsBranchBinders()
    {
        return $this->hasMany(EgsBranchBinder::className(), ['branch_id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommitteeFees()
    {
        return $this->hasMany(EgsCommitteeFee::className(), ['branch_id' => 'branch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestFees()
    {
        return $this->hasMany(EgsRequestFee::className(), ['branch_id' => 'branch_id']);
    }
}
