<?php
namespace MetGS;
use MetGS\Color\GBColor;
use MetGS\Color\ZJColor;

/*
 * author: zs
 * created_at: 2018-04-26
 */
class MColor
{
    public static function Create($standard)
    {
        switch ($standard) {
            case 'GB':
                return new GBColor();
                break;
            case 'ZJ':
                return new ZJColor();
                break;
            default:
                return new GBColor();
                break;
        }
    }
}