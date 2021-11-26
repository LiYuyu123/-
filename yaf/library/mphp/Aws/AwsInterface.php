<?php

namespace MetGS\Aws;

/**
* 自动观测站数据查询接口
*
* 该接口只负责生成查询的SQL语句，具体查询参数设置和执行查询过程
* 由调用的类完成。
*
* sz.metgs.com
* 2016-10-07
*/
interface IAws
{
    /// 自动站小时数据
    const AWS_INTERVAL_HOUR = 1;

    /// 自动站分钟数据
    const AWS_INTERVAL_MINUTE = 2;

    //function SourceName();

    /// 设置要使用的自动站数据间隔，小时数据还是分钟数据
    /// function SetDataInterval();

    /// 获取空间查询的SQL语句
    //function getSpaceSql();

    /// 获取单站时间序列查询的SQL语句
    //function GetTimeSeriesSql();

    /**
     * 查询所有可用的变量定义
     */
    function getVariables();

   /**
    * 设置数据过滤的矩形范围，只有在此矩形内的数据才会返回
    * @param $left/$right/$bottom/$top 矩形边界的经纬度值
    */
    function setRectangleFilter( $left, $right, $bottom, $top );

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
    function setPoliticalFilter( $filter );

    /**
     * 是否启用行政区域过滤，默认是关闭的，即没有行政区域过滤
     */
    function setPoliticalFilterOn( $flagTrueOfFalse );          

    /**
     * 是否启用矩形区域过滤，默认是关闭的
     */
    function setRectangleFilterOn( $flagTrueOfFalse );    

    /**
     * 获取空间查询的SQL语句
     *
     * 输入参数：
     * @param $varId 查询的变量ID，可以通过getVariables()函数查询所有可用的ID
     * @param $dtEnd 查询时刻值或者查询时段的结束值
     * @param $hourSpan 查询统计的时间跨度，0时为时刻值，>0时为时段值，时段区间为半开区间：(dtEnd.addHour(-hourSpan), dtEnd]
     * @param $statisticsFunction 查询时段上的统计方法，$hourSpan>0时有用，为SQL语言的聚合函数，SUM/AVG/MIN/MAX四个之一
     * 
     * 输出参数：
     * 一个临时类对象，成员为：
     * class Dummy{
     *      $status         SQL构建状态, 0时正功，其他值失败，失败原因在 $message成员中
     *      $message        仅当$status != 0时有，说明失败的原因
     *      $hasValue2      SQL查询语句是否包含第二个值，如风向
     *      $hasValueTime   SQL查询语句是否包含第一个查询值的出现时间
     *      $sql            SQL语句
     *  }
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
    function getSpaceSql($varId, $dtEnd, $hourSpan = 0, $statisticsFunction = "SUM");    
}


?>