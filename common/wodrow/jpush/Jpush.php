<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/6/12
 * Time: 19:18
 */

namespace common\wodrow\components\jpush;


use JPush\Client;
use yii\base\Component;

/**
 * Class Jpush
 * @package common\wodrow\components\jpush
 *
 * @property Client $client
 */
class Jpush extends Component
{
    public $appKey;
    public $appSecret;
    /**
     * @var Client
     */
    protected $_client;

    public function init()
    {
        parent::init();
        $this->_client = new Client($this->appKey, $this->appSecret, \Yii::getAlias("@runtime/logs/jpush.log"), 5);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->_client;
    }
}