<?php
/**
 * @name IndexController
 * @author lq@metgs.com
 */
class IndexController extends Yaf_Controller_Abstract
{
    public function getIndexAction ()
    {
        $startDate = $this->getParam('startDate');
        $endDate = $this->getParam('endDate');
        $db = new MDatabase();
        $data = $db->select("select obstime,value from RuiAn_TideForecast where obstime >= ? AND obstime <= ?",[$startDate,$endDate]);
        echo json_encode($data);
        return false;
    }

    public function getOneDayAction ()
    {
       $startDate = $this->getParam('startDate');
       $today = $this->getParam('todayEnd');
       $db = new MDatabase();
       $data = $db->select("select obstime,value from RuiAn_TideForecast where obstime >= ? AND obstime <= ?",[$startDate,$today] );
       echo json_encode($data);
       return false;
    }

    protected function getParam($key, $default=null)
    {
        $param = $this->getRequest()->isGet() ? $_GET : $_POST;
        return array_key_exists($key, $param) ? $param[$key] : $default;
    }
}
