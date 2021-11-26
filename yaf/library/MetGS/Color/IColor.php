<?php
namespace MetGS\Color;
/*
 * author: zs
 * created_at: 2018-04-26
 */
interface IColor
{
    /**
     * 获取特定变量的颜色等值线间隔默认值
     *
     * @param $varString 变量名称字符串，暂时支持：
     *      Pr 降水
     *      Temp 湿度     -- 未实现
     *      Rh 相对湿度 -- 未实现
     *      Ws 风速   -- 未实现
     * @param $hourSpan 变量的时间跨度，以小时为单位，不足1小时用小数表示
     * @param level 变量的垂直层次，默认值为-1，表示地面层，其他层次输入相应的值
     */
    function getLevelsAndColors($varString, $hourSpan, $level = -1);
}