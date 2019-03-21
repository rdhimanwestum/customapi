<?php

namespace Westum\CustApi\Model\Elasticsearch;

use Magento\Framework\Model\AbstractModel;

class Import extends AbstractModel
{
    public function _construct()
    {
        $this->_init(\Westum\CustApi\Model\ResourceModel\Elasticsearch\Import::class);
    }
}