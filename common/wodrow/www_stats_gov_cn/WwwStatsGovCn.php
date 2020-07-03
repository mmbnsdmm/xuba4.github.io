<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/9
 * Time: 16:04
 */

namespace common\wodrow\www_stats_gov_cn\components;


use GuzzleHttp\Exception\ServerException;
use QL\QueryList;
use wodrow\yii2wtools\tools\Tools;
use yii\base\Component;

/**
 * Class WwwStatsGovCn
 * @package common\wodrow\components
 *
 * @property WwwStatsGovCnGetArea|null $model
 */
class WwwStatsGovCn extends Component
{
    public $getAreaUrlPre = "http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019";
    public $showLog = 1;
    public $isCache = 1;

    public $model;

    /**
     * @throws
     */
    public function getAreaList()
    {
        $urlPre = $this->getAreaUrlPre;
        $china_url = $urlPre."/index.html";
        $provs = $this->_forceQlTr("WwwStatsGovCn-provs", $china_url, [
            'name' => ['tr.provincetr>td>a', 'text'],
            'href' => ['tr.provincetr>td>a', 'href'],
        ]);
        foreach ($provs as $k => $v){
            if (!$v['href'])continue;
            $prov_url = dirname($china_url)."/".$v['href'];
            if ($this->showLog)var_dump("prov:{$v['name']}");
            if ($this->showLog)var_dump($prov_url);
            $citys = $this->_forceQlTr("WwwStatsGovCn-citys-{$v['name']}", $prov_url, [
                'tr' => ['tr.citytr', 'html'],
            ]);
            foreach ($citys as $k1 => $v1) {
                $city = QueryList::getInstance()->html($v1['tr'])->rules([
                    'id' => ['td:eq(0)>a', 'text'],
                    'name' => ['td:eq(1)>a', 'text'],
                    'href' => ['td:eq(1)>a', 'href'],
                ])->queryData();
                if (!$city[0]['href'])continue;
                $city_url = dirname($prov_url)."/".$city[0]['href'];
                if ($this->showLog)var_dump("city:{$city[0]['name']}");
                if ($this->showLog)var_dump($city_url);
                $countys = $this->_forceQlTr("WwwStatsGovCn-countys-{$city[0]['id']}", $city_url, [
                    'tr' => ['tr.countytr', 'html'],
                ]);
                foreach ($countys as $k2 => $v2) {
                    $county = QueryList::getInstance()->html($v2['tr'])->rules([
                        'id' => ['td:eq(0)>a', 'text'],
                        'name' => ['td:eq(1)>a', 'text'],
                        'href' => ['td:eq(1)>a', 'href'],
                    ])->queryData();
                    if (!$county[0]['href'])continue;
                    $town_url = dirname($city_url)."/".$county[0]['href'];
                    if ($this->showLog)var_dump("county:{$county[0]['name']}");
                    if ($this->showLog)var_dump($town_url);
                    $towns = $this->_forceQlTr("WwwStatsGovCn-towns-{$county[0]['id']}", $town_url, [
                        'tr' => ['tr.towntr', 'html'],
                    ]);
                    foreach ($towns as $k3 => $v3) {
                        $town = QueryList::getInstance()->html($v3['tr'])->rules([
                            'id' => ['td:eq(0)>a', 'text'],
                            'name' => ['td:eq(1)>a', 'text'],
                            'href' => ['td:eq(1)>a', 'href'],
                        ])->queryData();
                        if (!$town[0]['href'])continue;
                        $village_url = dirname($town_url)."/".$town[0]['href'];
                        if ($this->showLog)var_dump("town:{$town[0]['name']}");
                        if ($this->showLog)var_dump($village_url);
                        $villages = $this->_forceQlTr("WwwStatsGovCn-villages-{$town[0]['id']}", $village_url, [
                            'tr' => ['tr.villagetr', 'html'],
                        ]);
                        foreach ($villages as $k4 => $v4) {
                            $village = QueryList::getInstance()->html($v4['tr'])->rules([
                                'id' => ['td:eq(0)', 'text'],
                                'code' => ['td:eq(1)', 'text'],
                                'name' => ['td:eq(2)', 'text'],
                            ])->queryData();
                            $_village = "{$v['name']} {$city[0]['name']}({$city[0]['id']}) {$county[0]['name']}({$county[0]['id']}) {$town[0]['name']}({$town[0]['id']}) {$village[0]['name']}({$village[0]['id']})(code:{$village[0]['code']})";
                            if ($this->showLog)var_dump($_village);
                            $this->_checkId($city[0]['id'], $county[0]['id'], $town[0]['id'], $village[0]['id']);
                            if ($this->model){
                                $this->model->handleArea($v['name'], $city[0]['name'], $city[0]['id'], $county[0]['name'], $county[0]['id'], $town[0]['name'], $town[0]['id'], $village[0]['name'], $village[0]['id'], $village[0]['code']);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $cacke_key
     * @param $url
     * @param $rules
     * @return array|mixed
     */
    protected function _forceQlTr($cacke_key, $url, $rules)
    {
        if (!$this->isCache)\Yii::$app->cache->delete($cacke_key);
        $arr = \Yii::$app->cache->get($cacke_key);
        if (!$arr){
            try{
                $arr = QueryList::getInstance()->get($url)->encoding('UTF-8', 'GB2312')->rules($rules)->query()->getData()->all();
                \Yii::$app->cache->set($cacke_key, $arr, 86400*180);
            }catch (ServerException $e){
                Tools::log($url, "WwwStatsGovCn-GuzzleHttp-ServerException");
                Tools::log($e->getMessage(), "WwwStatsGovCn-GuzzleHttp-ServerException");
                $arr = $this->_forceQlTr($cacke_key, $url, $rules);
            }
        }
        return $arr;
    }

    /**
     * @param $city_id
     * @param $county_id
     * @param $town_id
     * @param $village_id
     * @throws
     */
    protected function _checkId($city_id, $county_id, $town_id, $village_id)
    {
        if ((int)($village_id / 1000) != (int)($town_id / 1000)){
            throw new \yii\base\Exception("village,town键不匹配");
        }
        if ((int)($town_id / 1000000) != (int)($county_id / 1000000)){
            throw new \yii\base\Exception("town, county键不匹配");
        }
        if ((int)($county_id / 1000000000) != (int)($city_id / 1000000000)){
            throw new \yii\base\Exception("county, city键不匹配");
        }
    }
}