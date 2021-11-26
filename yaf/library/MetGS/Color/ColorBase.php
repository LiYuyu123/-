<?php
namespace MetGS\Color;

/*
 * author: zs
 * created_at: 2018-04-26
 */
class ColorBase implements IColor
{
    protected $_levelColor;

    function __construct()
    {
        $this->_levelColor = new \stdClass;
        $this->_levelColor->level = [];
        $this->_levelColor->color = "";
    }

    public function getLevelsAndColors($varString, $hourSpan, $level = -1, $minT = 0, $maxT = 0)
    {
        if (substr($varString, 0, 2) == 'Pr') {
            return $this->getPrLevelsAndColors($hourSpan);
        }
        if (substr($varString, 0, 1) == 'T') {
            return $this->getTempLevelsAndColors($minT, $maxT);
        }
        if (substr($varString, 0, 3) == 'Vis') {
            return $this->getVisLevelsAndColors();
        }
        if (substr($varString, 0, 4) == 'Wind') {
            return $this->getWindLevelsAndColors();
        }
        return null;
    }

    protected function getPrLevelsAndColors( $hourSpan )
    {
        return null;
    }
}