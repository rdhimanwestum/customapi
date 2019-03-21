<?php

namespace Westum\CustApi\Model\ResourceModel\Elasticsearch;


class Import extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('westum_custapi_elasticsearch_import', 'entity_id');
    }
}