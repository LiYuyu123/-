<?php
namespace MetGS\Color;
/*
 * author: zs
 * created_at: 2018-04-26
 */
class GBColor extends ColorBase 
{
    protected function getPrLevelsAndColors( $hourSpan )
    {
        $levels = null;
        $colors = null;

        $hourSpan = intval($hourSpan) > 24 ? 24 : intval($hourSpan);

        switch ($hourSpan) {
            case 24: // 24 hour precipitation
                $levels = [ 1, 10, 25, 50, 100, 250 ];
                break;
            case 12: // 12 hour precipitation
                $levels = [ 1, 5, 15, 30, 70, 140 ];
                break;
            case 6: // 6 hour precipitation
                $levels = [ 1, 4, 13, 25, 60, 120 ];
                break;
            case 3:  // 3 hour precipitation
                $levels = [ 1, 3, 10, 20, 50, 70 ];
                break;
            case 1: // 1 hour precipitation
                $levels = [ 1, 1.5, 7, 15, 40, 50 ];
                break;          
            default: // < 1 hour precipitation, such as 10 minutes
                $levels = [1.0, 1.6, 7.0, 15.0, 40.0, 50];
                break;
        }

        $colors = "(245,245,245)|(166,242,143)|(61,186,61)|(97,184,255)|(0,0,225)|(250,0,250)|(128,0,64)";
        $colorArr = ['(245,245,245)','(166,242,143)','(61,186,61)','(97,184,255)','(0,0,225)','(250,0,250)','(128,0,64)'];
        
        $this->_levelColor->level = $levels;
        $this->_levelColor->color = $colors;
        $this->_levelColor->colorArr = $colorArr;
        return $this->_levelColor;
    }

    protected function getTempLevelsAndColors($minT, $maxT)
    {
        $step = 0.5;
        while( $step * 13 < ($maxT - $minT)) {
            $step = $step + 0.5;
        }
        $levels = [];
        $colorArr = ['(31,31,255)','(59,59,255)','(87,87,255)','(115,115,255)','(143,143,255)','(171,171,255)','(199,199,255)','(227,227,255)','(255,252,140)','(255,224,140)'
        ,'(255,196,112)','(255,168,112)','(255,140,84)','(255,112,84)','(255,84,56)'];
        for ($i = $minT; $i <= $maxT; $i = $i + $step) {
            array_push($levels, $i);
        }
        $newColorArr = array_slice($colorArr, 14 - count($levels));
        // $levels = [20.0, 21.0, 22.0, 23.0, 24.0, 25.0, 26.0, 27.0, 28.0, 29.0, 30.0, 31.0, 32.0, 32.0, 33.0, 34.0, 35.0, 36.0, 37.0, 38.0, 39.0];
        // $colors = "(31,31,255)|(59,59,255)|(87,87,255)|(115,115,255)|(143,143,255)|(171,171,255)|(199,199,255)|(227,227,255)|(255,252,140)|(255,224,140)
        //             |(255,196,112)|(255,168,112)|(255,140,84)|(255,112,84)|(255,84,56)";
        $colors = implode($newColorArr, '|');
        $this->_levelColor->level = $levels;
        $this->_levelColor->color = $colors;
        $this->_levelColor->colorArr = $newColorArr;

        return $this->_levelColor;
    }

    protected function getVisLevelsAndColors()
    {
        // $levels = [0.05, 0.1, 0.2, 0.5, 1.0, 2, 5, 10, 20, 50, 100];
        // $colors = "(100,100,100)|(120,120,120)|(140,140,140)|(160,160,160)|(170,170,179)|(190,190,190)|(200,200,200)|(210,210,210)|(220,220,220)|(230,230,230)|(240,240,240)|(255,255,255)";
        $levels = [0.05, 0.2, 0.5, 0.75, 7.5];
        $colors = "(255,0,0)|(228,124,121)|(238,182,115)|(247,216,70)|(153,205,90)|(250,250,250)";
        $colorArr = explode('|', $colors);
        $this->_levelColor->level = $levels;
        $this->_levelColor->color = $colors;
        $this->_levelColor->colorArr = $colorArr;
        return $this->_levelColor;
    }

    protected function getWindLevelsAndColors()
    {
        $levels = [ 3.4, 5.5, 8.0, 10.8, 13.9, 17.2, 20.8, 24.5, 28.5, 32.7];
        // $colors = '(255,255,255)|(255,207,49)|(255,154,0)|(224,194,194)|(202,149,149)|(148,73,73)|(252,147,147)|(252,100,100)|(251,40,40)|(128,0,255)|(64,0,128)';
        $colors = '(255,255,255)|(75,165,255)|(75,148,255)|(108,184,75)|(108,220,75)|(102,255,75)|(255,236,77)|(255,218,77)|(255,201,77)|(255,184,75)|(255,129,75)';
        $colorArr = explode('|', $colors);
        $this->_levelColor->level = $levels;
        $this->_levelColor->color = $colors;
        $this->_levelColor->colorArr = $colorArr;
        return $this->_levelColor;
    }
}