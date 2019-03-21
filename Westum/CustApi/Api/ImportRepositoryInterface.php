<?php
/**
 * Created by PhpStorm.
 * User: dstoyanov
 * Date: 19.03.19
 * Time: 16:01
 */
namespace Westum\CustApi\Api;

interface ImportRepositoryInterface
{

    public function save(\Westum\CustApi\Api\Data\ImportInterface $import);

    //public function get($userId, $websiteId = null);

    public function getById($entityId);

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    public function delete(\Westum\CustApi\Api\Data\ImportInterface $import);

    public function deleteById($entityId);
}