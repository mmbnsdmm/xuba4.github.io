<?php
/**
 * Created by PhpStorm.
 * User: Wodro
 * Date: 2020/8/14
 * Time: 14:27
 */

namespace common\models\interfaces;


interface SearchIndexInterface
{
    public function setSearchIndex();

    public function delSearchIndex();

    /**
     * @return array
     */
    public function getSearchIndexData();
}