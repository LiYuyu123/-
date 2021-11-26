<?php

namespace MetGS\Aws;
	
/**
* 自动观测站数据, 浙江省气象局网络中心版本数据库zjszdzdb
*
* 该类只负责生成查询的SQL语句，具体查询参数设置和执行查询过程
* 由调用的类完成。
*
* sz.metgs.com
* 2016-10-07
*/
//implements IAws
class MAwsZheJiang extends MAwsBase
{
	/*
	 * 设置行政区划的过滤条件，省/市/县/乡镇名称
	 * 有两个作用：
	 *　（１）判断返回的站点是否在行政区域内
	 *　（２）用行政区域范围过滤返回数据
	 *
	 * 输入参数格式为：
	 *　　$filter = array('Province'=>'','Prefecture'=>'','County'=>'')
	 *　行政区域过滤条件优先级：省级>市级>县级，只需指定一个行政区域的名称
	 *　如只返回杭州市的数据：　array('Prefecture'=>'杭州')
	 *　如只返回平阳县的数据：　array('County'=>'平阳')
	 */
	public function setPoliticalFilter( $filter )
	{
		if ( array_key_exists("Province", $filter) )
		{
			$this->_politicalFilter = "(Province = '" . $filter["Province"] . "')";
			return;
		}

		if ( array_key_exists("Prefecture", $filter) )
		{
			$this->_politicalFilter = "(City = '" . $filter["Prefecture"] . "')";
			return;
		}

		if ( array_key_exists("County", $filter) )
		{
			$this->_politicalFilter = "(country = '" . $filter["County"] . "')";
			return;
		}
	}

    /*
     *启用是否添加周边数据
     */
    public function setAreaJudger( $filter )
    {
        if ( array_key_exists("Province", $filter) )
        {
            $this->_politicalFilter = "(Province = '" . $filter["Province"] . "')";
            return;
        }

        if ( array_key_exists("Prefecture", $filter) )
        {
            $this->_politicalFilter = "(City = '" . $filter["Prefecture"] . "')";
            return;
        }

        if ( array_key_exists("County", $filter) )
        {
            $this->_aroundJudger = "(country = '" . $filter["County"] . "')";
            return;
        }
    }

