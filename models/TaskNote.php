<?php

namespace app\modules\projects\models;

use Yii;

/**
 * This is the model class for table "{{%task_notes}}".
 *
 * @property string $note_id
 * @property string $task_id
 * @property string $user_id
 * @property string $note
 * @property string $created_at
 *
 * @property UserProfiles $user
 * @property Task $task
 */
class TaskNote extends MyActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%task_notes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id', 'note'], 'required'],
            [['note_id', 'task_id'], 'integer'],
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
            'note_id' => Yii::t('app', 'Ид. примечания'),
            'task_id' => Yii::t('app', 'Ид. задачи'),
            'user_id' => Yii::t('app', 'Ид. пользователя'),
            'note' => Yii::t('app', 'Примечание'),
            'created_at' => Yii::t('app', 'Создано'),
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
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['task_id' => 'task_id']);
    }
}