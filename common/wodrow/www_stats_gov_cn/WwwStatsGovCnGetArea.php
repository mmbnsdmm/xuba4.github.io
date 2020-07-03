<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/4/10
 * Time: 16:10
 */

namespace common\wodrow\components;


interface WwwStatsGovCnGetArea
{
    public function handleArea($prov_name, $city_name, $city_id, $county_name, $county_id, $town_name, $town_id, $village_name, $village_id, $village_code);
}