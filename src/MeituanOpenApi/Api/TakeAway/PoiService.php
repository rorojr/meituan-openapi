<?php

namespace MeituanOpenApi\Api\TakeAway;

use MeituanOpenApi\Api\RpcService;

/**
 * 门店服务
 */
class PoiService extends RpcService
{

    /** 门店置营业 (7.4.1)
     * 设置门店营业，门店营业后用户才可以下单。通过appAuthToken定位到要操作的门店。
     * @return mixed
     */
    public function open()
    {
        return $this->client->call('post', 'waimai/poi/open', []);
    }


    /** 门店置休息 (7.4.2)
     * 设置门店状态为休息中。通过appAuthToken定位到要操作的门店。
     * @return mixed
     */
    public function close()
    {
        return $this->client->call('post', 'waimai/poi/close', []);
    }


    /** 修改门店营业时间 (7.4.3)
     * @param string $openTime 表示周一至周日，该时间段内均营业。';'为日期分割符，','为当天时间分隔符
     * 例：“5:00-9:00,15:30-22:00;00:00-00:00;5:00-9:00,15:30-22:00;00:00-00:00;15:30-22:00;00:00-00:00;00:00-00:00”
     * @return mixed
     */
    public function updateOpenTime($openTime)
    {
        return $this->client->call('post', 'waimai/poi/updateOpenTime', ['openTime' => $openTime]);
    }


    /** 查询门店是否延迟发配送 (7.4.4)
     * 延迟发配送只针对美团配送的 自建、代理两种配送方式
     * @return mixed
     */
    public function queryDelayDispatch()
    {
        return $this->client->call('get', 'waimai/poi/queryDelayDispatch', []);
    }


    /** 设置延迟发配送时间 (7.4.5)
     * 如果门店在延迟发配送名单内，才能设置延迟时间。延迟配送的前提必须是自建配送或者代理配送，且需要由美团的销售人员另外申请才可以使用该功能。
     * @param integer $delaySeconds 延迟发配送时间单位秒，该参数需在300-600秒之间
     * @return mixed
     */
    public function updateDelayDispatch($delaySeconds)
    {
        return $this->client->call('post', 'waimai/poi/updateDelayDispatch', ['delaySeconds' => $delaySeconds]);
    }


    /** 查询门店信息 (7.4.6)
     * @param string $ePoiIds 门店Ids ERP方门店id(半角逗号分隔)
     * @return mixed
     */
    public function queryPoiInfos($ePoiIds)
    {
        return $this->client->call('get', 'waimai/poi/queryPoiInfo', ['ePoiIds' => $ePoiIds]);
    }


    /** 查询门店评价信息 (8.2.1)
     * @param string $ePoiId ERP方门店id 最大长度100
     * @param string $startTime 查询评价起始时间
     * @param string $endTime 查询评价结束时间
     * @param integer $offset 查询起始位置
     * @param integer $limit 查询条数
     * @return mixed
     */
    public function queryReviewList($ePoiId, $startTime, $endTime, $offset, $limit)
    {
        return $this->client->call('get', 'waimai/poi/queryReviewList', [
            'ePoiId' => $ePoiId,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'offset' => $offset,
            'limit' => $limit
        ]);
    }


    /** 外卖评价回复接口 (8.2.2)
     * @param string $commentId 外卖评价ID
     * @param string $ePoiId ERP方门店id 最大长度100
     * @param string $reply 商家回复内容
     * @return mixed
     */
    public function addReply($commentId, $ePoiId, $reply)
    {
        return $this->client->call('post', 'waimai/poi/addReply', ['commentId' => $commentId, 'ePoiId' => $ePoiId, 'reply' => $reply]);
    }

}