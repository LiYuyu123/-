<?php
namespace MetGS;
/*
 * author: zs
 * created_at: 2018-04-26
 */
use MetGS\GA as MG;

class MMapGA
{
    private $_mmap;
    private $_lonLat;
    private $_colorStandard;
    private $_cityLevel;
    private $_cityName;
    private $_levels;
    private $_colors;

    public function __construct($width, $height, $scaleSize)
    {
        $this->_colorStandard = "GB";
        $this->_mmap = new MG\MMap();
        $this->_mmap->setClientRect($width * $scaleSize, $height * $scaleSize);
        $this->_mmap->setGridLineOn(false);
        $this->_mmap->setClipMaskOnMapLayer(true);
        return $this->_mmap;
    }
    public function getLevels () {
        return $this->_levels;
    }
    public function getColors () {
        return $this->_colors;
    }
    public function setLevels ($levels) {
        $this->_levels = $levels;
    }
    public function setColors ($colors) {
        $this->_colors = $colors;
    }

    public function getMap ()
    {
        return $this->_mmap;
    }

    public function setMapExtent($lonMin, $lonMax, $latMin, $latMax)
    {
        $this->_mmap->setMapExtent($lonMin, $lonMax, $latMin, $latMax);
    }

    public function showAround($showAround)
    {
        $this->_mmap->setClipMaskOnDataLayer($showAround);
    }

    public function setCityName($cityName)
    {
        $this->_cityName = $cityName;
    }

    public function setCityLevel($cityLevel)
    {
        $this->_cityLevel = intval($cityLevel);
    }

    public function showTitle($title)
    {
        $this->_mmap->setClipMaskOnDataLayer(true);
        $this->_mmap->setTitleOn(true);
        $this->_mmap->setTitle(mb_convert_encoding($title, "GBK", "UTF-8"), 16, "(0,0,0)", MG_AlignCenter);
    }

    public function addCountyLayer($showTown = false)
    {
        $layerCounty = $this->_mmap->addLayer("Map_County");
        $layerCounty->setClipMask(true);
        $layerCounty->setStyleFill('(255,255,255)');
        $layerCounty->setLabelOn($this->_cityLevel == 1 ? true : false);
        $layerCounty->setStyleLabel(24);
        $this->_cityLevel == 1 && !$showTown ? $layerCounty->setStyleLine(1) : $layerCounty->setStyleLine(1);
        if($this->_cityLevel == 1)
            $layerCounty->setAttributeFilter("Pref_En='".$this->_cityName."'");
        else
            $layerCounty->setAttributeFilter("County_En='".$this->_cityName."'");

        $lonLat = $this->getLonLatByLayer($layerCounty);
        $this->_lonLat = $lonLat;

        $rect = $layerCounty->getMBR();
        $rect->setX($lonLat->minLon);
        $rect->setY($lonLat->minLat);
        $rect->setWidth($lonLat->maxLon - $lonLat->minLon);
        $rect->setHeight($lonLat->maxLat- $lonLat->minLat);

        $this->_mmap->setMapExtent($rect->inflateToRect(1.2));

        return $layerCounty;
    }

    public function addTownLayer($showTown = false)
    {
        if($this->_cityLevel == 1 && !$showTown) return;

        $layerTown = $this->_mmap->addLayer("Map_Town");
        $layerTown->setClipMask(true);
        $layerTown->setStyleFill('(224,224,224)');
        $layerTown->setStyleLine(1, '(0, 0, 0)');
        $layerTown->setLabelOn(true);
        $cityname = $this->_cityName;
        if($this->_cityLevel == 1)
            $layerTown->setAttributeFilter("Pref_En='".$this->_cityName."'");
        else
            $layerTown->setAttributeFilter("County_En='".$this->_cityName."'");
        return $layerTown;
    }

    public function getLonLat()
    {
        return $this->_lonLat;
    }

    public function getLonLatByLayer($layer)
    {
        $rect = $layer->getMBR();
        $ptLeftBottom = $this->_mmap->worldToLatLon($rect->getX(), $rect->getY());
        $ptTopRight = $this->_mmap->worldToLatLon($rect->getRight(), $rect->getTop());

        $ret = new \stdClass;

        $ret->minLon = $ptLeftBottom[0] - 0.1;
        $ret->maxLon = $ptTopRight[0];
        $ret->minLat = $ptLeftBottom[1] - 0.1;
        $ret->maxLat = $ptTopRight[1];

        return $ret;
    }