    /**
     * 不同的实现方式，站点表名不一样
     */
    protected function getStationInfoTable()
    {          
        return "tab_StationInfo";
    }
   
    
    /**
     * Class used internelly
     */
    protected function buildAwsRecordSql($dtEnd, $hourSpan, $strVar, $strStatVar, $statisticsFunction, 
        $dataFilter, $tableStationInfo, $movingConfig = null)
    {   
    	$delta = 0;
        if ( $hourSpan > 0 && null != $movingConfig ){
    		$delta = $movingConfig['step'];
    	}

    	//-- Tables will involve    	
    	$tables = array();
    	
        //-- 如果时间跨度为０,则为固定时刻查询
        if ( $hourSpan <= 0 )
        {
            $dataFilter .= " AND ObservTimes='" . $dtEnd->format('ymdH') . "'";
                    	
            //-- 每个月的最后一天的最后3个小时（21/22/23时）数据存储到下一个月的表中
            $ym = $dtEnd->format('Ym');
            if ( $ym != $dtEnd->add(new \DateInterval('PT3H'))->format('Ym') ) 
                $ym = $dtEnd->format('Ym');
            array_push($tables, $ym);
        }
        else  //-- 如果时间跨度非０,为区间时段查询
        {
            $dtStart = clone $dtEnd;
            $dtStart->sub(new \DateInterval("PT" . ($hourSpan+$delta) . "H"));
            
            if ( $statisticsFunction == "DIF" )
            {
                $dataFilter .= " AND (ObservTimes='" . $dtStart->format('ymdH') . 
                            "' OR ObservTimes='" . $dtEnd->format('ymdH') . "')";  
            }
            else 
            {
                $dataFilter .= " AND (ObservTimes>'" . $dtStart->format('ymdH') . 
                            "' AND ObservTimes<='" . $dtEnd->format('ymdH') . "')";  
            }                        

            while( $dtEnd->getTimeStamp() > $dtStart->getTimeStamp() )
            {
              $ym = $dtEnd->format('Ym');
              if ( $ym != $dtEnd->add(new \DateInterval('PT3H'))->format('Ym') ) 
                 $ym = $dtEnd->format('Ym');
                 
              if ( !in_array($ym,$tables) )   
                array_push($tables, $ym);
              
              //-- Decrease one hour, (it's 4 becase last line had add 3h) 
              $dtEnd->sub(new \DateInterval('PT4H'));
            }
            
        }
            
        //-- 对所有涉及的表构造查询语句
        $sql = "";
        foreach($tables as $ym)
        {
            // $sql .= (empty($sql) ? "" : " UNION ALL ") . 
            //         $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabRealTimeData_$ym", $tableStationInfo);       	
            $sql .= (empty($sql) ? "" : " UNION ALL ") . 
                   $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabRealTimeData_$ym", $tableStationInfo)
                   . " UNION ALL " .
                   $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabRealTimeDataMws_$ym", $tableStationInfo)
                   . " UNION ALL " .
                   $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabAreaAws_$ym", $tableStationInfo)
                   . " UNION ALL " .
                   $this->BuildAwsRecordSqlForAWSTable($strVar, $dataFilter, "tabAreaMws_$ym", $tableStationInfo);
        }


        //-- 日期字符串转datetime类型
        if ( $hourSpan > 0 && $statisticsFunction == "DIF" ){
            $sql = "select  t=convert(datetime, '20'+left(t,2) + '-' + SUBSTRING(t,3,2) + '-' + SUBSTRING(t,5,2) + ' ' + SUBSTRING(t,7,2) + ':00:00', 120), id, v1 from ( $sql )qtime";
        }

        //-- Moving average / moving sum
        if ( $hourSpan > 0 && null != $movingConfig ){
    		$method = $movingConfig['method'];

			$sql = "select id, t, $method(v1) over(partition by id order by t asc rows between " . ( $delta-1 ) . 
				" preceding and current row) as v1" . (empty($strStatVar) ? "" : ",$strStatVar") . " from(" .
				$sql . ")QMA WHERE T>'" . $dtStart->add(new \DateInterval("PT" . $delta . "H"))->format('ymdH') . "'";
		}
        
        return $sql;
    }    

    private function buildAwsRecordSqlForAWSTable($strVar, $dataFilter, $tableData, $tableStationInfo)
    {  
        return "SELECT ObservTimes as t, StationNum as id, $strVar FROM $tableData as O INNER JOIN" .
           " $tableStationInfo as S ON O.StationNum=S.IIiii WHERE $dataFilter";    	
    }

