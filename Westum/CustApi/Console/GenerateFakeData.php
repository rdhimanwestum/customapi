<?php
/**
 * Created by PhpStorm.
 * User: rohit
 * Date: 2/27/19
 * Time: 2:31 PM
 */
namespace Westum\CustApi\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Faker\Factory;

class GenerateFakeData extends Command
{
    const Counter = 100;

    // Initialize Faker Library
    public function __construct(){
        $this->faker = Factory::create();
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('fakedata:generatefakedata');
        $this->setDescription('Generate Fake Data');

        parent::configure();
    }
    // Generate products Data
    public function getProducts(){
        for ($i=0; $i < rand(1,10); $i++) {
            $order_items[]= array(
                'product_name' => $this->faker->catchPhrase,
                'product_quantity' => $this->faker->numberBetween(1,10),
                'product_brand' => $this->faker->company,
            );
        }
        return $order_items;
    }

    // Generate Customers Data
    public function getCustomers(){
        for ($i=0; $i < self::Counter; $i++) {
            $customers = array('customer_name'=> $this->faker->name,
                'customer_gender'=> $this->faker->numberBetween(0,1),
                'customer_email'=> $this->faker->email,);
        }
        return $customers;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        for ($i=0; $i < self::Counter; $i++) {
            $getCompleteData[] = array(
                'store_address'=> $this->faker->streetAddress,
                'store_geolocation'=> $this->faker->latitude.','.$this->faker->longitude,
                'store_owner_name'=> $this->faker->name,
                'store_city'=> $this->faker->city,
                'store_country'=> $this->faker->country,
                'store_email'=> $this->faker->email,
                'order_id'=> $this->faker->numberBetween(1000000,20000000),
                'order_amount'=> round($this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),2),
                'customer' => $this->getCustomers(),
                'order_items' => $this->getProducts()
            );
        }

        $output->writeln(json_encode($getCompleteData,JSON_PRETTY_PRINT));
    }
}