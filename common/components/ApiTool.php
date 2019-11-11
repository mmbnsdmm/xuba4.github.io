<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-11-11
 * Time: 下午6:03
 */

namespace common\components;


use common\data\Enum;
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
    public $sms_app_id;
    public $sms_app_key;
    public $sms_template_id;
    public $sms_sign;
    public $bd_ak;

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

    public function signFormParams($form_params)
    {
        if (!\Yii::$app->user->isGuest){
            $user = \Yii::$app->user->identity;
            $form_params = $this->generateFormParams($user, $form_params);
        }
        return $form_params;
    }

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
     * @param $mobile
     * @param int $timeout
     * @param $smsCodeKey
     * @return array|mixed
     * @return int is_send
     * @return string msg
     * @throws
     */
    public function sendSmsCode($mobile, $timeout = 2, $smsCodeKey)
    {
        $r = [
            'is_ok' => 0,
            'msg' => "",
        ];
        $keys = Enum::getSmsCodeKeys();
        $val = $keys[$smsCodeKey];
        $query = LogSmsSendCode::find()->where(['nation_code' => LogSmsSendCode::NATION_CODE_CHINA, 'to_mobile' => $mobile, 'sms_key' => $smsCodeKey]);
        $send_total_in24 = $query->andWhere(['>', 'created_at', YII_BT_TIME - 86400])->count();
        if ($send_total_in24 >= 3){
            $r['msg'] = "24小时之内相同手机号发送{$val}验证码不能超过3次";
            return $r;
        }
        $lastSendLog = $query->orderBy(['id' => SORT_DESC])->one();
        if (YII_BT_TIME - $lastSendLog->created_at < 60 * $timeout){
            $r['msg'] = "{$timeout}分钟之内相同手机号不能重复发送{$val}验证码";
            return $r;
        }
        $code = rand(1000, 9999);
        $log = new LogSmsSendCode();
        $log->created_at = YII_BT_TIME;
        $log->nation_code = (string)LogSmsSendCode::NATION_CODE_CHINA;
        $log->to_mobile = (string)$mobile;
        $log->sms_key = $smsCodeKey;
        $log->code = (string)$code;
        $log->platform = LogSmsSendCode::PLATFORM_TENCENT;
        $log->params = json_encode([
            'sms_app_id' => $this->sms_app_id,
            'sms_template_id' => $this->sms_template_id,
            'timeout' => $timeout,
        ], JSON_UNESCAPED_UNICODE);
        if (YII_ENV_DEV){
            $_r = [];
            $_r['result'] = 0;
        }else{
            $sms = new SmsSingleSender($this->sms_app_id, $this->sms_app_key);
            $result = $sms->sendWithParam($log->nation_code, $mobile, $this->sms_template_id,
                [$code, $timeout], $this->sms_sign);
            $_r = json_decode($result, true);
        }
        $log->result = json_encode($_r, JSON_UNESCAPED_UNICODE);
        if (!$log->save()){
            throw new ApiException(201910251414, "验证码发送日志保存失败:".Model::getModelError($log));
        }
        if (isset($_r['result']) && $_r['result'] == 0){
            $r['is_send'] = 1;
            $r['msg'] = "{$val}验证码发送成功";
            if (YII_ENV_DEV)$r['msg'] = "测试环境{$val}验证码为{$code}，正式环境会直接把验证码发送到手机";
        }else{
            $r['msg'] = $_r['errmsg'];
        }
        return $r;
    }

    public function validateSmsCode($mobile, $smsCodeKey, $code)
    {
        $query = LogSmsSendCode::find()->where(['nation_code' => LogSmsSendCode::NATION_CODE_CHINA, 'to_mobile' => $mobile, 'sms_key' => $smsCodeKey]);
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
     * @return array
     */
    public function AuthReturn($user)
    {
        return [
            'token' => $user->token,
            'key' => $user->key,
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