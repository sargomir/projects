<?php

namespace app\modules\projects\models;

use Yii;
use app\modules\projects\Projects as Module;
/**
 * This is the model class for table "{{%Projects}}".
 *
 * @property integer $ProjectID
 * @property string $Project
 * @property string $Customer
 *
 * @property Tasks[] $tasks
 */
class Project extends MyActiveRecord
{
    public $Tasks;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project', 'company', 'project_lead_id', 'project_manager_id'], 'safe'],
            [['project', 'company', 'project_lead_id'], 'required'],
            [['project', 'company', 'project_lead_id', 'project_manager_id'], 'string', 'max' => 64],
            [['active'], 'boolean'],
            [['bdds_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'project_id'        => Module::t('app', 'Project ID'),
            'project'           => Module::t('app', 'Project'),
            'company'           => Module::t('app', 'Customer'),
            'project_lead_id'   => Module::t('app', 'Project Lead'),
            'project_manager_id'=> Module::t('app', 'Project Manager'),
            'active'            => Module::t('app', 'Active'),
            'notes'             => Module::t('app', 'Notes'),
            'bdds_id'           => Module::t('app', 'BDDS ID'),

        ];
    }

    /**
     * Assisiated Project lead profile model
     * @return AuthProfile
     */
    public function getProject_lead()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'project_lead_id']);
    }
    
    /**
     * Assisiated Project manager profile model
     * @return AuthProfile
     */
    public function getProject_manager()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'project_manager_id']);
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotes()
    {
        return $this->hasMany(ProjectNote::className(), ['project_id' => 'project_id']);
    }
    
    public function getSum_tasks_estimated()
    {
        return null;
    }
}