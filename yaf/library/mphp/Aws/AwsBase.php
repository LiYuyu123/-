<?php

namespace MetGS\Aws;
	
/**
* 自动观测站数据, 基类
*
* 该类只负责生成查询的SQL语句，具体查询参数设置和执行查询过程
* 由调用的类完成。
*
* sz.metgs.com
* 2016-10-07
* 2016-10-24
*/        
class MAwsBase implements IAws
{   
    public function __construct( )  
    {  
        $this->_dicVars = array(
            //-- 气温类
            'T'         => array('value'=>'0.1*DryBulTemp','name'=>'定时的空气温度值'),
            'TAvg'      => array('value'=>'0.1*DryBulTemp','name'=>'定时的最低温度'),//'name'=>'定时的平均温度'
            'TMax'      => array('value'=>'0.1*MaxTemp','valueTime'=>'TimeMaxTemp','name'=>'定时的最高温度'),
            'TMin'      => array('value'=>'0.1*MinTemp','valueTime'=>'TimeMinTemp','name'=>'定时的最低温度'),
            
            //-- 风向风速类
            'Wind2'     => array('value'=>'0.1*WindVelocity', 'value2'=>'WindDirect','name'=>'定时的2分钟风向风速'),
            'Wind10'    => array('value'=>'0.1*WindVelocity10', 'value2'=>'WindDirect10','name'=>'定时10分钟平均风速风向'),
            'WindMax'   => array('value'=>'0.1*MaxWindV','value2'=>'MaxWindD','valueTime'=>'TimeMaxWind','name'=>'定时的最大风速风向'),
            'WindExMax' => array('value'=>'0.1*ExMaxWindV','value2'=>'ExMaxWindD','valueTime'=>'TimeExMaxWind','name'=>'定时的极大风速的风向'),
            
            //-- 降水类
            'Pr'        => array('value'=>'0.1*Precipitation','name'=>'定时的小时雨量累计值'),
            'PrInt1Max' => array('value'=>'0.1*Precipitation','valueTime'=>'LEFT(convert(char,ObservTimes,120),16)','moving'=>array('method'=>'SUM','step'=>1),'name'=>'1小时最大雨强'),
            'PrInt3Max' => array('value'=>'0.1*Precipitation','valueTime'=>'LEFT(convert(char,ObservTimes,120),16)','moving'=>array('method'=>'SUM','step'=>3),'name'=>'3小时最大雨强'),
            'PrInt6Max' => array('value'=>'0.1*Precipitation','valueTime'=>'LEFT(convert(char,ObservTimes,120),16)','moving'=>array('method'=>'SUM','step'=>6),'name'=>'6小时最大雨强'),
            'PrInt12Max' => array('value'=>'0.1*Precipitation','valueTime'=>'LEFT(convert(char,ObservTimes,120),16)','moving'=>array('method'=>'SUM','step'=>12),'name'=>'12小时最大雨强'),
            'PrInt24Max' => array('value'=>'0.1*Precipitation','valueTime'=>'LEFT(convert(char,ObservTimes,120),16)','moving'=>array('method'=>'SUM','step'=>24),'name'=>'24小时最大雨强'),
            
            //-- 地温类
            'TSfc'     => array('value'=>'0.1*SurfaceTemp','name'=>'定时的地面温度'),
            'TSfcMax'  => array('value'=>'0.1*SurfaceMaxTemp','valueTime'=>'TimeSurMaxTemp','name'=>'定时的地面最高温度'),
            'TSfcMin'  => array('value'=>'0.1*SurfaceMinTemp','valueTime'=>'TimeSurMinTemp','name'=>'定时的地面最低温度'),
            'T5'       => array('value'=>'0.1*Temp5cm','name'=>'5cm地温'),
            'T10'      => array('value'=>'0.1*Temp10cm','name'=>'10cm地温'),
            
            //-- 相对湿度类
            'Rh'       => array('value'=>'RelHumidity','name'=>'相对湿度'),
            'RhMin'    => array('value'=>'MinRelHumidity','valueTime'=>'TimeMinRelH','name'=>'最小相对湿度'),
            'TDew'     => array('value'=>'0.1*DewTemp','name'=>'露点温度'),
            'TTdDiff' => array('value'=>'0.1*(DryBulTemp-DewTemp)','name'=>'温度露点差'),
            
            //-- 气压
            'P'        => array('value'=>'0.1*StationPress','name'=>'定时的本站气压值'),
            'PMin'     => array('value'=>'0.1*MinPSta','valueTime'=>'TimeMinPSta','name'=>'最低本站气压值'),
            'PMax'     => array('value'=>'0.1*MaxPSta','valueTime'=>'TimeMaxPSta','name'=>'最高本站气压值'),
            'Slp'      => array('value'=>'0.1*SeaLevelPress','name'=>''),  //定时的海平面气压值
            
            //-- 能见度
            'Vis'      => array('value'=>'VisiA','name'=>'水平能见度'),   
            'VisMin'   => array('value'=>'MinVisiA','valueTime'=>'TimeMinVisiA','name'=>'最小水平能见度'),
            
            //-- 蒸发量
            'Eva'      => array('value'=>'EvagaugeAmount','name'=>'蒸发量 ')      
        );

        $this->_sqlFunc = array('MAX','MIN','AVG','SUM','');

    }  

