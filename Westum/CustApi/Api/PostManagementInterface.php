<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 2/26/19
 * Time: 10:09 AM
 */

namespace Westum\CustApi\Api;


interface PostManagementInterface {


    /**
     * GET for Post api
     * @param string $param
     * @return string
     */

    public function getPost($param);
}