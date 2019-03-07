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
use Magento\Store\Model\StoreManagerInterface;
use Faker\Factory;

class ImportFakeData extends Command
{
    const FILENAME = 'filename';
    const USER = 'user';
    const PASSWORD = 'password';

    // Initialize Faker Library
    public function __construct(Filesystem $filesystem, DirectoryList $dir, StoreManagerInterface $storeManager){
        $this->storeurl = $storeManager->getStore()->getBaseUrl();
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
                self::USER,
                null,
                InputOption::VALUE_REQUIRED,
                'User'
            ),
            new InputOption(
                self::PASSWORD,
                null,
                InputOption::VALUE_REQUIRED,
                'Password'
            )
        ];
        $this->setName('fakedata:import');
        $this->setDescription('Import Fake Data');
        $this->setDefinition($options);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption(self::FILENAME) and $input->getOption(self::USER) and $input->getOption(self::PASSWORD)) { //Get Input Parameters
            try {
                $userData = array("username" => $input->getOption(self::USER), "password" => $input->getOption(self::PASSWORD));
                $ch = curl_init($this->storeurl."rest/V1/integration/admin/token");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

                $token = curl_exec($ch);
                $fake_data = $this->jsonDir->readFile($input->getOption(self::FILENAME));

                $ch = curl_init($this->storeurl."rest/V1/westum-custapi/post/?param=something");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fake_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

                $result = curl_exec($ch);

                $output->writeln($result);

            }catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

        } else {
            throw new \Exception('Required Parameters are missing. Example: php bin/magento fakedata:import --user=admin --password=admin123 --filename=\'ttt.json\'');
        }
    }
}