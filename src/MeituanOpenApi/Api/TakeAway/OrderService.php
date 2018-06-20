<?php

namespace MeituanOpenApi\Api\TakeAway;

use MeituanOpenApi\Api\RpcService;

/**
 * 订单服务
 */
class OrderService extends RpcService
{

    /** 查询订单－根据订单Id（7.3.1）
     * 通过订单ID主动查询订单明细，包括下单、菜品，配送，优惠、对账等信息。建议与7.5.1及7.5.4接口结合使用，以防系统漏单。
     * @param  string $orderId 订单Id
     * @return mixed
     */
    public function queryById($orderId)
    {
        return $this->client->call('get', 'waimai/order/queryById', ['orderId' => $orderId]);
    }


    /** 查询订单－根据流水号（7.3.2）
     * @param string $ePoiId erp方门店Id 最大长度100
     * @param integer $daySeq 门店下的订单流水号
     * @param string $date 日期【yyyyMMdd】, 非必须
     * @return mixed
     */
    public function queryByDaySeq($ePoiId, $daySeq, $date)
    {
        return $this->client->call('get', 'waimai/order/queryByDaySeq', ['ePoiId' => $ePoiId, 'daySeq' => $daySeq, 'date' => $date]);
    }


    /** 商家确认接单（7.3.3）
     * 新订单推单后，需要调用此接口确认接单。
     * @param string $orderId 订单Id
     * @return mixed
     */
    public function confirm($orderId)
    {
        return $this->client->call('post', 'waimai/order/confirm', ['orderId' => $orderId]);
    }


    /** 商家取消订单（7.3.4）
     * 新订单推送后，可以调用此接口取消订单。如商家已接入美团配送服务，则调用该接口会自动取消美团配送单，无需再调用取消配送单接口。
     * @param string $orderId 订单Id
     * @param string $reasonCode 取消原因码
     * @param string $reason 取消原因
     * @return mixed
     */
    public function cancel($orderId, $reasonCode, $reason)
    {
        return $this->client->call('post', 'waimai/order/cancel', ['orderId' => $orderId, 'reasonCode' => $reasonCode, 'reason' => $reason]);
    }


    /** 自配送－配送状态（7.3.5）
     * 配送方式是商家自配送时，需要调用此接口将配送信息同步到美团外卖。
     * @param string $orderId 订单Id
     * @param string $courierName 配送人名称
     * @param string $courierPhone 配送人电话
     * @return mixed
     */
    public function delivering($orderId, $courierName, $courierPhone)
    {
        return $this->client->call('post', 'waimai/order/delivering', ['orderId' => $orderId, 'courierName' => $courierName, 'courierPhone' => $courierPhone]);
    }


    /** 自配送场景－订单已送达（7.3.6）
     * 配送方式是商家自配送时，需要调用此接口将配送信息同步到美团外卖。
     * @param string $orderId 订单Id
     * @return mixed
     */
    public function delivered($orderId)
    {
        return $this->client->call('post', 'waimai/order/delivered', ['orderId' => $orderId]);
    }


    /** 众包配送场景－查询配送费(7.3.7)
     * 众包配送下单前，先调用该接口查看当前配送费
     * @param string $orderIds 订单Ids (多个订单号逗号隔开)
     * @return mixed
     */
    public function queryCrowdsourcingShippingFee($orderIds)
    {
        return $this->client->call('get', 'waimai/order/queryZbShippingFee', ['orderIds' => $orderIds]);
    }


    /** 众包配送场景－预下单(7.3.8)
     * 此接口仅用于众包配送场景下的预下单。
     *  -如预下单时配送费与查询时配送费一致，直接发起众包配送。
     *  -如配送费有变化需增加配送费并调用7.3.7接口再次发起众包配送。
     * @param string $orderId 订单Id
     * @param float $shippingFee 配送费，查询接口返回的配送费
     * @param float $tipAmount 小费，不加小费输入0.0
     * @return mixed
     */
    public function prepareCrowdsourcingDispatch($orderId, $shippingFee, $tipAmount)
    {
        return $this->client->call('post', 'waimai/order/prepareZbDispatch', ['orderId' => $orderId, 'shippingFee' => $shippingFee, 'tipAmount' => $tipAmount]);
    }


