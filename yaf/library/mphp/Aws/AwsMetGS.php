<?php

namespace MetGS\Aws;
	
/**
* 自动观测站数据, MetGS版本数据库 MGAws
*
* 该类只负责生成查询的SQL语句，具体查询参数设置和执行查询过程
* 由调用的类完成。
*
* sz.metgs.com
* 2016-10-07
*/
class MAwsMetGS extends MAwsBase
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

//		if ( array_key_exists("County", $filter) )
//		{
//			$this->_politicalFilter = "(country = '" . $filter["County"] . "')";
//			return;
//		}
		if ( array_key_exists("County", $filter) )
		{
			$this->_politicalFilter = "(Country = '" . $filter["County"] . "')";
//			$this->_politicalFilter = "(City = '" . $filter["County"] . "')";
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
            $this->_aroundJudger = "(Province = '" . $filter["Province"] . "')";
            return;
        }

        if ( array_key_exists("Prefecture", $filter) )
        {
            $this->_aroundJudger = "(City = '" . $filter["Prefecture"] . "')";
            return;
        }

        if ( array_key_exists("County", $filter) )
        {
			$this->_politicalFilter = "(Country = '" . $filter["County"] . "')";
//            $this->_aroundJudger = "(Country = '" . $filter["County"] . "')";
            return;
        }
    }

    /**
     * 不同的实现方式，站点表名不一样
     */
    protected function getStationInfoTable()
    {
		return "MG_AWS_STATIONINFO";
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

    	//-- 时间过滤字符串   	
        if ( $hourSpan > 0 )
        {
            $dtStart = clone $dtEnd;
            $dtStart->sub(new \DateInterval("PT" . ($hourSpan+$delta) . "H"));
            
            if ( $statisticsFunction == "DIF" )
            {
                $whereDate = "(ObservTimes='" . $dtStart->format('ymdH') . 
                        "' OR ObservTimes='" . $dtEnd->format('ymdH') . "')";
            }
            else {
                $whereDate = "(ObservTimes>'" . $dtStart->format('ymdH') . 
                        "' AND ObservTimes<='" . $dtEnd->format('ymdH') . "')";
            }
        }
        else
        {
        	$whereDate = "ObservTimes='" . $dtEnd->format('ymdH') . "'";
        }

        $dataFilter .= (empty($dataFilter) ? "" : " AND ") . $whereDate;
                    	
        //-- 从数据表中取出所有原始记录Select out all orignal records filtered by datetime and area
		$sql = "SELECT ObservTimes as t, StationNum as id, $strVar FROM MG_Aws_Hour as O INNER JOIN" .
			" $tableStationInfo as S ON O.StationNum=S.IIiii WHERE $dataFilter";

        //-- Moving average / moving sum
        if ( $hourSpan > 0 && null != $movingConfig ){
    		$method = $movingConfig['method'];

			$sql = "select id, t, $method(v1) over(partition by id order by t asc rows between " . ( $delta-1 ) . 
				" preceding and current row) as v1" . (empty($strStatVar) ? "" : ",$strStatVar") . " from(" .
				$sql . ")QMA WHERE T>'" . $dtStart->add(new \DateInterval("PT" . $delta . "H"))->format('ymdH') . "'";
		}
        
        return $sql;
    }

    protected function buildAwsChartRecordSql ($dtEnd, $hourSpan, $daySpan, $strVar, $strStatVar, $sqlFunc, $dataFilter, $timeFixEnd, $movingConfig)
    {
        $delta = 0;
        if ( $hourSpan > 0 && null != $movingConfig ){
            $delta = $movingConfig['step'];
        }

        $dtStart = clone $dtEnd;
        $dtStart = $dtStart->sub(new \DateInterval('P'. $daySpan .'D'));

        if ( $timeFixEnd > -1 && $timeFixEnd < 100 ) {
            if ($dtEnd->format('ymdH') == date('ymdH', time()))
                $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymd'.$timeFixEnd) ."' AND ObservTimes <= '". $dtEnd->format('ymdH') ."')";
            else 
                $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymd'.$timeFixEnd) ."' AND ObservTimes <= '". $dtEnd->format('ymd'.$timeFixEnd) ."')";
        } else {
            if ($hourSpan > 0) {
                $dtStart = $dtStart->sub(new \DateInterval('PT'. ((int)$hourSpan + $delta) .'H'));
                $delta = $hourSpan;
            }
            $dataFilter .= " AND (ObservTimes > '". $dtStart->format('ymdH') ."' AND ObservTimes <= '". $dtEnd->format('ymdH') ."') "; 
        }

        $sql = "SELECT {$strVar} FROM MG_Aws_Hour {$dataFilter}";
        
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
            $sql = "SELECT * FROM ($sql) QRB WHERE t > '".$dtEnd->sub(new \DateInterval('P'. $daySpan .'D'))->format('ymdH')."'";
        }

        $sql .= $hourSpan == 0 ? "order by t" : "";

        return $sql;

    }

    protected function buildFormatObservTimes()
    {
        return "DATEADD(HOUR, CONVERT(int,SUBSTRING(ObservTimes, 7, 2)), CONVERT(datetime,'20' + SUBSTRING(ObservTimes, 1, 6),103))";
    }

}

?>