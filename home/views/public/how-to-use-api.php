<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-30
 * Time: 上午11:35
 * @var \yii\web\View $this
 */
$this->title = "如何对接api平台";
$this->params['breadcrumbs'][] = $this->title;
$dev_url = \Yii::$app->api_tool->base_uri;
$markdown = <<<STR
### 环境配置

测试url: <{$dev_url}>

api路由文档: <{$dev_url}/route/api>

### 不需要认证的接口 

`/public/*` `/site/*`

### 认证方式

#### 注册用户

详见[用户注册]({$dev_url}/route/api#/site/signup)

#### 登录

详见[用户账号登录]({$dev_url}/route/api#/site/login1) [手机号快速登录](http://tx.dev.anshuoyun.tk/route/api#/site/login2)
用于获取 `token` `key` 以及其他用户数据

#### 请求数据签名

例如你要携带请求参数`{a:1, b:2}`请求`{$dev_url}/{api路由}`。

首先需要把你的`token`，`key`，`timestamp`(当前时间戳,保留10位秒数)，`nonce`(随机数)和请求参数组成一个新的对象`{a:1, b:2, token:token, key:key, timestamp: timestamp, nonce:nonce}`

数组按照键名从小到达排序`{a:1, b:2, key:key, nonce:nonce, token:token, timestamp: timestamp}`

数组按照`key=value`用`&`拼接成新的字符串`a=1&b=2&key=key&nonce=nonce&token=token&timestamp= timestamp`

生成签名`sign=md5(上面生成的字符串)`

把`token`，`timestamp`，`nonce`, `sign`和请求参数`{a:1, b:2}`组成一个新的请求参数`{a:1, b:2, nonce:nonce, token:token, timestamp: timestamp, sign:sign}`就可以成功进行请求操作了

### 你可以参考postman的签名方式

```
var _token = pm.environment.get("token");
var _key = pm.environment.get("key");
var _timestamp = (new Date()).valueOf();
_timestamp = _timestamp/1000;
_timestamp = Math.floor(_timestamp);
var _nonce = _timestamp + Math.random() + Math.random() + Math.random() + Math.random();
request.data["key"] = _key;
delete request.data["sign"];
var keys = Object.keys(request.data), i, len = keys.length;
keys.sort();
var _sign = "";
var firstpass = true;
for(var index in keys){
    if(!firstpass){
        _sign += "&";
    }
    if(keys[index]=="token"){
        request.data[keys[index]] = _token;
    }
    if(keys[index]=="timestamp"){
        request.data[keys[index]] = _timestamp;
    }
    if(keys[index]=="nonce"){
        request.data[keys[index]] = _nonce;
    }
    _sign += keys[index] + "=" + request.data[keys[index]];
    firstpass = false;
}
_sign = CryptoJS.MD5(_sign).toString();
pm.environment.set("timestamp", _timestamp);
pm.environment.set("nonce", _nonce);
pm.environment.set("sign", _sign);
```
STR;

?>

<div class="public-how-to-use-api">
    <div class="row">
        <div class="col-lg-12">
            <?=\yii\helpers\Markdown::process($markdown, 'extra') ?>
        </div>
    </div>
</div>
