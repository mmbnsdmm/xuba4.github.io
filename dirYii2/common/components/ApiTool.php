<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-11
 * Time: 下午6:03
 */

namespace common\components;


use common\data\Enum;
use common\models\db\LogEmailSendCode;
use common\models\db\User;
use common\models\db\UserFile;
use GuzzleHttp\Client;
use Mimey\MimeTypes;
use wodrow\yii\rest\ApiException;
use wodrow\yii2wtools\tools\ArrayHelper;
use wodrow\yii2wtools\tools\Model;
use yii\base\Component;

class ApiTool extends Component
{
    public $base_uri;

    /**
     * @param $uri
     * @param $form_params
     * @return mixed
     * @throws
     */
    public function post($uri, $form_params)
    {
        $client = new Client(['base_uri' => $this->base_uri, 'verify'=>false]);
        $form_params = $this->signFormParams($form_params);
        $resp = $client->request("POST", $uri, [
            'form_params' => $form_params,
        ]);
        $resp_content = $resp->getBody()->getContents();
        return json_decode($resp_content, true);
    }

    /**
     * @param $form_params
     * @return array
     */
    public function signFormParams($form_params)
    {
        if (!\Yii::$app->user->isGuest){
            $user = \Yii::$app->user->identity;
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
        $form_params['nonce'] = $form_params['timestamp'].rand(1000, 4000);
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
            'is_ok' => 0,
            'msg' => "",
        ];
        $types = LogEmailSendCode::getTypes();
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
        $log->code = \Yii::$app->security->generateRandomString();
        if (YII_ENV_DEV){
            $log->status = LogEmailSendCode::STATUS_SEND_SUCCESS;
            $r['is_ok'] = 1;
            $r['msg'] = "测试环境{$val}验证码为：{$log->code}，正式环境会直接把验证码发送到邮箱";
        }else{
            $mail->setTo($log->to);
            $mail->setSubject($log->subject);
            $mail->setTextBody($log->code);
            $_r = $mail->send();
            if ($_r){
                $r['is_ok'] = 1;
                $r['msg'] = "发送{$val}验证码成功";
                $log->status = LogEmailSendCode::STATUS_SEND_SUCCESS;
            }else{
                $r['is_ok'] = 0;
                $r['msg'] = "发送{$val}验证码失败";
            }
        }
        $log->params = json_encode([
            'timeout' => $timeout,
            'msg' => $r['msg'],
        ], JSON_UNESCAPED_UNICODE);
        if (!$log->save()){
            $r['is_ok'] = 0;
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
     * @return array {'token': "令牌", 'key': "秘钥， 不要泄露", 'username': "用户名", 'email': "邮箱", 'amount': "余额", 'frozen': "冻结资金", 'deposit': 保证金"}
     *
     */
    public function authReturn($user)
    {
        return [
            'token' => $user->token,
            'key' => $user->key,
            'username' => $user->username,
            'email' => $user->email,
            'amount' => $user->amount,
            'frozen' => $user->frozen,
            'deposit' => $user->deposit,
        ];
    }

    /**
     * @param null $base64
     * @param null $url
     * @param null $filename
     * @param int $url_file_download
     * @return array
     * @throws
     */
    public function fileSave($base64 = null, $url = null, $filename = null, $url_file_download = 0)
    {
        $r = [
            'is_ok' => 0,
            'msg' => "失败",
        ];
        $rurl = [];
        $mimes = new MimeTypes();
        if ($base64){
            $mime_type = mime_content_type($base64);
            $match = preg_match('/^(data:\s*(\w+)\/([\w|-]+);base64,)/', $base64, $result);
            if(!$match){
                $r['msg'] = "信息匹配失败";
                return $r;
            }
            $extension = $mimes->getExtension($mime_type);
            $x= str_replace($result[1], '', $base64);
            $content = base64_decode($x);
            $rurl[] = UserFile::upload($filename, $extension, $content);
        }else{
            if ($url){
                if ($url_file_download){
                    $extension = substr(strrchr($url, '.'), 1);
                    $content = file_get_contents($url);
                    $rurl[] = UserFile::upload($filename, $extension, $content);
                }else{
                    $rurl[] = $url;
                }
            }else{
                if ($_FILES){
                    if (!isset($_FILES['ufile'])){
                        $r['msg'] = "表单字段必须为ufile或ufile[]";
                        return $r;
                    }
                    $ufile = $_FILES['ufile'];
                    $ufiles = [];
                    if (is_array($ufile['name'])){
                        $total = count($ufile['name']);
                        $keys = array_keys($ufile);
                        for ($i = 0; $i < $total; $i++){
                            $x = [];
                            foreach ($keys as $k => $v){
                                $x[$v] = $ufile[$v][$i];
                            }
                            $ufiles[] = $x;
                        }
                    }else{
                        $ufiles[] = $ufile;
                    }
                    foreach ($ufiles as $k => $v){
                        $rurl[] = UserFile::upload(null, substr(strrchr($v['name'], '.'), 1), null, $v['tmp_name']);
                    }
                }else{
                    $r['msg'] = "url,base64,表单上传必须选其一";
                    return $r;
                }
            }
        }
        $r['is_ok'] = 1;
        $r['msg'] = "成功";
        $r['urls'] = $rurl;
        return $r;
    }
}