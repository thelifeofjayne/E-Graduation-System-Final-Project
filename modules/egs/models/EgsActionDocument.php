<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action_document".
 *
 * @property integer $action_id
 * @property integer $document_id
 *
 * @property EgsAction $action
 * @property EgsDocument $document
 */
class EgsActionDocument extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action_document';
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
            [['action_id', 'document_id'], 'required'],
            [['action_id', 'document_id'], 'integer'],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsAction::className(), 'targetAttribute' => ['action_id' => 'action_id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsDocument::className(), 'targetAttribute' => ['document_id' => 'document_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'document_id' => 'Document ID',
        ];
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
    public function getDocument()
    {
        return $this->hasOne(EgsDocument::className(), ['document_id' => 'document_id']);
    }
}
