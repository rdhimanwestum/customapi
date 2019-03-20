<?php
/**
 * Created by PhpStorm.
 * User: dstoyanov
 * Date: 19.03.19
 * Time: 16:19
 */
namespace Westum\CustApi\Model\Data;

use Westum\CustApi\Api\Data\ImportInterface;

class Import extends \Magento\Framework\Api\AbstractExtensibleObject implements ImportInterface
{
    public function getEntityId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    public function setEntityId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    public function getUserId()
    {
        return $this->_get(self::USER_ID);
    }

    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }


}