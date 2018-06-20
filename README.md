# PHP SDK 接入指南 & CHANGELOG

## 转自其他人，在此基础上开发

## 仿照饿了么openApi编写

## 接入指南

  1. PHP version >= 5.4 & curl extension support
  2. 通过composer安装SDK
  3. 创建Config配置类，填入key，secret和sandbox参数
  4. 使用sdk提供的接口进行开发调试
  5. 上线前将Config中$sandbox值设为false以及填入正式环境的key和secret

## 安装方法
composer require meituan-openapi/meituan-openapi-sdk:dev-master
  
### 基本用法

```php
        // 门店映射
        $config = new Config($developerId, $businessId, $signKey);
        $client = new OAuthClient($config);
        $url = $client->storemap($ePoiId);
        header('location: ' . $url);

```



