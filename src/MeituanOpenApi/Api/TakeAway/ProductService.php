<?php

namespace MeituanOpenApi\Api\TakeAway;

use MeituanOpenApi\Api\RpcService;

/**
 * 菜品服务
 */
class ProductService extends RpcService
{
    const DISH_MAP_API = 'https://open-erp.meituan.com/waimai-dish-mapping';

    /**
     * 根据ERP的门店id查询门店下的菜品基础信息【包含美团的菜品Id】(7.2.2)
     * @param string $ePoiId erp门店id 最大长度100
     * @return mixed
     */
    public function queryBaseListByEPoiId($ePoiId)
    {
        return $this->client->call('get', 'waimai/dish/queryBaseListByEPoiId', ['ePoiId' => $ePoiId]);
    }


    /**
     * 菜品映射链接 (7.2.3 跳转地址)
     * @param string $ePoiId erp门店id 最大长度100
     * @return string
     */
    public function getDishMapUrl($ePoiId)
    {
        return self::DISH_MAP_API . '?' . http_build_query([
                'signKey' => $this->client->getSignKey(),
                'appAuthToken' => $this->client->getToken(),
                'ePoiId' => $ePoiId,
            ]);
    }


    /**
     * 重定向至菜品映射链接 (7.2.3 重定向跳转)
     * @param string $ePoiId erp门店id
     */
    public function redirectDishMap($ePoiId)
    {
        header('Location:' . $this->getDishMapUrl($ePoiId));
    }


    /**
     * 建立菜品映射(美团商品与本地erp商品映射关系) （7.2.3 openapi接入）
     * @param string $ePoiId erp门店id 最大长度100
     * @param array $dishMappings 菜品映射(ERP在本地新建菜品后传与外卖菜品的关联关系)
     * -eDishCode string  erp方菜品id
     * -dishId    integer 美团菜品id，由7.2.2接口获取
     * -waiMaiDishSkuMappings  array 如果没有sku维度的信息，此数据结构允许为空
     *      -eDishSkuCode   string   erp方sku id
     *      -dishSkuId      string   美团sku id， 由7.2.2接口获取
     * @return mixed
     */
    public function mapping($ePoiId, $dishMappings)
    {
        return $this->client->call('post', 'waimai/dish/mapping', ['ePoiId' => $ePoiId, 'dishMappings' => $dishMappings]);
    }


    /**
     * 根据ERP的门店id查询门店下的菜品【不包含美团的菜品Id】(7.2.4)
     * @param string $ePoiId erp门店id 最大长度100
     * @param integer $offset 起始条目数
     * @param integer $limit 每页大小，须小于200
     * @return mixed
     */
    public function queryListByEPoiId($ePoiId, $offset, $limit)
    {
        return $this->client->call('get', 'waimai/dish/queryListByEPoiId', ['ePoiId' => $ePoiId, 'offset' => $offset, 'limit' => $limit]);
    }


    /**
     * 查询菜品分类 (7.2.5)
     * @return mixed
     */
    public function queryCateList()
    {
        return $this->client->call('get', 'waimai/dish/queryCatList');
    }


    /**
     * 批量上传／更新菜品 (7.2.6)
     * @param string $ePoiId erp门店id 最大长度100
     * @param array $dishes 菜品列表
     * -boxNum        integer  餐盒数量，创建时必须
     * -boxPrice      float    餐盒价格，不能为负数, 创建时必须
     * -isSoldOut     integer  是否售完，0:未售完，1:售完，创建时请设置为0，否则C端不显示。当isSoldOut=0,而且库存>0时,菜品才可以售卖。 创建时必须
     * -minOrderCount integer  最小购买数量，创建时必须
     * -unit          string   单位/规格，创建时必须
     * -categoryName  string   菜品分类，必须
     * -description   string   菜品描述，非必须
     * -dishName      string   菜名，同一分类下菜品名不能重复，必须
     * -EDishCode     string   erp方菜品Id，不同门店可以重复，同一门店内不能重复，最大长度100，必须
     * -ePoiId        string   erp方门店Id，同一分类下菜品名不能重复，必须
     * -picture       string   图片id或地址 支持jpg,png格式，图片需要小于1600*1200，非必须
     * -sequence      integer  分类下菜品的顺序，非必须
     * -price         float    菜品价格，float(8,2) 不能为负数，如果传递了skus价格，本参数可不传, 必须
     * -skus          array    sku信息，菜品下的多个sku信息，使用json数组格式传递参数, 非必须
     *      -skuId            string   erp方sku ID ，必须
     *      -spec             string   菜品的规格名称，如针对蛋炒饭（SPU）存在3个规格大份、中份、小份（SKU），当菜品只存在一个规格时，建议使用份、位、例等字眼
     *      -upc              string   sku的upc码(条形码)，非必须
     *      -stock            integer  库存数量，stock不能为负数，也不能为小数，传 "*" 表示库存无限，必须
     *      -price            float    价格，float(8,2), price不能为负数，不能超过10个字，必须
     *      -availableTimes   json     表示sku起售时间，要保证不同时间段之间不存在交集，默认与门店营业时间一致, 非必须 例：{"monday":"19:00-21:00,23:00-23:59","tuesday":"19:00-21:00","wednesday":"23:00-23:59","thursday":"23:00-23:59","friday":"19:00-21:00","saturday":"23:00-23:59","sunday":"19:00-21:00"}
     *      -locationCode     string   sku的料位码，非必须
     * @return mixed
     */
    public function batchUpload($ePoiId, $dishes)
    {
        return $this->client->call('post', 'waimai/dish/batchUpload', ['ePoiId' => $ePoiId, 'dishes' => $dishes]);
    }


