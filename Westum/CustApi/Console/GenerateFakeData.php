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
use Symfony\Component\Console\Input\InputOption;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Faker\Factory;

class GenerateFakeData extends Command
{
    const FILENAME = 'filename';
    const RECORDS = 'records';

    // Initialize Faker Library
    public function __construct(Filesystem $filesystem, DirectoryList $dir){
        $this->faker = Factory::create();
        $this->filesystem = $filesystem;
        $this->dir = $dir;
        $this->jsonDir=$filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        parent::__construct();
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::FILENAME,
                null,
                InputOption::VALUE_REQUIRED,
                'Filename'
            ),
            new InputOption(
                self::RECORDS,
                null,
                InputOption::VALUE_REQUIRED,
                'Records'
            )
        ];
        $this->setName('fakedata:generate');
        $this->setDescription('Generate Fake Data');
        $this->setDefinition($options);

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
    public function getCustomers($count){
        for ($i=0; $i < $count; $i++) {
            $customers = array('customer_name'=> $this->faker->name,
                'customer_gender'=> $this->faker->numberBetween(0,1),
                'customer_email'=> $this->faker->email,);
        }
        return $customers;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption(self::FILENAME) && $input->getOption(self::RECORDS)) { //Get Input Parameters

            for ($i=0; $i < $input->getOption(self::RECORDS); $i++) {
                $getCompleteData[] = array(
                    'store_address'=> $this->faker->streetAddress,
                    'store_geolocation'=> $this->faker->latitude.','.$this->faker->longitude,
                    'store_owner_name'=> $this->faker->name,
                    'store_city'=> $this->faker->city,
                    'store_country'=> $this->faker->country,
                    'store_email'=> $this->faker->email,
                    'order_id'=> $this->faker->numberBetween(1000000,20000000),
                    'order_amount'=> round($this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000),2),
                    'customer' => $this->getCustomers($input->getOption(self::RECORDS)),
                    'order_items' => $this->getProducts()
                );
            }

            try {
                $this->jsonDir->writeFile($input->getOption(self::FILENAME), json_encode($getCompleteData, JSON_PRETTY_PRINT)); //Save Data
                $output->writeln('Data generated and saved Successfully.');
            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }


        } else {
            throw new \Exception('Required Parameters are missing. Example: fakedata:generate --filename = \'fakedata.json\' --records = 500');
        }
    }
}