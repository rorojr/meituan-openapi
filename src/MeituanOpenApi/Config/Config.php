<?php

namespace MeituanOpenApi\Config;

use InvalidArgumentException;

class Config
{
    private $developer_id;   //	开发者ID
    private $business_id;    //	1: 接入团购&闪惠业务 2: 接入外卖业务
    private $signKey;
    private $sandbox;

    private $request_url;

    private $log;

    private $default_request_url = "http://api.open.cater.meituan.com/";
    private $default_sandbox_request_url = "http://api.open.cater.meituan.com/";

    // private $default_request_url = "http://waimaiopen.meituan.com";
    // private $default_sandbox_request_url = "http://test.waimaiopen.meituan.com";

    public function __construct($developer_id, $business_id, $sign_key, $sandbox)
    {
        if ($sandbox == false) {
            $this->request_url = $this->default_request_url;
        } elseif ($sandbox == true) {
            $this->request_url = $this->default_sandbox_request_url;
        } else {
            throw new InvalidArgumentException("the type of sandbox should be a boolean");
        }

        if (empty($developer_id)) {
            throw new InvalidArgumentException("developer_id is required");
        }

        if (empty($business_id)) {
            throw new InvalidArgumentException("business_id is required");
        }

        if (empty($sign_key)) {
            throw new InvalidArgumentException("sign_key is required");
        }

        $this->developer_id = $developer_id;
        $this->business_id = $business_id;
        $this->signKey = $sign_key;
        $this->sandbox = $sandbox;
    }


    /**
     * 获取开发者ID
     * @return mixed
     */
    public function developerId()
    {
        return $this->developer_id;
    }


    /**
     * 获取业务类型
     * @return mixed
     */
    public function businessId()
    {
        return $this->business_id;
    }


    public function getSignKey()
    {
        return $this->signKey;
    }


    public function get_request_url()
    {
        return $this->request_url;
    }

    public function set_request_url($request_url)
    {
        $this->request_url = $request_url;
    }

    public function get_log()
    {
        return $this->log;
    }

    public function set_log($log)
    {
        if (!method_exists($log, "info")) {
            throw new InvalidArgumentException("logger need have method 'info(\$message)'");
        }
        if (!method_exists($log, "error")) {
            throw new InvalidArgumentException("logger need have method 'error(\$message)'");
        }
        $this->log = $log;
    }
}