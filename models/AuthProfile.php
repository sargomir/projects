<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;

/**
 * This is the model class for table "{{%user_profiles}}".
 *
 * @property string $user_id
 * @property string $firstname
 * @property string $secondname
 * @property string $lastname
 * @property string $lead_id
 * @property string $email
 *
 * @property TaskNote[] $taskNotes
 * @property AuthUser $credentials
 * @property AuthProfile $lead
 * @property AuthProfile[] $subordinates
 */
class AuthProfile extends AuthActiveRecord
{
    private $_roles;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['roles'], 'safe'],
            [['firstname', 'secondname', 'lastname'], 'string', 'max' => 64],
            [['email'], 'email'],
            [['lead_id'], 'default', 'value'=>null],
            [['user_id', 'lead_id'], 'string', 'max' => 64],
            [['bdds_id'], 'integer'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('app', 'Login'),
            'firstname' => Module::t('app', 'First Name'),
            'secondname' => Module::t('app', 'Second Name'),
            'lastname' => Module::t('app', 'Last Name'),
            'email' => Module::t('app', 'E-Mail'),
            'lead_id' => Module::t('app', 'Technical Lead'),
            'bdds_id' => Module::t('app', 'BDDS ID'),
        ];
    }
    
    /**
     * Create AuthAssignment for eash role checked
     * and delete AuthAssignment for each role unchecked
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->_roles === '') {
            // No role checked. Delete all roles from user_id
            AuthAssignment::deleteAll(['user_id'=>$this->user_id]);
            return true;
        };

        if (is_array($this->_roles)) {
            // Remove unchecked roles
            foreach ($this->roles as $key=>$role) {
                if (! in_array ($role, $this->_roles)) {
                    Yii::trace("Remove role `{$role}` from `{$this->user_id}`", 'auth');
                    AuthAssignment::findOne(['item_name'=>$role, 'user_id'=>$this->user_id])->delete();
                }
            };
            // Create checked roles
            foreach ($this->_roles as $key=>$role) {
                // Check if role exists
                if (! AuthAssignment::find()->where(['item_name'=>$role, 'user_id'=>$this->user_id])->asArray()->all()) {    
                    Yii::trace("Create role `{$role}` for `{$this->user_id}`", 'auth');
                    (new AuthAssignment(['item_name'=>$role, 'user_id'=>$this->user_id]))->save();
                }
            };
            return true;
        };
        return false;
    }
    
    public function getFullname()
    {
        return "{$this->lastname} {$this->firstname} {$this->secondname}";
    }
    
    public function getUsername()
    {
        //return "{$this->lastname} {$this->firstname} {$this->secondname}";
        return $this->lastname . ' ' . mb_substr($this->firstname, 0, 1, 'utf-8') . '. ' . mb_substr($this->secondname, 0, 1, 'utf-8');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskNotes()
    {
        return $this->hasMany(TaskNotes::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredentials()
    {
        return $this->hasOne(AuthUser::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLead()
    {
        //if ($lead = AuthProfile::findOne(['user_id' => $this->lead_id])) return $lead;
        //return new AuthProfile();        
        return $this->hasOne(AuthProfile::className(), ['user_id' => 'lead_id'])->from(AuthProfile::tableName() . ' TL');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubordinates()
    {
        return $this->hasMany(AuthProfile::className(), ['lead_id' => 'user_id']);
    }
    
    /**
     * return string[] $item_name from auth_assignments table
     */
    public function getRoles()
    {
        return \yii\helpers\ArrayHelper::map(
            $this->hasMany(AuthAssignment::className(), ['user_id' => 'user_id'])
                ->select(['id'=>'item_name', 'value'=>'description'])
                ->join('left join', 'auth_item', 'auth_item.name = auth_assignment.item_name')
                ->asArray()->all(),
            'value', 'id');
    }
    public function setRoles($value) { $this->_roles = $value; }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'user_id']);
    }

}