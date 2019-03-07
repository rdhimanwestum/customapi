<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 3/6/19
 * Time: 11:08 AM
 */

namespace Westum\CustApi\Cron;

use \Psr\Log\LoggerInterface;


class Order
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
        $this->logger->info('Order Cron Works');
        echo 'Order';
    }
}