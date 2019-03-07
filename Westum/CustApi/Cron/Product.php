<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 3/6/19
 * Time: 11:05 AM
 */

namespace Westum\CustApi\Cron;
use \Psr\Log\LoggerInterface;


class Product
{

    protected $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * Write to system.log
     *
     * @return void
     */

    public function execute() {
        $this->logger->info('Product Cron Works');
        echo 'Product';
    }
}
