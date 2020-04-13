<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/9
 * Time: 10:58
 */

namespace common\components;


use yii\web\IdentityInterface;

/**
 * Class User
 * @package common\components
 *
 * @property string|int $id The unique identifier for the user. If `null`, it means the user is a guest. This
 * property is read-only.
 * @property \common\models\db\User|null $identity The identity object associated with the currently logged-in
 * user. `null` is returned if the user is not logged in (not authenticated).
 * @property bool $isGuest Whether the current user is a guest. This property is read-only.
 * @property string $returnUrl The URL that the user should be redirected to after login. Note that the type
 * of this property differs in getter and setter. See [[getReturnUrl()]] and [[setReturnUrl()]] for details.
 * @property bool $isInConsole
 * @property string $loginIp
 */
class User extends \yii\web\User
{
    public $isInConsole = false;
    public $loginIp = '';

    /**
     * @param IdentityInterface $identity
     * @param int $duration
     * @return bool
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            if ($this->isInConsole){
                $this->loginIp = '0.0.0.0';
            }else{
                $this->loginIp = \Yii::$app->request->getUserIP();
            }
            $this->afterLogin($identity, false, $duration);
        }

        return !$this->getIsGuest();
    }
}