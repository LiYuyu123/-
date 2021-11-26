<?php
/// Time consume class
// xjc@metgs.com
// 2018-01-12
class MTimer {
    private $_t0;

    public function start(){
        $this->_t0 = microtime(true);
    }

    public function reset(){
        $this->_t0 = microtime(true);
    }

    /// Time sued in micro seconds
    public function ellapse(){
        return (microtime(true) - $this->_t0) * 1000;
    }
}