    /**
     * 更新菜品价格【sku的价格】(7.2.7)
     * @param string $ePoiId erp门店id 最大长度100
     * @param array $dishSkuPrices 菜品sku价格
     * -eDishCode  string  erp方菜品id， 必须
     * -skus       array   菜品sku， 必须
     *      -skuId     array   erp方菜品sku id， 必须
     *      -price     float   菜品sku价格， 必须
     * @return mixed
     */
    public function updatePrice($ePoiId, $dishSkuPrices)
    {
        return $this->client->call('post', 'waimai/dish/updatePrice', ['ePoiId' => $ePoiId, 'dishSkuPrices' => $dishSkuPrices]);
    }


    /**
     * 更新菜品库存【sku的库存】(7.2.8)
     * @param string $ePoiId erp门店id 最大长度100
     * @param array $dishSkuStocks 菜品库存列表, 使用json数组格式传递参数
     * -eDishCode   string     erp方菜品id， 必须
     * -skus        array      sku信息，菜品下的多个sku信息，使用json数组格式传递参数, 必须
     *      -skuId     string      erp方菜品sku id， 必须
     *      -stock     integer     菜品库存，长度：int(4) 必须
     * @return mixed
     */
    public function updateStock($ePoiId, $dishSkuStocks)
    {
        return $this->client->call('post', 'waimai/dish/updateStock', ['ePoiId' => $ePoiId, 'dishSkuStocks' => $dishSkuStocks]);
    }


    /**
     * 新增/更新菜品分类 (7.2.9)
     * @param string $oldCatName 原始的菜品分类名称, 更新时必须
     * @param string $catName 菜品分类名称
     * @param integer $sequence 菜品排序(数字越小，排名越靠前,不同分类顺序可以相同),    新建时必须
     * @return mixed
     */
    public function addOrUpdCate($oldCatName, $catName, $sequence)
    {
        return $this->client->call('post', 'waimai/dish/updateCat', ['oldCatName' => $oldCatName, 'catName' => $catName, 'sequence' => $sequence]);
    }


    /**
     * 上传菜品图片，返回图片id (7.2.10)
     * @param string $ePoiId erp门店id 最大长度100
     * @param string $imageName 图片名称 文件名只能是字母或数字,且必须以.jpg结尾
     * @param string $file 上传文件base64字节流 byte, 支持jpg,jpeg格式，图片需要小于1600*1200
     * @return mixed
     */
    public function uploadImage($ePoiId, $imageName, $file)
    {
        return $this->client->call('post', 'waimai/image/upload',
            [
                'ePoiId' => $ePoiId,
                'imageName' => $imageName,
                'file' => $file
            ],
            ['contentType：multipart/form-data'], false
        );
    }


    /**
     * 删除菜品 (7.2.11)
     * @param string $ePoiId erp门店id 最大长度100
     * @param string $eDishCode erp方菜品id
     * @return mixed
     */
    public function delete($ePoiId, $eDishCode)
    {
        return $this->client->call('post', 'waimai/dish/delete', ['ePoiId' => $ePoiId, 'eDishCode' => $eDishCode]);
    }


    /**
     * 删除菜品sku (7.2.12)
     * @param string $eDishCode erp方菜品id
     * @param string $eDishSkuCode erp方skuId
     * @return mixed
     */
    public function deleteSku($eDishCode, $eDishSkuCode)
    {
        return $this->client->call('post', 'waimai/dish/deleteSku', ['eDishCode' => $eDishCode, 'eDishSkuCode' => $eDishSkuCode]);
    }


    /**
     * 删除菜品分类 (7.2.13)
     * @param string $catName 菜品分类名称
     * @return mixed
     */
    public function delCate($catName)
    {
        return $this->client->call('post', 'waimai/dish/deleteCat', ['catName' => $catName]);
    }


    /**
     * 查询菜品属性 (7.2.14)
     * @param string $eDishCodes erp方菜品id
     * @return mixed
     */
    public function queryPropertyList($eDishCodes)
    {
        return $this->client->call('get', 'waimai/dish/queryPropertyList', ['eDishCode' => $eDishCodes]);
    }


    /**
     * 批量创建/更新菜品属性 (7.2.15)
     * 可以同时创建更新多个菜品的多个属性；如果菜品已有菜品属性调用此接口后会全量覆盖旧的菜品属性；properties字段传空则会清空该菜品的所有属性。
     * @param array $dishProperty 菜品属性信息,例：[{"eDishCode":"erp123_01", "properties":[{"propertyName":"辣度", "values":["微辣","超辣"]}]}]
     * -eDishCode  string  erp方菜品id
     * -properties  array  属性信息
     *      -propertyName  string  属性名
     *      -values        array   属性值
     * @return mixed
     */
    public function batchAddOrUpdateProperty($dishProperty)
    {
        return $this->client->call('post', 'waimai/dish/updateProperty', ['dishProperty' => $dishProperty]);
    }


    /**
     * 批量查询菜品信息 (7.2.16)
     * 根据eDishCode批量查询外卖菜品信息
     * @param string $ePoiId erp门店id，最大长度100
     * @param string $eDishCodes erp方要查询菜品id（多个，以逗号隔开，最多100个）
     * @return mixed
     */
    public function batchProducts($ePoiId, $eDishCodes)
    {
        return $this->client->call('post', 'waimai/dish/queryListByEdishCodes', ['ePoiId' => $ePoiId, 'eDishCodes' => $eDishCodes]);
    }

}