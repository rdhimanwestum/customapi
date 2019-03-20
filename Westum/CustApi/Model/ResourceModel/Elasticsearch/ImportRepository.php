<?php
/**
 * Created by PhpStorm.
 * User: dstoyanov
 * Date: 19.03.19
 * Time: 15:58
 */
namespace Westum\CustApi\Model\ResourceModel\Elasticsearch;

use Westum\CustApi\Api\ImportRepositoryInterface;
use Westum\CustApi\Model\Elasticsearch\ImportFactory;

class ImportRepository implements ImportRepositoryInterface
{
    /**
     * @var Import
     */
    protected $modelResourceModelElasticsearchImport;

    /**
     * @var ImportFactory
     */
    protected $modelElasticsearchImportFactory;

    public function __construct(
        Import $import,
        ImportFactory $importFactory
    ) {
        $this->modelResourceModelElasticsearchImport = $import;
        $this->modelElasticsearchImportFactory = $importFactory;
    }

    public function save(\Westum\CustApi\Api\Data\ImportInterface $import)
    {
        $modelElasticsearchImport = $this->modelElasticsearchImportFactory->create();
        $modelElasticsearchImport->setData('user_id', $import->getUserId());
        $this->getResource()->save($modelElasticsearchImport);
    }

    public function getById($entityId)
    {
        // TODO: Implement getById() method.
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList() method.
    }

    public function delete(\Westum\CustApi\Api\Data\ImportInterface $import)
    {
        // TODO: Implement delete() method.
    }

    public function deleteById($entityId)
    {
        // TODO: Implement deleteById() method.
    }

    public function getResource()
    {
        return $this->modelResourceModelElasticsearchImport;
    }
}