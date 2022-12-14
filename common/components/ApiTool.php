<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-11
 * Time: 下午6:03
 */

namespace common\components;


use common\models\db\LogEmailSendCode;
use common\models\db\User;
use common\models\db\UserFile;
use GuzzleHttp\Client;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Model;
use yii\base\Component;

/**
 * Class ApiTool
 * @package common\components
 *
 * @property-read string $randomAvatarUrl
 */
class ApiTool extends Component
{
    public $baseUri;
    public $apiUri;

    /**
     * @param $uri
     * @param $form_params
     * @return mixed
     * @throws
     */
    public function post($uri, $form_params, $user = null)
    {
        $client = new Client(['base_uri' => $this->getFullUrl($uri), 'verify'=>false]);
        $form_params = $this->signFormParams($form_params, $user);
        $resp = $client->request("POST", "", [
            'form_params' => $form_params,
        ]);
        $resp_content = $resp->getBody()->getContents();
        return json_decode($resp_content, true);
    }

    /**
     * @param $form_params
     * @return array
     */
    public function signFormParams($form_params, $user = null)
    {
        if ($user){
            $form_params = $this->generateFormParams($user, $form_params);
        }
        return $form_params;
    }

    /**
     * @param $user
     * @param array $form_params
     * @return array
     */
    public function generateFormParams($user, $form_params = [])
    {
        $form_params['token'] = $user->token;
        $form_params['timestamp'] = YII_BT_TIME;
        $form_params['nonce'] = $form_params['timestamp'].rand(10000000, 40000000);
        $p = $form_params;
        $p['key'] = $user->key;
        ksort($p);
        unset($p['sign']);
        $p_arr = [];
        foreach ($p as $k => $v) {
            $p_arr[] = $k."=".$v;
        }
        $p_str = ArrayHelper::arr2str($p_arr, '&');
        $form_params['sign'] = md5($p_str);
        return $form_params;
    }

    /**
     * @param $email
     * @param int $timeout
     * @param $smsCodeKey
     * @return array|mixed
     * @return int is_send
     * @return string msg
     * @throws
     */
    public function sendEmailCode($email, $typeKey, $timeout = 10)
    {
        $r = [
            'status' => 0,
            'msg' => "",
        ];
        $types = LogEmailSendCode::instance()->typeDesc;
        $val = $types[$typeKey];
        $query = LogEmailSendCode::find()->where(['to' => $email, 'type' => $typeKey]);
        $send_total_in24 = $query->andWhere(['>', 'created_at', YII_BT_TIME - 86400])->count();
        if ($send_total_in24 >= 3){
            $r['msg'] = "24小时之内相同邮箱发送{$val}验证码不能超过3次";
            return $r;
        }
        $lastSendLog = $query->orderBy(['id' => SORT_DESC])->one();
        if ($lastSendLog && YII_BT_TIME - $lastSendLog->created_at < 60 * $timeout){
            $r['msg'] = "{$timeout}分钟之内相同邮箱不能重复发送{$val}验证码";
            return $r;
        }
        $mail = \Yii::$app->mailer->compose();
        $log = new LogEmailSendCode();
        $log->created_at = YII_BT_TIME;
        $log->subject = "{$val}验证码";
        $log->from = json_encode($mail->getFrom(), JSON_UNESCAPED_UNICODE);
        $log->to = $email;
        $log->type = $typeKey;
        $log->code = rand(100000, 999999);
        if (YII_ENV_DEV){
            $log->status = LogEmailSendCode::STATUS_SEND_SUCCESS;
            $r['status'] = 200;
            $r['msg'] = "测试环境{$val}验证码为：{$log->code}，正式环境会直接把验证码发送到邮箱";
        }else{
            $mail->setTo($log->to);
            $mail->setSubject($log->subject);
            $mail->setTextBody($log->code);
            $_r = $mail->send();
            if ($_r){
                $r['status'] = 200;
                $r['msg'] = "发送{$val}验证码成功";
                $log->status = LogEmailSendCode::STATUS_SEND_SUCCESS;
            }else{
                $r['status'] = 0;
                $r['msg'] = "发送{$val}验证码失败";
            }
        }
        $log->params = json_encode([
            'timeout' => $timeout,
            'msg' => $r['msg'],
        ], JSON_UNESCAPED_UNICODE);
        if (!$log->save()){
            $r['status'] = 0;
            $r['msg'] = Model::getModelError($log);
        }
        return $r;
    }

    /**
     * @param $email
     * @param $typeKey
     * @param $code
     * @return bool
     */
    public function validateEmailCode($email, $typeKey, $code)
    {
        $query = LogEmailSendCode::find()->where(['to' => $email, 'type' => $typeKey]);
        $lastSendLog = $query->orderBy(['id' => SORT_DESC])->one();
        if (!$lastSendLog){
            return false;
        }
        if (YII_BT_TIME - $lastSendLog->created_at > 86400*2){
            return false;
        }
        if ($lastSendLog->code != $code){
            return false;
        }
        return true;
    }

    /**
     * @param User $user
     * @return array {'token': "令牌", 'key': "秘钥， 不要泄露", 'username': "用户名", 'email': "邮箱", 'amount': "余额", 'frozen': "冻结资金"}
     *
     */
    public function authReturn($user)
    {
        $r = [
            'id' => $user->id,
            'token' => $user->token,
            'key' => $user->key,
            'username' => $user->username,
            'nickName' => $user->nickName,
            'email' => $user->email,
            'amount' => $user->amount,
            'frozen' => $user->frozen,
            'status' => $user->status,
            'created_at' => $user->created_at,
//            'avatar' => UserFile::decodeContent($user->avatar),
            'avatar' => $user->avatar,
            'mobile' => $user->mobile,
            'weixin' => $user->weixin,
            'qq' => $user->qq,
            'alipay_exceptional_code' => $user->alipay_exceptional_code,
            'alipay_exceptional_url' => $user->alipay_exceptional_url,
            'weixin_exceptional_code' => $user->weixin_exceptional_code,
            'weixin_exceptional_url' => $user->weixin_exceptional_url,
            'signup_message' => $user->signup_message,
        ];
        return $r;
    }

    /**
     * @param $uri
     * @return string
     */
    public function getFullUrl($uri = "")
    {
        return $this->baseUri.$this->apiUri.$uri;
    }

    /**
     * @return string
     */
    public function getRandomAvatarUrl()
    {
        $random = rand(1001, 2387);
        return "@static_aburl/avatars/{$random}.jpg";
    }
}