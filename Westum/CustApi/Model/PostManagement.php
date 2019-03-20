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


    /**
     * @var \Westum\CustApi\Api\ImportRepositoryInterface
     */
    protected $apiImportRepositoryInterface;

    /**
     * @var \Westum\CustApi\Api\Data\ImportInterface
     */
    protected $apiDataImportInterface;

    /**
     * @var \Magento\Integration\Model\Oauth\Token
     */
    protected $modelOauthToken;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Westum\CustApi\Api\ImportRepositoryInterface $apiImportRepositoryInterface,
        \Westum\CustApi\Api\Data\ImportInterface $apiDataImportInterface,
        \Magento\Integration\Model\Oauth\Token $modelOauthToken
    )
    {
        $this->request = $request;
        $this->clientResolver = $clientResolver;
        $this->scopeConfig = $scopeConfig;
        $this->apiImportRepositoryInterface = $apiImportRepositoryInterface;
        $this->apiDataImportInterface = $apiDataImportInterface;
        $this->modelOauthToken = $modelOauthToken;
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
                    /* save auth admin id and import date in a db table */
                    $authTokenData = $this->modelOauthToken->loadByToken($this->getAuthToken());
                    if($authTokenData && $authTokenData['admin_id']) {
                        $this->apiDataImportInterface->setUserId($authTokenData['admin_id']);
                        $this->apiImportRepositoryInterface->save($this->apiDataImportInterface);
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

    /**
     * Get Authorization Bearer Key
     * @return string
     */
    public function getAuthToken()
    {
        return trim(str_replace(self::OAUTH_STRING, '', $this->request->getHeader('Authorization')));
    }
}
