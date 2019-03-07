<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 3/6/19
 * Time: 11:08 AM
 */

namespace Westum\CustApi\Cron;

use \Psr\Log\LoggerInterface;


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

    protected $logger;

    public function __construct(
        LoggerInterface $logger,
        \Magento\AdvancedSearch\Model\Client\ClientResolver $clientResolver,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Elasticsearch\Model\Adapter\Elasticsearch $adapterElasticsearch
    )
    {
        $this->logger = $logger;
        $this->clientResolver = $clientResolver;
        $this->scopeConfig = $scopeConfig;
        $this->adapterElasticsearch = $adapterElasticsearch;
    }

    public function execute() {
        $this->logger->info('Customer Cron Works');
        echo 'Customer';
    }
}