<?php

// interface IAws
// {
//     /// 自动站小时数据
//     const AWS_INTERVAL_HOUR = 1;

//     /// 自动站分钟数据
//     const AWS_INTERVAL_MINUTE = 2;

//     //function SourceName();

//     /// 设置要使用的自动站数据间隔，小时数据还是分钟数据
//     function SetDataInterval();

//     /// 获取空间查询的SQL语句
//     function GetSpaceSql();

//     /// 获取单站时间序列查询的SQL语句
//     function GetTimeSeriesSql();
// }
	
// /**
// * 自动观测站数据
// * sz.metgs.com
// * 2016-10-06
// */
// class MAws
// {
//     private $_politicalFilter = "(county='平阳')";
//     private $_areaFilter = "(EEEee>11990 AND EEEee<12120 AND NNnn>2740 and NNnn<2780)";

    
//     protected $_dicVars = array(
//         //-- 气温类
//         'T'         => array('value'=>'0.1*DryBulTemp'),     //定时的空气温度值
//         'TAvg'      => array('value'=>'0.1*DryBulTemp'),     //定时的最低温度
//         'TMax'      => array('value'=>'0.1*MaxTemp','valueTime'=>'TimeMaxTemp'),        //定时的最高温度
//         'TMin'      => array('value'=>'0.1*MinTemp','valueTime'=>'TimeMinTemp'),        //定时的最低温度
        
//         //-- 风向风速类
//         'Wind2'     => array('value'=>'0.1*WindVelocity', 'value2'=>'WindDirect'),           //定时的2分钟风向风速
//         'Wind10'    => array('value'=>'0.1*WindVelocity10', 'value2'=>'WindDirect10'),         //定时10分钟平均风速风向
//         'WindMax'   => array('value'=>'0.1*MaxWindV','value2'=>'MaxWindD','valueTime'=>'TimeMaxWind'),   //定时的2分钟的平均风速
//         'WindExMax' => array('value'=>'ExMaxWindV','value2'=>'ExMaxWindD','valueTime'=>'TimeExMaxWind'),         //定时的极大风速的风向
        
//         //-- 降水类
//         'Pr'        => array('value'=>'0.1*Precipitation'),  //定时的小时雨量累计值
//         'PrMaxIntensity' => array('value'=>'0.1*Precipitation'),  //定时的小时雨量累计值
        
//         //-- 地温类
//         'TSfc'     => array('value'=>'0.1*SurfaceTemp'),    //定时的地面温度
//         'TSfcMax'  => array('value'=>'0.1*SurfaceMaxTemp','valueTime'=>'TimeSurMaxTemp'), //定时的地面最高温度
//         'TSfcMin'  => array('value'=>'0.1*SurfaceMinTemp','valueTime'=>'TimeSurMinTemp'), //定时的地面最低温度
//         'T5'       => array('value'=>'0.1*Temp5cm'),        //5cm地温
//         'T10'      => array('value'=>'0.1*Temp10cm'),        //10cm地温
        
//         //-- 相对湿度类
//         'Rh'       => array('value'=>'RelHumidity'),        //相对湿度
//         'RhMin'    => array('value'=>'MinRelHumidity','valueTime'=>'TimeMinRelH'),     //最小相对湿度
//         'TDew'     => array('value'=>'0.1*DewTemp'),        //露点温度
//         'TDewDiff' => array('value'=>'0.1*(DryBulTemp-DewTemp)'),        //温度露点差
        
//         //-- 气压
//         'P'        => array('value'=>'0.1*StationPress'),   //定时的本站气压值
//         'PMin'     => array('value'=>'0.1*MinPSta','valueTime'=>'TimeMinPSta'),   //最低本站气压值
//         'PMax'     => array('value'=>'0.1*MaxPSta','valueTime'=>'TimeMaxPSta'),   //最高本站气压值
//         'Slp'      => array('value'=>'0.1*SeaLevelPress'),  //定时的海平面气压值
        
//         //-- 能见度
//         'Vis'      => array('value'=>'VisiA'),               //水平能见度    
//         'VisMin'   => array('value'=>'MinVisiA','valueTime'=>'TimeMinVisiA'),              //水平能见度
        
//         //-- 蒸发量
//         'Eva'      => array('value'=>'EvagaugeAmount'),               //蒸发量            
//     );    

    
//     public function getData()
//     {
    	
//     }
   
   
    
//     //public function getSpaceData($varId = 'T', $dtEnd = null, $hourSpan = 0, $statFunction = 'SUM')
//     /**
//      * 
//      * {v1:value, v2:direction, v3:time}
//      */
//     public function getSpaceData()
//     {
//         //-- Force UTF8 output encoding
//         header("content-type:text/html; charset=utf-8");
            	
