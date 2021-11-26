<?php

namespace MetGS\Aws;
	
/**
* 自动观测站数据
* sz.metgs.com
* 2016-10-06
*/
class MAwsFactory
{
    public static function Create($sourceName)
    {
        if ( $sourceName == "MetGS" )
            return new MAwsMetGS();
        else if ( $sourceName == "ZheJiang" )
            return new MAwsZheJiang();

    }
}

?>