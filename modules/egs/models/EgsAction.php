<?php

namespace app\modules\egs\models;

use Yii;

/**
 * This is the model class for table "egs_action".
 *
 * @property integer $action_id
 * @property string $action_name_th
 * @property string $action_name_en
 * @property integer $action_type_id
 * @property integer $action_redo
 * @property integer $action_credit
 * @property integer $action_cond
 * @property integer $action_project
 * @property integer $action_score
 * @property string $action_validation
 * @property integer $todo_id
 *
 * @property EgsTodo $todo
 * @property EgsActionType $actionType
 * @property EgsActionBypass[] $egsActionBypasses
 * @property EgsActionDocument[] $egsActionDocuments
 * @property EgsDocument[] $documents
 * @property EgsActionFor[] $egsActionFors
 * @property EgsActionItem[] $egsActionItems
 * @property EgsActionStep[] $egsActionSteps
 * @property EgsStep[] $steps
 * @property EgsAdvisorFee[] $egsAdvisorFees
 * @property EgsCommitteeFee[] $egsCommitteeFees
 * @property EgsDefense[] $egsDefenses
 * @property EgsUserRequest[] $calendars
 * @property EgsRequestDefense[] $egsRequestDefenses
 * @property EgsRequestDefense[] $egsRequestDefenses0
 * @property EgsAction[] $requestTypes
 * @property EgsAction[] $defenseTypes
 * @property EgsRequestFee[] $egsRequestFees
 * @property EgsRequestInit[] $egsRequestInits
 * @property EgsRequestInit[] $egsRequestInits0
 * @property EgsAction[] $initTypes
 * @property EgsAction[] $requestTypes0
 * @property EgsSubjectFor[] $egsSubjectFors
 */
class EgsAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'egs_action';
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
            [['action_id', 'action_name_th', 'action_name_en', 'action_type_id', 'action_redo', 'action_credit', 'action_cond', 'action_project', 'action_score'], 'required'],
            [['action_id', 'action_type_id', 'action_redo', 'action_credit', 'action_cond', 'action_project', 'action_score', 'todo_id'], 'integer'],
            [['action_name_th', 'action_name_en', 'action_validation'], 'string', 'max' => 255],
            [['todo_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsTodo::className(), 'targetAttribute' => ['todo_id' => 'todo_id']],
            [['action_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EgsActionType::className(), 'targetAttribute' => ['action_type_id' => 'action_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_id' => 'Action ID',
            'action_name_th' => 'Action Name Th',
            'action_name_en' => 'Action Name En',
            'action_type_id' => 'Action Type ID',
            'action_redo' => 'Action Redo',
            'action_credit' => 'Action Credit',
            'action_cond' => 'Action Cond',
            'action_project' => 'Action Project',
            'action_score' => 'Action Score',
            'action_validation' => 'Action Validation',
            'todo_id' => 'Todo ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTodo()
    {
        return $this->hasOne(EgsTodo::className(), ['todo_id' => 'todo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionType()
    {
        return $this->hasOne(EgsActionType::className(), ['action_type_id' => 'action_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionBypasses()
    {
        return $this->hasMany(EgsActionBypass::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionDocuments()
    {
        return $this->hasMany(EgsActionDocument::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(EgsDocument::className(), ['document_id' => 'document_id'])->viaTable('egs_action_document', ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionFors()
    {
        return $this->hasMany(EgsActionFor::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionItems()
    {
        return $this->hasMany(EgsActionItem::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsActionSteps()
    {
        return $this->hasMany(EgsActionStep::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(EgsStep::className(), ['step_id' => 'step_id'])->viaTable('egs_action_step', ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsAdvisorFees()
    {
        return $this->hasMany(EgsAdvisorFee::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsCommitteeFees()
    {
        return $this->hasMany(EgsCommitteeFee::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsDefenses()
    {
        return $this->hasMany(EgsDefense::className(), ['defense_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(EgsUserRequest::className(), ['calendar_id' => 'calendar_id', 'action_id' => 'action_id', 'level_id' => 'level_id', 'semester_id' => 'semester_id', 'owner_id' => 'owner_id', 'student_id' => 'student_id'])->viaTable('egs_defense', ['defense_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestDefenses()
    {
        return $this->hasMany(EgsRequestDefense::className(), ['defense_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestDefenses0()
    {
        return $this->hasMany(EgsRequestDefense::className(), ['request_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestTypes()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'request_type_id'])->viaTable('egs_request_defense', ['defense_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefenseTypes()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'defense_type_id'])->viaTable('egs_request_defense', ['request_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestFees()
    {
        return $this->hasMany(EgsRequestFee::className(), ['action_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestInits()
    {
        return $this->hasMany(EgsRequestInit::className(), ['request_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsRequestInits0()
    {
        return $this->hasMany(EgsRequestInit::className(), ['init_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitTypes()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'init_type_id'])->viaTable('egs_request_init', ['request_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestTypes0()
    {
        return $this->hasMany(EgsAction::className(), ['action_id' => 'request_type_id'])->viaTable('egs_request_init', ['init_type_id' => 'action_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEgsSubjectFors()
    {
        return $this->hasMany(EgsSubjectFor::className(), ['action_id' => 'action_id']);
    }
}