    /** 众包配送场景－配送单加小费(7.3.9)
     * 从发配送到骑手抢单前，这段时间都可以追加小费，可多次调用，小费只能增，不能减。
     * @param string $orderId 订单Id
     * @param float $tipAmount 小费
     * @return mixed
     */
    public function addCrowdsourcingDispatchTip($orderId, $tipAmount)
    {
        return $this->client->call('post', 'waimai/order/updateZbDispatchTip', ['orderId' => $orderId, 'tipAmount' => $tipAmount]);
    }


    /** 众包配送场景－确认下单(7.3.10)
     * 预下单失败后，再调用确认下单接口。
     * @param string $orderId 订单Id
     * @param float $tipAmount 小费，不加小费输入0.0
     * @return mixed
     */
    public function confirmCrowdsourcingDispatch($orderId, $tipAmount)
    {
        return $this->client->call('post', 'waimai/order/confirmZbDispatch', ['orderId' => $orderId, 'tipAmount' => $tipAmount]);
    }


    /** 美团专送场景－发配送(7.3.11)
     * @param string $orderId 订单Id
     * @return mixed
     */
    public function dispatchShip($orderId)
    {
        return $this->client->call('post', 'waimai/order/dispatchShip', ['orderId' => $orderId]);
    }


    /** 取消美团配送（除自配送场景）(7.3.12)
     * 此接口主要应用众包配送方式，送达之前都可以取消，其它美团配送方式，只能15分钟后无骑手接单情况下取消。
     * @param string $orderId 订单Id
     * @return mixed
     */
    public function cancelDispatch($orderId)
    {
        return $this->client->call('post', 'waimai/order/cancelDispatch', ['orderId' => $orderId]);
    }


    /** 订单同意退款(7.3.13)
     * 同意用户发起的退款申请
     * @param string $orderId 订单Id
     * @param string $reason 原因
     * @return mixed
     */
    public function agreeRefund($orderId, $reason)
    {
        return $this->client->call('post', 'waimai/order/agreeRefund', ['orderId' => $orderId, 'reason' => $reason]);
    }


    /** 订单拒绝退款(7.3.14)
     * 用户申请退款被商家第一次拒绝后，不能直接进行退款申诉，需联系美团客服后由客服裁决
     * @param string $orderId 订单Id
     * @param string $reason 原因
     * @return mixed
     */
    public function rejectRefund($orderId, $reason)
    {
        return $this->client->call('post', 'waimai/order/rejectRefund', ['orderId' => $orderId, 'reason' => $reason]);
    }


    /** 根据门店id, 批量查询待确认订单号列表(7.3.15)
     * 查询5分钟内未确认的订单号列表，已经推送成功的订单号不再返回，一次传入查询的门店不可超过10个
     * @param string $epoiIds 商家门店号集合,中间用逗号隔开
     * @return mixed
     */
    public function queryByEpoids($epoiIds)
    {
        return $this->client->call('post', 'waimai/order/queryByEpoids', ['epoiIds' => $epoiIds]);
    }


    /** 根据开发者id, 批量查询待确认订单号列表(7.3.16)
     * 查询近5分钟内未被商家确认的有效订单号列表，可多次拉取，一次拉取最多100单（按时间升序）
     * @param integer $developerId 开发者id
     * @param integer $maxOffsetId 上次拉取新单列表中最大的offserId，初始值可以为0
     * @param integer $size 每次拉取多少条，范围为1-100
     * @return mixed
     */
    public function queryNewOrdersByDevId($developerId, $maxOffsetId, $size)
    {
        return $this->client->call('get', 'waimai/order/queryNewOrdersByDevId', ['developerId' => $developerId, 'maxOffsetId' => $maxOffsetId, 'size' => $size]);
    }


    /** 部分退款-查询部分退款商品(7.3.17)
     * 订单完成后，在商家发起部分退款前，查询可被部分退款的商品详情
     * @param string $orderId 订单Id
     * @return mixed
     */
    public function queryPartRefundFoods($orderId)
    {
        return $this->client->call('get', 'waimai/order/queryPartRefundFoods', ['orderId' => $orderId]);
    }


    /** 部分退款-申请部分退款(7.3.18)
     * 订单完成后，可以调用此接口发起部分退款
     * @param string $orderId 订单Id
     * @param string $reason 申请部分退款的具体原因
     * @param string $foodData 部分退款菜品详情 (没有sku，sku_id为空字符串"" ,count不能为0)
     * @return mixed
     */
    public function applyPartRefund($orderId, $reason, $foodData)
    {
        return $this->client->call('post', 'waimai/order/applyPartRefund', ['orderId' => $orderId, 'reason' => $reason, 'foodData' => $foodData]);
    }


}