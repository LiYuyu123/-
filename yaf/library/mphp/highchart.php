<?php
    require __DIR__."/metgs.php";

    $aws = \MetGS\Aws\MAwsFactory::Create("ZheJiang");

    $varId = "Wind10";
    $stid = "K3152";
    $hourSpan = 0;
    $daySpan = 1;
    $timeFixEnd = -1;
    $dtEnd = new \DateTime('2016-11-03 12:00:00');

    $sqlFunc = '';

    $ret = $aws->getHighchartDataSql($stid, $varId, $dtEnd, $hourSpan, $daySpan, $sqlFunc, $timeFixEnd);

    echo $ret->sql;





