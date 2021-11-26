<?php
	/**
	 * Demo file to use mphp/Aws
	 *
	 * sz@metgs.com
	 * 2016-10-07
	 */


	// class Root{

	//     function sql(){
	//     	return "";
	//     }

	//     function dayin(){
	//         return $this->sql();
	//         //return "Root dayin()";
	//     }	    

	// }

	// class Son extends  Root{
	//  	function sql(){
	//     	return "Hello in son class";
	//     }
	// }
	// $s=new Son();
	// echo  $s->dayin();


	// return;







	require __DIR__."/metgs.php";

	//use MetGS\Aws;

	//$aws = \MetGS\Aws\MAwsFactory::Create("MetGS");
	$aws = \MetGS\Aws\MAwsFactory::Create("ZheJiang");
	
	$vars = $aws->getVariables();

    foreach($vars as $v)
	{
		echo $v['varId'] . '=>' . $v['name'] . "<br/>";
	}

	//$obj->Debug();

	$varId = "T";
	//$varId = "WindExMax";
	$dtEnd = new \DateTime('2016-10-24 07:00:00');
	//$dtEnd->setTime($dtEnd->format('H'),0,0);
	$hourSpan = 24;
	$statisticsFunction = "DIF";

	echo $dtEnd->format('y-m-d H:i:s') . '<hr>';

	//$aws->setPoliticalFilter(array('County'=>'平阳'));
	$aws->setPoliticalFilter(array('Prefecture'=>'温州'));
	$aws->setRectangleFilter(119.9, 121.2, 27.4, 27.8);
	$aws->setPoliticalFilterOn(true);
	$aws->setRectangleFilterOn(false);
	$ret = $aws->getSpaceSql($varId, $dtEnd, $hourSpan, $statisticsFunction);

	//return;

	if ( $ret->status != 0 )
		echo $ret->message;
	else
		echo $ret->sql;

?>