    public function MLayerGrid($hourSpan, $varId = 'T', $isFillColor = true, $gridLayerParam, $levelArr, $fillColors, $gridSize = 0.03)
    {
        $gridLayerParam['x_grid'] = [$this->_lonLat->minLon, $this->_lonLat->maxLon + 0.5, $gridSize];
        $gridLayerParam['y_grid'] = [$this->_lonLat->minLat, $this->_lonLat->maxLat + 0.5, $gridSize];

        $layer = new MG\MLayerGrid();
        $layer->setName("LayerGrid");
        $layer->setData($gridLayerParam);
        $layer->setContFillsOn($isFillColor);
        $layer->setContLinesOn(false);
        $layer->setColorBarOn(false);
        $layer->setGridValueShown(true);
        $this->setSkipGrid($layer);
        $layer->setGridValueShownFormat('%2.0f');
        if (empty($levelArr)) {
            $minT = $maxT = 0;
            $minT = floor(min($gridLayerParam['value']) );
            $maxT = ceil(max($gridLayerParam['value']) );
            
            $mColor = MColor::Create($this->_colorStandard);
            $levelColor = $mColor->getLevelsAndColors($varId, $hourSpan, -1, $minT, $maxT);
            $levelArr = $levelColor->level;
            $fillColors = $levelColor->color;
            $this->setLevels($levelColor->level);
            $this->setColors($levelColor->colorArr);
        }
        $layer->setContLevel($levelArr);
        $layer->setContFillColors($fillColors);

        return $layer;
    }

    public function MWindLayerGrid($hourSpan, $varId = 'T', $isFillColor = true, $gridLayerParam, $levelArr, $fillColors, $gridSize = 0.03)
    {
        $gridLayerParam['x_grid'] = [$this->_lonLat->minLon, $this->_lonLat->maxLon + 0.5, $gridSize];
        $gridLayerParam['y_grid'] = [$this->_lonLat->minLat, $this->_lonLat->maxLat + 0.5, $gridSize];
        $grid = new \MGrid2D();
        $grid->loadFromStation($gridLayerParam);
        $layer = new MG\MLayerVector();
        $layer->setName("MWindLayerGrid");
        $layer->setData($grid);
        $layer->setContLinesOn(false);
        $layer->setContFillsOn(true);
        if (empty($levelArr)) {
            $mColor = MColor::Create($this->_colorStandard);
            $levelColor = $mColor->getLevelsAndColors($varId, $hourSpan, -1, '', '');
            $levelArr = $levelColor->level;
            $fillColors = $levelColor->color;
            $this->setLevels($levelColor->level);
            $this->setColors($levelColor->colorArr);
        }
        $layer->setContLevel($levelArr);
        $layer->setContFillColors($fillColors);
        
        $layer->setWindBarbShown(true);
        $layer->setColorBarOn(false);
        $this->setSkipGrid($layer);
        return $layer;
    }

    public function MLayerStationValue($stationValueParam, $showName = false, $showValue = false, $isShowdecimal = true)
    {
        $stationLayer = new MG\MLayerStationValue();
        if ($showName || $showValue) { //地图上所有文字显示或隐藏
            $stationLayer->setStationValueOn($showValue);
            $stationLayer->setStationNameOn($showName);
            $stationLayer->setStationSymbolOn(true);
        } else {
            $stationLayer->setStationValueOn(false);
            $stationLayer->setStationNameOn(false);
            $stationLayer->setStationSymbolOn(false);
        }

        $stationLayer->setData($stationValueParam);
        $stationLayer->setStationValueStyle(12, '(0,0,0)');
        // $stationLayer->setStationValueStyle(12, '(0, 0,0)', 'simsun.ttc', '(255, 255, 255)', 2, false);
        $isShowdecimal ? $stationLayer->setStationValueFormat('%2.1f') : $stationLayer->setStationValueFormat('%2.0f');
        $stationLayer->setStationSymbolStyle(4, '(0,255,0)', '(150,150,150)', MG_Symbol_Square);
        return $stationLayer;
    }

    function setSkipGrid($layer)
    {
        //-- 数据跳点设置
        $skipGrid = [
            [0, 1, 1, 1], [1, 2, 2, 2], [2, 3, 3, 3], [3, 999, 4, 4]
        ];
        $nSkipGrid = count($skipGrid);

        $layer->setSkipGridsOn(true);
        for ( $i = 0; $i < $nSkipGrid; $i++ ) {
            $layer->addSkipGridSetting($skipGrid[$i][0],$skipGrid[$i][1],$skipGrid[$i][2],$skipGrid[$i][3]);
        }
    }

}