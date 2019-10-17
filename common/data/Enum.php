<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-9-26
 * Time: 上午11:25
 */

namespace common\data;


class Enum
{
    const IS_YSE = "y";
    const IS_NO = "n";

    public static function getIses()
    {
        return [
            self::IS_YSE => "是",
            self::IS_NO => "否",
        ];
    }
}