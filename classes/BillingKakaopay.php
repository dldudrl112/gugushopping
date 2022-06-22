<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

class BillingKakaopay
{
    private $apiUrl = "https://kapi.kakao.com/v1";
    private $apiMethods = [
        'init' => "payment/ready",
        'approve' => "payment/approve"
    ];
    private $billing_id= 'TCSUBSCRIP';//CT06206006
    private $billing_admin_key= '1eb42500c9dcb81bcf59d0e6434cf1f2';
    private $user = [];
    private $order = [];
    private $cart = [];
    public $value = null;
    public $status = null;

    public function __construct($order, $pgInfo= [])
    {
        $this->order= $order;

        if(!empty($pgInfo)){
            if(!empty($pgInfo['billing_id'])) $this->billing_id= $pgInfo['billing_id'];
            if(!empty($pgInfo['billing_key'])) $this->billing_admin_key= $pgInfo['billing_key'];
        }
    }

    public function isMobile()
    {
        return preg_match('/phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony/i', $_SERVER['HTTP_USER_AGENT']);
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    public function getOrder(){
        return $this->order;
    }

    public function getApiUrl($method)
    {
        if (!isset($this->apiMethods[$method])) return null;

        return "{$this->apiUrl}/{$this->apiMethods[$method]}";
    }

    public function getNextUrl()
    {
        if (empty($this->value) || !is_array($this->value)) {
            return null;
        }
        $method = $this->isMobile() ? 'next_redirect_mobile_url' : 'next_redirect_pc_url';
        return isset($this->value[$method]) ? $this->value[$method] : null;
    }

    public function approve($pg_token){
        $url = $this->getApiUrl('approve');
        if (empty($url)) return $this;

        $options = ['post'=>[
            'cid' => $this->billing_id,
            'tid' => $this->order['od_tno'],
            'partner_order_id' => $this->order['od_id'],
            'partner_user_id' => $this->user['index_no'] ?: "",
            'pg_token' => $pg_token
        ]];

        $v= json_decode($this->post($url, $options), true);
        if(!empty($v) && isset($v['aid']) && isset($v['sid'])){
            $v['od_app_no']= $v['aid'];
            $v['od_billkey']= $v['sid'];
        }
        $this->value= $v;

        return $this;
    }

    public function updateTID(){

        if(empty($this->order) || empty($this->order['od_id'])){
            $this->errors[]= "error updateTID empty(order)";
            return $this;
        }
        if(!is_array($this->value) || !isset($this->value['tid'])){
            $this->errors[]= "error updateTID";
            return $this;
        }
        $od_id= $this->order['od_id'];

        sql_query("update shop_order set od_tno= '{$this->value['tid']}' where od_id = '{$od_id}'");
        return $this;
    }

    public function setCartByOrder(){
        global $set_cart_id;
        $cart= sql_fetch(" select * from shop_cart where od_id = '{$this->order['od_id']}' and ct_select = '0' and ct_direct= '{$set_cart_id}' ");
        $this->cart= $cart;
        return $this;
    }

    public function post($url, $options= []){
        $ch = curl_init($url);
        $headers = [
            "Authorization: KakaoAK " . $this->billing_admin_key,
            "Content-Type: application/x-www-form-urlencoded"
        ];
        if(!empty($options) && !empty($options['post'])){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['post']));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //header 지정하기
        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 201:  # OK
                case 200:  # OK
                    break;
                default:
                    $logData= PHP_EOL."Headers".PHP_EOL.print_r($headers, true);
                    if(!empty($options) && !empty($options['post'])) {
                        $logData .= PHP_EOL.$http_code.PHP_EOL.print_r($options['post'], true);
                    }
                    error_log($logData, 3, TB_DATA_PATH."/order/kakao.error.".date('ymd').'.log');

                    $this->errors[] = $http_code;
                    break;
            }
            $this->status= $http_code;
        }
        curl_close($ch);
        return $result;
    }

    public function init()
    {
        $url = $this->getApiUrl('init');
        if (empty($url)) return $this;
        $cart= $this->cart;
        $returnUrl = str_replace(":443", "", TB_SHOP_URL . '/billing.php?pg=KAKAOPAY');
        $amount = $cart['io_type'] === "1" ? $cart['io_price'] : ($cart['io_price'] + $cart['ct_price']);
        $amount *= $cart['ct_qty'];
        $options =[
            'post'=>            [
                'cid' => $this->billing_id,
                'partner_order_id' => $cart['od_id'],
                'partner_user_id' => $this->user['index_no'] ?: "",
                'item_name' => "[정기결제]" . $cart['ct_price'],
                'quantity' => 1,
                'total_amount' => $amount,
                'tax_free_amount' => 0,
                'approval_url' => $returnUrl . '&method=success&od_id=' . $cart['od_id'],
                'fail_url' => $returnUrl . '&method=fail&od_id=' . $cart['od_id'],
                'cancel_url' => $returnUrl . '&method=cancel&od_id=' . $cart['od_id'],
            ]
        ];
        $this->value= json_decode($this->post($url, $options), true);

//        error_log(print_r($options, true), 3, TB_DATA_PATH."/order/kakao_next_url.error.".date('ymd').'.log');

        return $this;
    }
}
