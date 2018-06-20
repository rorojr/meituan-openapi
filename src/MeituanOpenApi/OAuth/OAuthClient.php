<?php

namespace MeituanOpenApi\OAuth;

use MeituanOpenApi\Config\Config;
use Exception;

class OAuthClient
{
    private $developerId;  //开发者id
    private $businessId;   //1: 接入团购&闪惠业务 2: 接入外卖业务
    private $signKey;
    private $token_url;
    private $authorize_url;
    private $log;
    const  STORE_MAP_API = 'https://open-erp.meituan.com/storemap';

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->developerId = $config->developerId();
        $this->businessId = $config->businessId();
        $this->signKey = $config->getSignKey();
        $this->token_url = $config->get_request_url() . "/token";
        $this->authorize_url = $config->get_request_url() . "/authorize";
        $this->log = $config->get_log();
    }


    /**
     * 生成授权url
     * @param string $ePoiId 客户端唯一标识
     * @param string $ePoiName ERP商家门店名
     * @return string
     */
    public function getAuthUrl($ePoiId, $ePoiName = '')
    {
        //获取登录的客户ID
        $query = [
            'developerId' => $this->developerId,
            'businessId' => $this->businessId,
            'ePoiId' => $ePoiId,
            'signKey' => $this->signKey,
        ];

        //非必须
        if (!empty($ePoiName)) {
            $query['ePoiName'] = $ePoiName;
        }

        $queryStr = http_build_query($query);
        return $this->authorize_url . '?' . $queryStr;
    }


    /**
     * 数字签名.
     *
     * @param $params
     * @return string
     */
    public function signature(&$params)
    {
        $result = $this->signKey;

        ksort($params);

        foreach ($params as $key => &$param) {
            $param = is_array($param) ? json_encode($param) : $param;
            $result .= $key . $param;
        }

        return strtolower(sha1($result));
    }


    /**
     * 门店映射
     * @param $ePoiId
     * @return mixed
     */
    public function storemap($ePoiId)
    {
        $query = [
            'developerId' => $this->developerId,
            'businessId' => $this->businessId,
            'ePoiId' => $ePoiId,
            'signKey' => $this->signKey,
        ];
        return $this->request(self::STORE_MAP_API, $query);
    }


    /**
     * 头部信息
     * @return array
     */
    private function getHeaders()
    {
        return array(
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
        );
    }


    /**
     * 发起get请求
     * @param $url
     * @param $data
     * @return mixed
     * @throws Exception
     */
    private function request($url, $data)
    {
        $log = $this->log;
        if ($log != null) {
            $log->info("request data: " . json_encode($data));
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $request_response = curl_exec($ch);
        if (curl_errno($ch)) {
            if ($log != null) {
                $log->error("error: " . curl_error($ch));
            }
            throw new Exception(curl_error($ch));
        }
        $response = json_decode($request_response);

        if (is_null($response)) {
            throw new Exception("response :" . $request_response);
        }

        if ($log != null) {
            $log->info("response: " . $response);
        }

        //关闭cURL资源，并且释放系统资源
        curl_close($ch);

        return $response;
    }

}

