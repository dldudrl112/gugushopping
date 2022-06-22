<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

class Subscription
{
    public static $tableName = 'subscriptions';
    private $tableComment = '구독 테이블';
    private $primary = "id";
    private $keys = [
        'id' => 'PRIMARY KEY (`id`)',
        'member_id' => 'KEY `member_id` (`member_id`)'
    ];
    private $error = [];
    private $scheme = [
        'id' => [
            'query' => "bigint unsigned NOT NULL auto_increment"
        ],
        'member_id' => [
            'query' => "int unsigned NOT NULL comment '회원 index_no' default 0"
        ],
        'order_id' => [
            'query' => "varchar(30) NOT NULL comment 'od_id 카카오페이용' default ''"
        ],
        'is_subscribed' => [
            'query' => "tinyint NOT NULL comment '0: 구독취소, 1: 구독중' default 0"
        ],
        'pg' => [
            'query' => "varchar(10) not null comment 'PG사' default 'inicis'"
        ],
        'pg_id' => [
            'query' => "pg_id(40) not null comment 'PG사 결제 아이디' default 'gugushop01'"
        ],
        'billing_key' => [
            'query' => "varchar(52) not null comment '정기결제용 키값' default ''"
        ],
        'price' => [
            'query' => "int unsigned NOT NULL comment '결제금액' default 0"
        ],
        'gs_id' => [
            'query' => "int unsigned NOT NULL comment '상품 index_no' default 0"
        ],
        'io_id' => [
            'query' => "varchar(255) NOT NULL comment '상품옵션 io_id' default ''"
        ],
        'goods_name' => [
            'query' => "varchar(100) NOT NULL default ''"
        ],
        'new_order_data' => [
            'query' => "text comment '신규 주문생성시 데이터(json_encode 데이터)'"
        ],
        'next_billing_date' => [
            'query' => "date comment '다음 결제일' "
        ],
        'payment_cycle' => [
            'query' => "smallint unsigned NOT NULL comment '결제주기 (월단위)' default 0"
        ],
        'billing_day' => [
            'query' => "tinyint unsigned NOT NULL comment '매달 결제일 (next_billing_date용도)' default 1"
        ],
        'memo' => [
            'query' => "text comment '관리자 메모'"
        ],
        'updated_at' => [
            'query' => "datetime DEFAULT CURRENT_TIMESTAMP "
        ],
        'created_at' => [
            'query' => "datetime comment '구독일시 (생성일)' "
        ]
    ];
    private $order = [];
    private $order_indexes = ['od_no' => null, 'od_id' => null];
    private $usedOrderFields = ['name', 'mb_id', 'od_id', 'cellphone', 'email', 'b_name', 'b_cellphone', 'b_telephone', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon', 'gs_id', 'receipt_time', 'od_billkey', 'use_price', 'od_time', 'od_goods', 'pt_id', 'shop_id', 'paymethod', 'seller_id', 'sum_qty', 'goods_price', 'supply_price', 'od_pwd', 'od_pg', 'memo'];
    public static $instance;
    public $offset= 30;
    public $page= 1;
    public $lastInsertId = null;
    public $value = null;
    public $status = null;
    public $row= null;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function install()
    {
        $tableName = self::$tableName;
        if (!property_exists($this, 'scheme') || empty($this->scheme)) return null;
        $temp = sql_query("SHOW TABLES LIKE  '{$tableName}'");
        if (is_object($temp) && $temp->num_rows >= 1) {
            return null;
        }
        $querys = [];
        foreach ($this->scheme as $key => $property) {
            $querys[] = $key . " " . $property['query'];
        }

        $key = "";
        if (property_exists($this, 'keys') && !empty($this->keys)) {
            $key = ", " . implode(",", $this->keys);
        }

        $after = "ENGINE=InnoDB DEFAULT CHARSET=utf8 comment='{$this->tableComment}';";
        $query = sprintf(
            "CREATE TABLE IF NOT EXISTS %s (%s %s) %s",
            $tableName,
            implode(",", $querys),
            $key,
            $after
        );
        $result = sql_query($query, true);
        if ($result) {
            sql_query("alter table shop_order add subscription_id bigint unsigned comment 'subscription id'", true);
            sql_query("alter table shop_order add INDEX subscription_id (`subscription_id`)", true);
        }
        return $result;
    }

    public function showByOrderId($od_id)
    {
        $row= sql_fetch("select S.* from " . self::$tableName . " S inner join shop_order O on S.id= O.subscription_id where O.od_id = '{$od_id}'", true);
        return $this->setRow($row)->row;
    }

    public function getOrdersBySubscription_id($subscription_id)
    {
        $result= sql_query("select * from shop_order where subscription_id = '{$subscription_id}'");
        return $this->fetch_all($result);
    }


    public function isTestPg($row= null){
        $row= is_null($row) ? $this->row : $row;
        if(empty($row)) return null;
        return in_array($row['pg_id'], ['TCSUBSCRIP', 'INIBillTst', 'INIpayTest', 'iniescrow0']);
    }

    public function show($id, $key = 'id', $isSet= true)
    {
        $row= sql_fetch("select * from " . self::$tableName . " where {$key} = '{$id}'");
        if($isSet) $row= $this->setRow($row)->row;
        return $row;
    }

    public function index($params=null, $fieldName= '*', $orderBy= 'order by created_at desc'){
        $where= "";
        if(!is_null($params) && !empty($params)) {
            $mapQuery = [];
            foreach($params as $k=> $v){
                $mapQuery[]= "{$k}= '{$v}'";
            }
            $where= "where ".join(" and ", $mapQuery);
        }

        $query= "select {$fieldName} from %s %s %s limit %d, %d";
        $sql= sprintf($query,
            self::$tableName,
            $where,
            $orderBy,
            ($this->page - 1) * $this->offset,
            $this->offset
        );

        $result= sql_query($sql, true);
        if(!$result) return $this;
        $this->value= $this->fetch_all($result);
        return $this;
    }

    public function fetch_all($result, $opt= MYSQLI_ASSOC){
        //return mysqli_fetch_all($result, $opt);
        $rows= [];
        while($row= sql_fetch_array($result)){
            $rows[]= $row;
        }

        return $rows;
    }

    public function updateOrderData($request, $id) {
        $row= $this->show($id, 'id', false);
        $orderData= $this->decodeOrderData($row['new_order_data']);
        foreach($request as $k=>$v){
            if(!in_array($k, $this->usedOrderFields)) continue;
            $orderData[$k]= $v;
        }


        return $this->update(['new_order_data'=> sql_real_escape_string($this->encodeOrderData($orderData))], $id);
    }


    public function update($request, $id= null, $key = null)
    {
        $key = is_null($key) ? $this->primary : $key;
        $id  = is_null($id) && !empty($this->row)  ? $this->row['id'] : $id;
        if (empty($request) || empty($id)) {
            $this->error[] = "Empty request";
            return $this;
        }

        $set = "";
        foreach ($request as $k => $value) {
            if (!isset($this->scheme[$k]) || $k === $this->primary) {
                continue;
            }
            $set .= ($set ? ',' : '') . " {$k} = '{$value}'";
        }

        $sql = sprintf("update %s set %s where %s = '%s'", self::$tableName, $set, $key, $id);

        $result = sql_query($sql, true);
        if (empty($result)) {
            $this->error[] = sql_error_info();
        }

        return $this;
    }

    public function getError()
    {
        if (empty($this->error)) return "";
        return implode(",", $this->error);
    }

    public function setOrderIndexes($order = null)
    {
        $order = is_null($order) ? $this->order : $order;
        $this->order_indexes['od_id'] = $order['od_id'];
        $this->order_indexes['od_no'] = $order['od_no'];
        return $this;
    }

    public function setOrderById($od_id)
    {
        $this->order = sql_fetch("select * from shop_order where od_id = '{$od_id}'");

        return $this->setOrderIndexes()
            ->filterOrder();
    }

    public function setRow($row){
        if(!empty($row) && isset($row['new_order_data'])){
            $row['order_data']= $this->decodeOrderData($row['new_order_data']);
        }
        $this->row= $row;
        return $this;
    }

    public function setOrder($order)
    {
        $this->order = $order;
        return $this->setOrderIndexes()
            ->filterOrder();
    }

    public function makeNewOrderData($od_id){
        $row = sql_fetch("select * from shop_order where od_id = '{$od_id}'");

        $filter = [];
        foreach ($this->usedOrderFields as $field) {
            if (!isset($row[$field])) continue;
            $filter[$field] = $row[$field];
        }

        return $filter;
    }

    public function filterOrder()
    {
        $filter = [];
        foreach ($this->usedOrderFields as $field) {
            if (!isset($this->order[$field])) continue;
            $filter[$field] = $this->order[$field];
        }
        $this->value = $filter;
        return $this;
    }

    public function encodeOrderData($order = null)
    {
        $order = is_null($order) ? $this->order : $order;
        //$order['name'] = $order['name'] ?: $order['b_name'];
        return htmlspecialchars(json_encode($order));
    }

    public function decodeOrderData($orderData = null)
    {
        $orderData = is_null($orderData) ? $this->row['new_order_data'] : $orderData;
        if(empty($orderData)) return [];

        $data = json_decode(htmlspecialchars_decode($orderData), true);
        if (isset($data['od_goods']) && !empty($data['od_goods'])) {
            $data['goods'] = unserialize($data['od_goods']);
        }
        return $data;
    }

    public function getGoodsOptionsByRow($isPaymentCycle=true){
        return $this->getGoodsOptions($this->row['gs_id'], $isPaymentCycle ? $this->row['payment_cycle'] : null);
    }

    public function getGoodsOptions($gs_id, $paymentCycle= null)
    {
        $sql = " select * from shop_goods_option where gs_id = '{$gs_id}' and io_type = '0' ";
        if(is_numeric($paymentCycle)) {
            $sql .= " and payment_cycle = {$paymentCycle}";
        }
        $result = sql_query($sql);
        if (empty($result)) return [];
//        echo function_exists('mysqli_query');
        //return mysqli_fetch_all($result);
        $rows = [];
        while ($row = sql_fetch_array($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function mapData()
    {
        global $default;
        $order = $this->order;
        $order['name'] = $order['name'] ?: $order['b_name'];
        $mb = get_member($order['mb_id'], 'index_no');

        $day = (int)date("d", strtotime($order['receipt_time']));
        $cart = sql_fetch("select C.io_id, G.gname from shop_cart as C left join shop_goods as G on C.gs_id= G.index_no where C.od_id = '{$this->order_indexes['od_id']}' ");
//        $payment_cycle = preg_replace("/[^0-9]*/s", "", $cart['io_id'])?: 0;
//        $payment_cycle= substr($payment_cycle,0,1);
        $cart['gname'] = $cart['gname'] ?: '구구';
        $mapData = [
            'goods_name' => $cart['gname'] . " 정기구독",
            'new_order_data' => $this->encodeOrderData(),
            'next_billing_date' => $this->computeNextBillingDate($order['receipt_time'], $order['payment_cycle']),
            'billing_key' => $order['od_billkey'],
            'member_id' => $mb['index_no'],
            'is_subscribed' => 1,
            'order_id' => $order['od_id'],
            'gs_id' => $order['gs_id'],
            'io_id' => $cart['io_id'],
            'price' => $order['goods_price'],
            'payment_cycle' => $order['payment_cycle'],
            'pg' => $order['od_pg'],
            'pg_id'=> $order['od_pg']==='inicis'? $default['de_inicis_mid']:$default['de_kakaopay_mid'],
            'billing_day' => $day,
        ];
        foreach ($this->usedOrderFields as $field => $mapField) {
            if (!isset($order[$field])) continue;
            $mapData[$mapField] = $order[$field];
        }

        return $mapData;
    }

    public function syncSubscriptionId()
    {
        if (empty($this->lastInsertId) || empty($this->order_indexes['od_id']) || empty($this->order_indexes['od_no'])) return false;
        $result = sql_query("update shop_order set subscription_id= {$this->lastInsertId} where od_id = '{$this->order_indexes['od_id']}' and od_no = '{$this->order_indexes['od_no']}'");
        return $result;
    }

    public function store($data = null)
    {
        $data = $data ?: $this->mapData();
        if (empty($data)) {
            $this->error[] = "[insert] empty post data";
            return $this;
        }

        $createdAt = isset($data['created_at']) ? $data['created_at'] : date("Y-m-d H:i:s");
        $sql = "insert into %s (goods_name, io_id, new_order_data, next_billing_date, billing_key, 
                                member_id, is_subscribed, gs_id,  payment_cycle, billing_day,
                                price, order_id, pg, pg_id, created_at
                            ) values (
                                '%s', '%s', '%s', '%s', '%s',                                                                                                           %d, %d, %d, %d, %d, 
                                      %d, '%s', '%s', '%s', '%s'
                            )";
        $sql = sprintf($sql,
            self::$tableName,

            sql_real_escape_string($data['goods_name']),
            sql_real_escape_string($data['io_id']),
            sql_real_escape_string($data['new_order_data']),
            $data['next_billing_date'],
            $data['billing_key'],

            $data['member_id'],
            $data['is_subscribed'],
            $data['gs_id'],
            $data['payment_cycle'],
            $data['billing_day'],

            $data['price'],
            $data['order_id'],
            $data['pg'],
            $data['pg_id'],
            $createdAt
        );

        $result = sql_query($sql);
        if ($result) {
            $this->lastInsertId = sql_insert_id();
        } else {
            $this->lastInsertId = null;
            $this->error[] = sql_error_info();
        }

        return $this;
    }

    public function getNextBillingDate()
    {
        return $this->computeNextBillingDate($this->order['receipt_date'], $this->order['payment_cycle']);
    }

    public function computeNextBillingDate($date, $nextMonth = 1)
    {
        if (empty($date)) return "";
        $dateTime = new DateTime($date);
        $beforeMonth = (int)$dateTime->format("m");
        $dateTime->add(new DateInterval("P{$nextMonth}M"));

        //다다음달로 지정될경우
        if ($beforeMonth + $nextMonth < (int)$dateTime->format('m')) {
            $dateTime->sub(new DateInterval("P1M"));
            return $dateTime->format("Y-m-t");
        }

        return $dateTime->format('Y-m-d');
    }

    public function getBillingDay()
    {
        return substr($this->getNextBillingDate(), -2);
    }

}

//var_dump(Subscription::getInstance()->install());
//sql_query("alter table subscriptions add price int unsigned NOT NULL comment '결제금액' default 0 after `billing_key`", true);
//$sql = "
//select name, mb_id, od_id, cellphone, email, b_name,b_cellphone,b_telephone,b_zip,b_addr1, b_addr2, b_addr3, b_addr_jibeon, gs_id, receipt_time, od_billkey, use_price, od_time, od_goods, pt_id, shop_id,paymethod, seller_id, sum_qty, goods_price, supply_price, od_pwd, od_pg from shop_order O
//where
//O.od_id in (21071708091252,
//21071814524281,
//21072007324188,
//21072013404997,
//21072013444710,
//21072013461688,
//21072013474925,
//21072623153641,
//21080121185144,
//21080121300341,
//21061620401814,
//21062118375814,
//21062118284360,
//21071019430255,
//21072000332218,
//21073012092944,
//21070716360493,21081414133558) order by O.name";
//$subscription = Subscription::getInstance();
//$result = sql_query($sql);
//
//while ($row = sql_fetch_array($result)) {
//    $od_id= $row['od_id'];
//    $cart = sql_fetch("select C.*, G.gname from shop_cart C inner join shop_goods as G on C.gs_id= G.index_no  where C.od_id = '{$od_id}'");
////    $io = sql_fetch("select * from shop_goods_option where gs_id = '{$cart['gs_id']}' and io_id = '{$cart['io_id']}'");
//
////    echo $row['name'] . ($cart['io_id']) . $row['receipt_time'] . "<br>";
//    $row['name']= $row['name'] ? $row['name'] : $row['b_name'];
//
//    $day = (int)date("d", strtotime($row['receipt_time']));
//    $payment_cycle = preg_replace("/[^0-9]*/s", "", $cart['io_id'])?: 0;
//    $mb= get_member($row['mb_id'], 'index_no');
//    $payment_cycle= substr($payment_cycle,0,1);
//
//    unset($row['od_id']);
//    $map = [
//        'goods_name' => $cart['gname'] . " 정기결제",
//        'new_order_data' => htmlspecialchars(json_encode($row)),
//        'next_billing_date' => $subscription->computeNextBillingDate($row['receipt_time']),
//        'billing_key' => $row['od_billkey'],
//        'member_id' => $mb['index_no'],
//        'is_subscribed' => 1,
//        'gs_id' => $row['gs_id'],
//        'io_id' => $cart['io_id'],
//        'price' => $row['goods_price'],
//        'payment_cycle' => $payment_cycle,
//        'billing_day' => $day,
//    ];
//    $subscription->store($map);
//    //print_r($row);
//    if($subscription->getError()){
//        echo "Error: ".$subscription->getError();
//        die();
//    }
//    sql_query("update shop_order set subscription_id= '{$subscription->lastInsertId}' where od_id = '{$cart['od_id']}' and od_no = '{$cart['od_no']}'", true);
//}

