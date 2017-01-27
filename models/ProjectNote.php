<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * This is the model class for table "{{%project_notes}}".
 *
 * @property string $note_id
 * @property string $project_id
 * @property string $user_id
 * @property string $note
 * @property string $created_at
 *
 * @property UserProfiles $user
 * @property Project $project
 */
class ProjectNote extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_notes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'note'], 'required'],
            [['note_id', 'project_id'], 'integer'],
            [['created_at'], 'safe'],
            [['user_id'], 'string', 'max' => 64],
            [['note'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note_id' => Module::t('app', 'Ид. примечания'),
            'project_id' => Module::t('app', 'Ид. проекта'),
            'user_id' => Module::t('app', 'Ид. пользователя'),
            'note' => Module::t('app', 'Примечание'),
            'created_at' => Module::t('app', 'Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['project_id' => 'project_id']);
    }
}