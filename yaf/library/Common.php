<?php
  class Common
  {
    public function windDirect($value)
    {   
        if (!$value) {
            return '暂无数据';
        }
        $tmpValue = $value % 360;

        $windDirectionName = '北风';

        if ($tmpValue == 0)
            $windDirectionName = '北风';

        if ($tmpValue > 0 && $tmpValue < 90)
            $windDirectionName = '东北风';

        if ($tmpValue == 90)
            $windDirectionName = '东风';

        if ($tmpValue > 90 && $tmpValue < 180)
            $windDirectionName = '东南风';

        if ($tmpValue == 180)
            $windDirectionName = '南风';

        if ($tmpValue > 180 && $tmpValue < 270)
            $windDirectionName = '西南风';

        if ($tmpValue == 270)
            $windDirectionName = '西风';

        if ($tmpValue > 270 && $tmpValue < 360)
            $windDirectionName = '西北风';

        return $windDirectionName;
    }

    /**
     * [windLevel 风速等级]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function windLevel($value)
    {
        if (!$value) {
            return '暂无数据';
        }
        $windSName = '微风';

        if ($value >= 0 && $value <= 5.4)
            $windSName = '微风';
        if ($value > 5.4 && $value <= 7.9)
            $windSName = '3-4级';
        if ($value > 7.9 && $value <= 10.7)
            $windSName = '4-5级';
        if ($value > 10.7 && $value <= 13.8)
            $windSName = '5-6级';
        if ($value > 13.8 && $value <= 17.1)
            $windSName = '6-7级';
        if ($value > 17.1 && $value <= 20.7)
            $windSName = '7-8级';
        if ($value > 20.7 && $value <= 24.4)
            $windSName = '8-9级';
        if ($value > 24.4 && $value <= 28.4)
            $windSName = '9-10级';
        if ($value > 28.4 && $value <= 32.6)
            $windSName = '10-11级';
        if ($value > 32.6)
            $windSName = '12级以上';

        return $windSName;

    }
  }


?>