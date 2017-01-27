<?php

namespace app\modules\projects\models;

use Yii;

use app\modules\projects\Projects as Module;
use app\modules\projects\models\AuthProfile;

/**
 * This is the model class for table "{{%auth_users}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property string $group
 */
class AuthUser extends AuthActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * IdentityInterface
     */
    //public $id;
    //public $username;
    //public $password;
    //public $authKey;
    //public $accessToken;
    
    public static function findIdentity($id)
    {
        $dbUser = self::find()
                ->where([
                    "id" => $id
                ])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);        
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $dbUser = self::find()
                ->where(["accessToken" => $token])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);        
    }
    public static function findByUsername($username)
    {
        $dbUser = self::find()
                ->where([
                    //"username" => $username
                    // We want to login via id, not username
                    "id" => $username
                ])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);        
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->authKey;
    }
    public function getDisplayname()
    {
        return $this->profile->username;
    }
    public function getRoles()
    {
        return "asd";
    }    
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    public function validatePassword($password)
    {
        return $this->password === $password;
    }    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'username', 'password'], 'required'],
            [['disabled'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('app', 'User'),
            'password' => Module::t('app', 'Password'),
            'id' => Module::t('app', 'Login'),
            'disabled' => Module::t('app', 'Disabled'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        $profile = $this->hasOne(AuthProfile::className(), ['user_id' => 'id']);
        if ($profile->count() > 0) return $profile; // profile exists
        else return new AuthProfile(['user_id'=>$this->id]); // or create new one
    }
}