    protected function buildAwsChartRecordSql ($dtEnd, $hourSpan, $daySpan, $strVar, $strStatVar, $sqlFunc, $dataFilter, $timeFixEnd, $movingConfig)
    {
        $delta = 0;
        if ( $hourSpan > 0 && null != $movingConfig ){
            $delta = $movingConfig['step'];
        }

        $dtStart = clone $dtEnd;
        $dtStart = $dtStart->sub(new \DateInterval('P'. $daySpan .'D'));

        $tables = [];

        if ( $timeFixEnd > -1 && $timeFixEnd < 100 ) {
            if ($dtEnd->format('Y-m-d H:i:s') == date('Y-m-d H:00:00', time()))
                $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymd'.$timeFixEnd) ."' AND ObservTimes <= '". $dtEnd->format('ymdH') ."')";
            else 
                $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymd'.$timeFixEnd) ."' AND ObservTimes <= '". $dtEnd->format('ymd'.$timeFixEnd) ."')";
            $ym = $dtStart->format('Ym');
            if ( $ym != $dtStart->add(new \DateInterval('PT4H'))->format('Ym') ) {
                $ym = $dtEnd->format('Ym');
                array_push($tables, $ym);
            } else {
                $ym = $dtStart->format('Ym');
                array_push($tables, $ym);
                $ym = $dtEnd->format('Ym');
                if ( !in_array($ym,$tables) )   
                    array_push($tables, $ym);
            }
        } else {
            if ($hourSpan > 0) {
                $dtStart = $dtStart->sub(new \DateInterval('PT'. ((int)$hourSpan + $delta) .'H'));
                $delta = $hourSpan;
            }
            $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymdH') ."' AND ObservTimes <= '". $dtEnd->format('ymdH') ."')";
            $ym = $dtStart->format('Ym');
            if ( $ym != $dtStart->add(new \DateInterval('PT4H'))->format('Ym') ) {
                $ym = $dtEnd->format('Ym');
                array_push($tables, $ym);
            } else {
                $ym = $dtStart->format('Ym');
                array_push($tables, $ym);
                $ym = $dtEnd->format('Ym');
                if ( !in_array($ym,$tables) )   
                    array_push($tables, $ym);
            }
        }
    
        $sql = "";
        foreach ($tables as $ym) {
            $sql .= (empty($sql) ? "" : " UNION ALL ") . $this->buildAwsHighchartsTablesSql($strVar, $ym, $dataFilter);
        }
        
        if ($delta > 0 && null == $movingConfig) {
            if ($sqlFunc != 'DIF') {
                $sql = "SELECT $sqlFunc(v1) over( order by t asc rows between " . ( $delta-1 ) . " preceding and current row) as v1, t" . (empty($strStatVar) ? "" : ",$strStatVar") . " from(" . $sql . ")QRA";
                $sql = "SELECT * FROM ($sql) QRB WHERE t > '".$dtEnd->sub(new \DateInterval('P'. $daySpan .'D'))->format('Y-m-d H:i:s')."'";
            } else {
                $sql = "SELECT (q.v1-qprev.v1) AS v1,q.t FROM ($sql) q INNER JOIN ( SELECT t = DATEADD(hour, $hourSpan, t),v1 FROM ($sql)qprevtmp ) qprev ON q.t = qprev.t"; 
            }
        }

        //-- Moving average / moving sum
        if ( $hourSpan > 0 && null != $movingConfig ) {
            $method = $movingConfig['method'];
            $delta = $movingConfig['step'];
            $sql = "SELECT $method(v1) over( order by t asc rows between " . ( $delta - 1 ) . " preceding and current row) as v1, t" . (empty($strStatVar) ? "" : ",$strStatVar") . " from(" . $sql . ")QRA ";
            $sql = "SELECT $sqlFunc(v1) over( order by t asc rows between " . ( $hourSpan - 1 ) . " preceding and current row) as v1, t" . (empty($strStatVar) ? "" : ",$strStatVar") . " from(" . $sql . ")QRB ";
            $sql = "SELECT * FROM ($sql) QRB WHERE t > '".$dtEnd->sub(new \DateInterval('P'. $daySpan .'D'))->format('Y-m-d H:i:s')."'";
        }

        return $sql;

    }

    protected function buildAwsHighchartsTablesSql ($strVar, $ym, $dataFilter) 
    {
        return "SELECT $strVar FROM tabRealTimeData_$ym {$dataFilter}
                UNION ALL 
                SELECT $strVar FROM tabRealTimeDataMws_$ym {$dataFilter}
                UNION ALL
                SELECT $strVar FROM tabAreaAws_$ym {$dataFilter}
                UNION ALL
                SELECT $strVar FROM tabAreaMws_$ym {$dataFilter}
                ";
    }

    protected function buildFormatObservTimes () 
    {
        return "DATEADD(HOUR, CONVERT(int,SUBSTRING(ObservTimes, 7, 2)), CONVERT(datetime,'20' + SUBSTRING(ObservTimes, 1, 6),103))";
    }
}

?>