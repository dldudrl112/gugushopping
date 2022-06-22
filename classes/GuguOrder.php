<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

class GuguOrder {
    private $user = [];
    private $order = [];
    private $cart = [];
    public $value = null;
    public $status = null;

    public function __construct($orderId)
    {
        if(is_string($orderId)) {
            $this->initOrder($orderId);
        }
    }

    public function initOrder($orderId)
    {
        $sql = " select * from shop_order where od_id = '{$orderId}' order by index_no desc ";
        $this->order = sql_fetch($sql);
    }

    public function getBillingDate($isNextData= false){
        if(!$isNextData) return substr($this->order['receipt_time'], 0 , 10);
        $day= substr($this->order['od_time'], 8, 2);
        $endDay= date("t");

        if($day <= (int)$endDay){
            return date("Y-m-".$day);
        }

        $endDay = date("m")=== '02' ? '28' : $endDay;
        return date("Y-m-".$endDay);
    }

    public function getBillingDay(){
        return substr($this->getBillingDate(), 0, 8);
    }

}