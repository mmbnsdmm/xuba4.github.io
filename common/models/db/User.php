<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-10-17
 * Time: 下午12:51
 */

namespace common\models\db;


use common\Tools;
use wodrow\yii2wtools\tools\Model;
use wodrow\yii2wtools\tools\Security;
use yii\web\IdentityInterface;

class User extends \common\models\db\tables\User implements IdentityInterface
{
    const STATUS_ACTIVE = 10;

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token, 'status' => static::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function setPassword($password)
    {
        $this->password = md5($password);
        $this->pwd_back = Security::think_encrypt($password);
    }

    public function setToken()
    {
        Model::setUniqueStrForModelKey($this, 'token');
        $this->key = \Yii::$app->security->generateRandomString();
    }
}