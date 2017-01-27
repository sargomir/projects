<?php
/*
 * Deprecated module. Using AuthUser instead. To be deleted.
 */

namespace app\modules\projects\models;

use app\modules\projects\models\AuthUser as DbUser;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        $dbUser = DbUser::find()
                ->where([
                    "id" => $id
                ])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);        
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //foreach (self::$users as $user) {
        //    if ($user['accessToken'] === $token) {
        //        return new static($user);
        //    }
        //}
        //return null;

        $dbUser = DbUser::find()
                ->where(["accessToken" => $token])
                ->one();
        if (!count($dbUser)) {
            return null;
        }
        return new static($dbUser);        
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        //foreach (self::$users as $user) {
        //    if (strcasecmp($user['username'], $username) === 0) {
        //        return new static($user);
        //    }
        //}
        //
        //return null;
        $dbUser = DbUser::find()
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

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
