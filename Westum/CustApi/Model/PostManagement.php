<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 2/26/19
 * Time: 10:08 AM
 */

namespace Westum\CustApi\Model;


class PostManagement {

    const ALLOWED_CONTENT_TYPE = 'application/json';
    const OAUTH_STRING = 'Bearer';

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\AdvancedSearch\Model\Client\ClientResolver
     */
    protected $clientResolver;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->request = $request;
        $this->clientResolver = $clientResolver;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getPost()
    {
        try {
            if($this->request->getHeader('Content-Type') != self::ALLOWED_CONTENT_TYPE)
                return;

            $jsonDataToArray = json_decode($this->request->getContent(), true);
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

            if($elasticsearch->testConnection()) {
                if(!empty($jsonDataToArray) && is_array($jsonDataToArray)) {
                    foreach ($jsonDataToArray as $k => $data) {
                        $query = [
                            'index' => 'westum_custapi',
                            'type' => 'document',
                            'body' => [
                                0 => [
                                    'index' => [
                                        '_id' => $k,
                                        '_type' => '_doc',
                                        '_index' => 'westum_custapi'
                                    ]
                                ],
                                1 => $data
                            ],
                            'refresh' => true
                        ];
                        $elasticsearch->bulkQuery($query);
                    }
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
        return 'Data is imported!';
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
