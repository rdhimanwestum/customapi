<?php

namespace Westum\CustApi\Api\Data;

interface ImportInterface
{
    const ENTITY_ID = 'entity_id';
    const USER_ID = 'user_id';

    public function getEntityId();

    public function setEntityId($id);

    public function getUserId();

    public function setUserId($userId);

}