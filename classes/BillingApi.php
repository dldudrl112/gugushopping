<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

//sql_query('alter table shop_goods_option add payment_cycle smallint unsigned after io_use', true);
//sql_query("alter table shop_order add payment_cycle smallint unsigned comment '결제주기' after od_billkey", true);

/*
 * 결제 한건당 주문 1개?
 * 배송관련부분이 매 구독결제시마다 필요함?
 *
 *
 * 7페이지- 결제취소불가
 * 해당 구독 주문리스트 (결제내역?)
 * 배송비,쿠폰할인,포인트결제 이부분은 먼가요?
 * 옵션변경시- 주기 동시 변경됨.
 * -- 다음 결제일 생성
 * -- 결제횟수
 * */
class BillingApi
{
    private $apiUrl = "https://localhost:8000/api";
    private $apiMethods = [
        'billing' => "billing",
        'approve' => "payment/approve"
    ];
    private $billing_id= 'TCSUBSCRIP';//CT06206006
    private $billing_admin_key= '1eb42500c9dcb81bcf59d0e6434cf1f2';
    public $value = null;
    private $PgInstance= null;
    public $status= null;

    public function __construct($PgInstance)
    {
        $this->PgInstance = $PgInstance;
    }

    public function approve($pg_token= null){
        if(empty($v = $this->PgInstance->approve($pg_token)->value)) return $this;

        error_log(print_r($v, true), 3, TB_DATA_PATH."/order/paylog.".date('ymd').'.log');
        $this->value= $v;
        if(isset($v['code']) && !empty($v['code'])){
            $this->status= 500;
            return $this;
        }
        $od= $this->PgInstance->getOrder();
        $dt= new DateTime($v['approved_at']);
        $receipt_time= $dt->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s');

        $od_vat_mny= (int)$v['amount']['vat'];
        $od_tax_mny= (int)$v['amount']['total'] - $od_vat_mny;
        $od_free_mny= (int)$v['amount']['tax_free'];

        //dan :2 결제완료
        $result= sql_query("update shop_order set dan = 2, od_free_mny= {$od_free_mny}, od_tax_mny= {$od_tax_mny}, od_vat_mny= {$od_vat_mny}, receipt_time= '{$receipt_time}', od_app_no= '{$v['od_app_no']}', od_billkey= '{$v['od_billkey']}' where od_id ='{$od['od_id']}'");

        $this->status= $result ? 200 : 501;
        return $this;
    }

    public function setUser($user)
    {
        $this->PgInstance->setUser($user);
        return $this;
    }

    /*
     *
     * api서버 전송용 제작중....
     * */
//    public function postApiBilling(){
//        if(empty($this->value) || !is_array($this->value)) return $this;
//        $v= $this->value;
//        $od= $this->PgInstance->order;
//        $post= $v + ['od_id'=> $od['od_id'], 'payment_cycle'=> $od['payment_cycle']];
//
//        $json= json_decode($this->post($this->apiUrl, ['post'=> $post]), true);
//        if($this->status !== 200){
//            return $this;
//        }
//
//        return $this;
//    }

    /*
     *
     * api서버 정기결제 취소 제작중....
     * */
    public function cancelApiBilling(){
        if(empty($this->value) || !is_array($this->value)) return $this;
        $v= $this->value;
        $od= $this->PgInstance->order;
        $post= $v + ['od_id'=> $od['od_id'], 'payment_cycle'=> $od['payment_cycle']];

        $json= json_decode($this->post($this->apiUrl, ['post'=> $post]), true);
        if($this->status !== 200){
            return $this;
        }

        return $this;
    }


    public function post($url, $options= []){
        $ch = curl_init($url);
        $headers = [
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
                    $this->status = $http_code;
                    break;
            }
        }
        curl_close($ch);
        return $result;
    }


}