    /// 获取空间查询的SQL语句

    /// 获取单站时间序列查询的SQL语句
    function getTimeSeriesSql() 
    {

    }

    /**
     * 查询所有可用的变量定义
     */
    public function getVariables()
    {
    	$vars = array();

    	foreach($this->_dicVars as $key=>$value)
    	{
    		array_push($vars, array('varId'=>$key, 'name'=>$value['name']));
    	}

    	return $vars;
    }
   
   /**
    * 设置数据过滤的矩形范围，只有在此矩形内的数据才会返回
    * @param $left/$right/$bottom/$top 矩形边界的经纬度值
    */
	public function setRectangleFilter( $left, $right, $bottom, $top )
	{
		$this->_rectangleFilter = "(EEEee>" . ($left*100) . " AND EEEee<" . ($right*100) . 
			" AND NNnn>" . ($bottom*100) . " and NNnn<" . ($top*100) . ")";	
	}   

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

	}
    /*
     *启用是否添加周边数据
     */
    public function setAreaJudger( $filter )
    {

    }

	/**
	 * 是否启用行政区域过滤，默认是关闭的，即没有行政区域过滤
	 */
	public function setPoliticalFilterOn( $flagTrueOfFalse )
	{
		$this->_isPoliticalFilterOn = $flagTrueOfFalse;
	}

	/**
	 * 是否启用矩形区域过滤，默认是关闭的
	 */
	public function setRectangleFilterOn( $flagTrueOfFalse )
	{
		$this->_isRectangleFilterOn = $flagTrueOfFalse;
	}
    
    /**
     * 获取空间查询的SQL语句
     *
     * 输入参数：
     * @param $varId 查询的变量ID，可以通过getVariables()函数查询所有可用的ID
     * @param $dtEnd 查询时刻值或者查询时段的结束值
     * @param $hourSpan 查询统计的时间跨度，0时为时刻值，>0时为时段值，时段区间为半开区间：(dtEnd.addHour(-hourSpan), dtEnd]
     * @param $statisticsFunction 查询时段上的统计方法，$hourSpan>0时有用，为SQL语言的聚合函数，SUM/AVG/MIN/MAX/DIF五个之一
     *              DIF不是标准的SQL统计函数，这里用来专指气象上的两个时间差值，如变温、变压
     * 
     * 输出参数：
     * 一个临时类对象，成员为：
     * class Dummy{
     * 		$status  		SQL构建状态, 0时正功，其他值失败，失败原因在 $message成员中
	 *		$message        仅当$status != 0时有，说明失败的原因
	 *		$hasValue2		SQL查询语句是否包含第二个值，如风向
	 *		$hasValueTime	SQL查询语句是否包含第一个查询值的出现时间
	 *		$sql 			SQL语句
	 *	}
	 *
	 *
	 * SQL查询语句返回的结果集，格式为
	 *  站号  | 站名 | 是否行政区内 | 经度 | 纬度 | 值1 ｜ 值2(可选项) ｜值1出现时间(可选项)
	 * --------------------------------------------------------------------------------------	 
	 *   id   | name |   p          |  x   |  y   | v1  ｜  v2         |  v1t
	 * --------------------------------------------------------------------------------------	 
	 * 其中是否有值2(v2)由返回对象的$hasValue2属性定，
	 * 是否有值1出现时间(v1t)由返回对象的$hasValueTime属性定
     */
    public function getSpaceSql($varId, $dtEnd, $hourSpan = 0, $statisticsFunction = "SUM")
    {
        //-- 构造返回对象，对传入参数进行基本检查
        $ret = new \stdClass;        
        if (!array_key_exists($varId, $this->_dicVars) )
        {
	        $ret->status = 1;
	        $ret->message = '找不到传入变量的定义 : $varId !';        	
            return $ret;
        }
        if(!is_numeric($hourSpan))
        {
            $ret->status = 1;
            $ret->message = '$hourSpan is not a number !';            
            return $ret;
        }
        if(!in_array($statisticsFunction, $this->_sqlFunc) )
        {
            $ret->status = 1;
            $ret->message = '找不到传入变量的定义 : $statisticsFunction !';            
            return $ret;
        }
              
        //-- 构造数据过滤字符串
        $dataFilter = "";
        if ( $this->_isPoliticalFilterOn )
        	$dataFilter = $this->_politicalFilter;

        if ( $this->_isRectangleFilterOn )
        	$dataFilter .= (empty($dataFilter) ? "" : " AND ") . $this->_rectangleFilter;

        //-- 构造变量查询字符串
        $var = $this->_dicVars[$varId];
   	 
        $ret->hasValue2 = false;
        $ret->hasValueTime = false;

    	$strVar = $var['value'] . " as v1";
    	$strStatVar = "";
    	if ( array_key_exists('value2', $var) ){
    	   $ret->hasValue2 = true;
    	   $strVar .= "," . $var['value2'] . " as v2";
    	   $strStatVar .= (empty($strStatVar) ? "" : ",") . "v2";
    	}
        if ( array_key_exists('valueTime', $var) ) {
           $ret->hasValueTime = true;
           $strVar .= "," . $var['valueTime'] . " as v1t";
           $strStatVar .= (empty($strStatVar) ? "" : ",") . "v1t";
        }
    	
    	//-- 从数据表中取出所有原始记录Select out all orignal records filtered by datetime and area
    	$tableStationInfo = $this->getStationInfoTable();
		$sql = $this->buildAwsRecordSql($dtEnd, $hourSpan, $strVar, $strStatVar, $statisticsFunction, $dataFilter, $tableStationInfo, 
				array_key_exists('moving', $var) ? $var['moving'] : null);

		//-- Statistical var always contain v1
		$strStatVar = "v1" . (empty($strStatVar) ? "" : ",") . $strStatVar;

    	//-- 如果需要进行统计，执行统计，If there are statistics function, just do statistics
    	if ( $hourSpan > 0 )
    	{
    		if ( $statisticsFunction == "SUM" || $statisticsFunction == "AVG") {
    			$strStatVar = "v1";
    			$hasValue2 = $hasValueTime = false;
                $sql = "SELECT id, $statisticsFunction(v1) as v1 FROM( " . $sql . ")Q1 GROUP BY id";
    		}
    		else if ($statisticsFunction == "MIN" || $statisticsFunction == "MAX") {
    			//-- NOTE: rank(), dense_rank() will cause multiple max records, use row_number() here
    			$strRankOrder = $statisticsFunction == "MAX" ? "DESC" : "ASC";
    			$sql = "SELECT id, t, $strStatVar, row_number() OVER ( partition BY id ORDER BY v1 $strRankOrder ) AS ROrder FROM ( $sql )Q1A";
    			$sql = "SELECT * from ( $sql ) Q1 WHERE ROrder = 1";
    		}
            else if ($statisticsFunction == "DIF"){ // 求两个时间之差
                $strStatVar = "v1";
                $hasValue2 = $hasValueTime = false;
                $sql = "select q.t, q.id, (q.v1 - qprev.v1) as v1 from ( $sql ) q 
                        inner join ( select t=DATEADD(hour, $hourSpan, t), id, v1 FROM( $sql )qprevtmp ) qprev 
                        on q.t = qprev.t and q.id = qprev.id";
            }
    	}
    	
    	//-- 将查询结果与站点信息关联Query station information
   	    $sql = "SELECT id, RTRIM(StationName) as name, IIF(" . (empty($this->_aroundJudger) ? '1=1' : $this->_aroundJudger) .
   		",1,0) as p, 0.01*EEEee as x, 0.01*NNnn as y, S.Country AS county, S.Town AS town, ZoomLevel as zlevel, $strStatVar from ( $sql )Q2 inner join $tableStationInfo as S on Q2.id = S.IIiii ORDER BY v1 DESC";
		// $sql = "SELECT DISTINCT id, RTRIM(StationName) as name, IIF(" . (empty($this->_aroundJudger) ? '1=1' : $this->_aroundJudger) .
		// 	",1,0) as p, 0.01*EEEee as x, STLAT as y, $strStatVar from ( $sql )Q2 inner join $tableStationInfo as S on  Q2.id = S.STID ORDER BY v1 DESC";
		$ret->status = 0;
		$ret->sql = $sql;
    	return $ret;
    }
    
    /**
     * 参数验证
     */
    protected function getValidate(){

    }
    /**
     * 不同的实现方式，站点表名不一样
     */
    protected function getStationInfoTable()
    {          
        return "";
    }    
    
    /**
     * Class used internelly
     */
    protected function buildAwsRecordSql($dtEnd, $hourSpan, $strVar, $strStatVar, $statisticsFunction, 
        $dataFilter, $tableStationInfo, $movingConfig = null)
    {          
        return "";
    }
    /*
    * Highchart 曲线图
    */
    public function getHighChartDataSql($stid, $varId, $dtEnd, $hourSpan = 0, $daySpan, $sqlFunc = "SUM", $timeFixEnd)
    {
        $ret = new \stdClass;
        if (strlen($stid) != 5 || !is_numeric(substr($stid,1)))
        {
            $ret->status = 1;
            $ret->message = '非法变量 : $stid !';
            return $ret;
        }

        if (!array_key_exists($varId, $this->_dicVars) )
        {
            $ret->status = 1;
            $ret->message = '找不到传入变量的定义 : $varId !';
            return $ret;
        }

        if(!is_numeric($hourSpan))
        {
            $ret->status = 1;
            $ret->message = '$hourSpan is not a number !';            
            return $ret;
        }

        if(!is_numeric($daySpan))
        {
            $ret->status = 1;
            $ret->message = '$daySpan is not a number !';            
            return $ret;
        }

        if(!is_numeric($timeFixEnd))
        {
            $ret->status = 1;
            $ret->message = '$timeFixEnd is not a number !';            
            return $ret;
        }

        if(!in_array($sqlFunc, $this->_sqlFunc) )
        {
            $ret->status = 1;
            $ret->message = '找不到传入变量的定义 : $sqlFunc !';            
            return $ret;
        }

        $var = $this->_dicVars[$varId];

        $ret->hasValue2 = false;
        $ret->hasValueTime = false;

        $dataFilter = " WHERE StationNum = '{$stid}'";

        $dateFormatStringToDateTime = $this->buildFormatObservTimes();

        if ($timeFixEnd > -1 && 100 > $timeFixEnd) {
            $strVar = $var['value'] . " as v1, CONVERT (VARCHAR (20), DATEADD(HOUR, ". (24 - $timeFixEnd - 1) .", $dateFormatStringToDateTime),120) AS t";
        } else {
            $strVar = $var['value'] . " as v1, $dateFormatStringToDateTime as t";
        }

        $strStatVar = "";
        if ( array_key_exists('value2', $var) ){
           $ret->hasValue2 = true;
           $strVar .= "," . $var['value2'] . " as v2";
           $strStatVar .= (empty($strStatVar) ? "" : ",") . "v2";
        }
        if ( array_key_exists('valueTime', $var) ) {
           $ret->hasValueTime = true;
           $strVar .= "," . $var['valueTime'] . " as v1t";
           $strStatVar .= (empty($strStatVar) ? "" : ",") . "v1t";
        }

        $sql = $this->buildAwsChartRecordSql($dtEnd, $hourSpan, $daySpan, $strVar, $strStatVar, $sqlFunc, $dataFilter, $timeFixEnd, array_key_exists('moving', $var) ? $var['moving'] : null);

        $strStatVar = "v1, substring(t, 0, 11) + ' 00:00:00' as t" . (empty($strStatVar) ? "" : ",") . $strStatVar;
        
        if ( $timeFixEnd > -1 && $timeFixEnd < 100) {
            $sql = "SELECT $strStatVar FROM ($sql) QL ";
            if ($sqlFunc == "MIN" || $sqlFunc == "MAX") {
                $strRankOrder = $sqlFunc == "MAX" ? "DESC" : "ASC";
                $sql = "SELECT $strStatVar FROM ( $sql )Q1B";
                $sql = "SELECT $strStatVar, row_number() OVER ( partition BY t ORDER BY v1 $strRankOrder ) AS ROrder FROM ( $sql )Q2B";
                $sql = "SELECT * from ( $sql ) Q1 WHERE ROrder = 1";
            } else {
                $sql = "SELECT $sqlFunc(v1) as v1, t FROM ($sql) QC GROUP BY t ORDER BY t";
            }
        }

//		$sql = $sql.'ORDER BY ObservTimes ASC';

        $ret->status = 0;
        $ret->sql = $sql;

        return $ret;
    }

	//---------------------------------------------------
	//-- 属性值
	//---------------------------------------------------

	/// 行政区域过滤字符串
    protected $_politicalFilter = "";

    /// 矩形连界过滤字符串
    protected $_rectangleFilter = "";

    /// 是否启用行政区域过滤
    protected $_isPoliticalFilterOn = false;

    /// 是否启用矩形边界过滤
    protected $_isRectangleFilterOn = false;

    ///METGS地图周边数据
    protected $_aroundFilter = "";

    /// 变量定义字典，用于简化数据库查询中字符串的构造
    protected $_dicVars;

    protected $_sqlFunc;
}

?>