//     	//-- Parse input parameters
//     	//-- data source can be: 
//     	//   MG -> MetGS Aws database,
//         //   AWS-> MetGS Aws database,    	
//     	$dataSource =  strtoupper(\Request::input('dataSource','mg'));
//         $varId = \Request::input('varId','T');
//         $dtEnd   = \Request::input('dtEnd', date('ymdH'));
//         $hourSpan   = \Request::input('hourSpan',0);
//         $statFunction  =  strtoupper( \Request::input('statFunction','SUM') );   	
//         $dataFilter  =  strtoupper( \Request::input('filter','A') );
        
//         //-- 构造返回对象，对传入参数进行基本检查
//         $ret = new \stdClass;        
//         if (!array_key_exists($varId, $this->_dicVars) )
//         {
// 	        $ret->status = 1;
// 	        $ret->message = '找不到传入变量的定义 : $varId !';        	
//             return response()->json($ret); 
//         }
        
//         //-- 解析时间参数,如果失败，用最新时间
//         $dtEnd = \DateTime::createFromFormat('ymdH', $dtEnd);
//         if ( !$dtEnd )
//             $dtEnd = \DateTime::createFromFormat('ymdH', date('ymdH'));
       
//         //-- 构造数据过滤字符串
//         $dataFilter = ($dataFilter == "A")  ? ( $this->_areaFilter) : ( $this->_politicalFilter );        
        
//         //-- 确定数据源相关的表和参数
//         $tableStationInfo = "MG_StationInfo";  
//         $database = 'climate';      
//         if ( $dataSource == "AWS" )
//         {
//         	$database = 'station'; 
//             $tableStationInfo = "tab_StationInfo";
//             //-- There are named bug in zjszdzdb AWS table
//             $dataFilter = str_replace("county", "country",$dataFilter);  
//             $this->_politicalFilter =  str_replace("county", "country",$this->_politicalFilter);
//         }

//         //-- 构造变量查询字符串
//         $var = $this->_dicVars[$varId];
   	 
//         $hasValue2 = $hasValueTime = false;
//     	$strVar = $var['value'] . " as v1";
//     	$strStatVar = "v1";
//     	if ( array_key_exists('value2', $var) ){
//     	   $hasValue2 = true;
//     	   $strVar .= "," . $var['value'] . " as v2";
//     	   $strStatVar .= ",v2";
//     	}
//         if ( array_key_exists('valueTime', $var) ) {
//            $hasValueTime = true;
//            $strVar .= "," . $var['valueTime'] . " as v3";
//            $strStatVar .= ",v3";
//         }
    	
//     	//-- 从数据表中取出所有原始记录Select out all orignal records filtered by datetime and area
//     	$sql = $dataSource == "AWS" ? 
//     	   $this->BuildAwsRecordSqlForAWS($dtEnd, $hourSpan, $strVar, $dataFilter, $tableStationInfo) : 
//     	   $this->BuildAwsRecordSqlForMG($dtEnd, $hourSpan, $strVar, $dataFilter, $tableStationInfo);
    	
//     	//-- 如果需要进行统计，执行统计，If there are statistics function, just do statistics
//     	if ( $hourSpan > 0 )
//     	{
//     		if ( $statFunction == "SUM" || $statFunction == "AVG") {
//     			$strStatVar = "v1";
//     			$hasValue2 = $hasValueTime = false;
//                 $sql = "SELECT id, $statFunction(v1) as v1 FROM( " . $sql . ")Q1 GROUP BY id";
//     		}
//     		else if ($statFunction == "MIN" || $statFunction == "MAX") {
//     			//-- NOTE: rank(), dense_rank() will cause multiple max records, use row_number() here
//     			$strRankOrder = $statFunction == "MIN" ? "DESC" : "ASC";
//     			$sql = "SELECT id, t, $strStatVar, row_number() OVER ( partition BY id ORDER BY v1 $strRankOrder ) AS ROrder FROM ( $sql )Q1A";
//     			$sql = "SELECT * from ( $sql ) Q1 WHERE ROrder = 1";
//     		}
//     	}
    	
//     	//-- 将查询结果与站点信息关联Query station information
//     	$sql = "SELECT id, RTRIM(StationName) as name, IIF(" . $this->_politicalFilter . ",1,0) as p, 0.01*EEEee as x, 0.01*NNnn as y, $strStatVar from ( $sql )Q2 
//     	   inner join $tableStationInfo as S on Q2.id = S.IIiii";
// //    	
//     	echo $sql . "\n\n<hr>";
//     	return;
    	
//     	//-- 执行查询并返回结果，Query database and construct JSON object
//         $result = \DB::connection($database)->select($sql);
//         $ret = new \stdClass;
//         $ret->status = 0;
//         $ret->message = '';
//         $ret->dateEnd = $dtEnd->format('Y-m-d H:i:s');
//         $ret->hourSpan = $hourSpan;
//         $ret->hasValue2 = $hasValue2;
//         $ret->hasValueTime = $hasValueTime;        
//         $ret->data = $result;

