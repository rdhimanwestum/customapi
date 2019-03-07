<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 3/6/19
 * Time: 11:08 AM
 */

namespace Westum\CustApi\Cron;

class Customer
{
    /**
     * @var \Magento\AdvancedSearch\Model\Client\ClientResolver
     */
    protected $clientResolver;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Elasticsearch\Model\Adapter\Elasticsearch
     */
    protected $adapterElasticsearch;

    public function __construct(
        \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Elasticsearch\Model\Adapter\Elasticsearch $adapterElasticsearch
    )
    {
        $this->clientResolver = $clientResolver;
        $this->scopeConfig = $scopeConfig;
        $this->adapterElasticsearch = $adapterElasticsearch;
    }

    public function execute() {

        try {
            $params = [];
            $params['hostname'] = $this->getStoreConfigData('catalog/search/elasticsearch_server_hostname');
            $params['engine'] = $this->getStoreConfigData('catalog/search/engine');
            $params['port'] = $this->getStoreConfigData('catalog/search/elasticsearch_server_port');
            $params['index'] = $this->getStoreConfigData('catalog/search/elasticsearch_index_prefix');
            $params['enableAuth'] = $this->getStoreConfigData('catalog/search/elasticsearch_enable_auth');
            $params['username'] = '';
            $params['password'] = '';
            $params['timeout'] = $this->getStoreConfigData('catalog/search/elasticsearch_server_timeout');
            $elasticsearch = $this->clientResolver->create($params['engine'], $params);

            $query = [
                'index' => 'westum_custapi',
                'type' => '_doc',

            ];

           $data =  $elasticsearch->query($query); //Get Data From Elastic Search

            //Customers will Be Created Here//

        }catch (\Exception $e) {
             echo $e->getMessage();
        }

}

    /**
     * Get Store Config Data
     * @param $path
     * @return mixed|void
     */
    public function getStoreConfigData($path) {
        if(!$path)
            return;

        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );
    }
}