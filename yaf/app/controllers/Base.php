<?php
class BaseController extends Yaf_Controller_Abstract{

    //-- handle input param
    protected function getParam($key, $default=null)
    {
        $param = $this->getRequest()->isGet() ? $_GET : $_POST;
        return array_key_exists($key, $param) ? $param[$key] : $default;
    }

    //-- set dataase param
    protected function setDatabaseConfig($ip=null)
    {
        $db = $ip != null ? new MDatabase($ip) : new MDatabase();
        $db->connect();
        return $db;
    }
}