//         return response()->json($ret); 
//     }
    
//     public function getTimeseriesData()
//     {
// //            $param = json_decode( $_POST['Param'], true );
// //            $county = $param["CountyName"];
// //            $var = $param["AwsVarName"];
// //                $ll = new DateTime();
// //            if ($param["ObservTime"] === "" || $param["ObservTime"]>$ll->format('ymdH')){
// //                $dt = new DateTime();
// //                //-- Latest hour
// //                if ( $dt->format('i')<="15" )
// //                    $dt->sub(new DateInterval('PT1H'));             
// //            }
// //            else
// //                $dt = DateTime::createFromFormat('ymdH', $param['ObservTime']);             	
//     }
    
//     /**
//      * 
//      * Enter description here ...
//      * @param unknown_type $dtEnd
//      * @param unknown_type $hourSpan
//      */
//     private function BuildAwsRecordSqlForMG($dtEnd, $hourSpan, $strVar, $dataFilter, $tableStationInfo)
//     {    
//     	//-- 时间过滤字符串   	
//         if ( $hourSpan > 0 )
//         {
//             $dtStart = clone $dtEnd;
//             $dtStart->sub(new \DateInterval("PT" . $hourSpan . "H"));
            
//             $whereDate = "(ObservTimes>'" . $dtStart->format('Y-m-d H:i:s') . 
//                         "' AND ObservTimes<='" . $dtEnd->format('Y-m-d H:i:s') . "')";
//         }
//         else
//         {
//         	$whereDate = "ObservTimes='" . $dtEnd->format('Y-m-d H:i:s') . "'";
//         }
                    	
//         //-- 从数据表中取出所有原始记录Select out all orignal records filtered by datetime and area
//         $sql = "SELECT ObservTimes as t, StationNum as id, $strVar FROM MG_AwsHour as O INNER JOIN" .
//            " $tableStationInfo as S ON O.StationNum=S.IIiii WHERE $dataFilter AND $whereDate ";
        
//         return $sql;
//     }
    
//     private function BuildAwsRecordSqlForAWS($dtEnd, $hourSpan, $strVar, $dataFilter, $tableStationInfo)
//     {   
//     	//-- Tables will involve    	
//     	$tables = array();
    	
//         //-- 如果时间跨度为０,则为固定时刻查询
//         if ( $hourSpan <= 0 )
//         {
//             $dataFilter .= " AND ObservTimes='" . $dtEnd->format('ymdH') . "'";
                    	
//             //-- 每个月的最后3个小时（21/22/23时）数据存储到下一个月的表中
//             $ym = $dtEnd->format('Ym');
//             if ( $ym != $dtEnd->add(new \DateInterval('PT3H'))->format('Ym') ) 
//                 $ym = $dtEnd->format('Ym');
//             array_push($tables, $ym);
//         }
//         else  //-- 如果时间跨度非０,为区间时段查询
//         {
//             $dtStart = clone $dtEnd;
//             $dtStart->sub(new \DateInterval("PT" . $hourSpan . "H"));
            
//             $dataFilter .= " AND (ObservTimes>'" . $dtStart->format('ymdH') . 
//                         "' AND ObservTimes<='" . $dtEnd->format('ymdH') . "')";  

//             while( $dtEnd->getTimeStamp() > $dtStart->getTimeStamp() )
//             {
//               $ym = $dtEnd->format('Ym');
//               if ( $ym != $dtEnd->add(new \DateInterval('PT3H'))->format('Ym') ) 
//                  $ym = $dtEnd->format('Ym');
                 
//               if ( !in_array($ym,$tables) )   
//                 array_push($tables, $ym);
              
//               //-- Decrease one hour, (it's 4 becase last line had add 3h) 
//               $dtEnd->sub(new \DateInterval('PT4H'));
//             }
            
//         }
        
// //        var_dump($tables);
// //        return "++";
     
//         //-- 对所有涉及的表构造查询语句
//         $sql = "";
//         foreach($tables as $ym)
//         {
//             $sql .= (empty($sql) ? "" : " UNION ALL ") . 
//                    $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabRealTimeData_$ym", $tableStationInfo)
//                    . " UNION ALL " .
//                    $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabRealTimeDataMws_$ym", $tableStationInfo)
//                    . " UNION ALL " .
//                    $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabAreaAws_$ym", $tableStationInfo)
//                    . " UNION ALL " .
//                    $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabAreaMws_$ym", $tableStationInfo);                                   
//         }    
        
//         return $sql;
//     }    

//     private function BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, $tableData, $tableStationInfo)
//     {  
//         return "SELECT ObservTimes as t, StationNum as id, $strVar FROM $tableData as O INNER JOIN" .
//            " $tableStationInfo as S ON O.StationNum=S.IIiii WHERE $dataFilter";    	
//     }    

// }

?>