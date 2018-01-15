<?php

namespace Script;

use FtpClient\FtpClient;
use Monolog\Logger;
use Script\Entities\ZzYashiCgn;
use Script\Entities\ZzYashiOrder;
use Script\Utils\CountManager;
use Script\Utils\DataManager;
use Script\Utils\ObjectMapper;
use Script\Utils\ServiceUtils;
use TypeError;

class ImportScriptCommand
{

    const BASE_FTP_FOLDER = 'yashi';
    const TEMP_STREAM = 'php://temp';

    private $dataManager;
    private $countManager;
    private $ftpClient;
    private $mapper;
    private $utils;
    private $fileLogger;
    private $stdOutLogger;

    /**
     * ImportScriptCommand constructor.
     * @param DataManager $dataManager
     * @param CountManager $countManager
     * @param FtpClient $ftpClient
     * @param ObjectMapper $mapper
     * @param ServiceUtils $utils
     * @param Logger $fileLogger
     * @param Logger $stdOutLogger
     */
    public function __construct(
        DataManager $dataManager,
        CountManager $countManager,
        FtpClient $ftpClient,
        ObjectMapper $mapper,
        ServiceUtils $utils,
        Logger $fileLogger,
        Logger $stdOutLogger
    ) {
        $this->dataManager = $dataManager;
        $this->countManager = $countManager;
        $this->ftpClient = $ftpClient;
        $this->mapper = $mapper;
        $this->utils = $utils;
        $this->fileLogger = $fileLogger;
        $this->stdOutLogger = $stdOutLogger;
    }

    /**
     * Runs the script logic
     */
    public function run() : void
    {
        $validFileList = $this->getValidFileList();

        foreach ($validFileList as $fileName) {
            try {
                $data = $this->mapCsvFileContent($this::BASE_FTP_FOLDER . '/' . $fileName);
                $this->generateCampaignData($data);
            } catch (\Exception $exception) {
                $this->logFileImportIssue($exception, $fileName);
            } catch(TypeError $error) {
                $this->logFileImportIssue($error, $fileName);
            }
        }
    }

    /**
     * @param $stack
     * @param $fileName
     */
    private function logFileImportIssue($stack, $fileName) : void
    {
        $this->fileLogger->error($stack->getMessage());
        $this->stdOutLogger->error('Failed to import file with fileName: ' . $fileName . "\n");
    }

    /**
     * Returns mapped data from csv file.
     * Filters by Valid Advertiser.
     * @param $path
     * @return array
     */
    private function mapCsvFileContent(string $path) : array
    {
        $data = [];

        $tempHandle = fopen($this::TEMP_STREAM, 'r+');

        if ($this->ftpClient->fget($tempHandle, $path, FTP_ASCII)) {
            rewind($tempHandle);
            // for each row we map a campaign an order and a creative if the advertiser is valid.
            // we then push these objects in a data array'

            while ($csvRow = fgetcsv($tempHandle)) {
                $campaign = $this->mapper->getCampaignFromRow($csvRow);

                if ($this->utils->isListedAdvertiser($campaign)) {
                    $order = $this->mapper->getOrderFromRow($csvRow);
                    $creative = $this->mapper->getCreativeFromRow($csvRow);

                    $data = $this->mapper->setDataHierarchy($data, $campaign, $order, $creative);
                }
            }
        }
        fclose($tempHandle);

        return $data;
    }

    /**
     * returns all valid files.
     * @return array
     */
    private function getValidFileList() : array
    {
        $fileNameList = $this->ftpClient->nlist($this::BASE_FTP_FOLDER);
        return $this->utils->getValidFileNames($fileNameList);
    }

    /**
     * Generates the campaign and cascade to orders
     * Then Updates the campaigns counts
     * @param $groupedData
     */
    private function generateCampaignData(array $groupedData) : void
    {
        forEach ($groupedData as $campaignData) {
            $this->stdOutLogger->info("Saving campaign with yashi_campaign_id: {$campaignData['data']['campaignId']}");

            $createdCampaign = $this->dataManager->saveCampaign($campaignData['data']);
            $this->generateOrderData($campaignData, $createdCampaign);
            $this->countManager->updateCampaignCounts($createdCampaign, $campaignData['data']['date']);
        }
    }

    /**
     * Generates the orders and cascade to creatives
     * Then Updates the campaign orders counts
     * @param array $campaignData
     * @param ZzYashiCgn $createdCampaign
     */
    private function generateOrderData(array $campaignData, ZzYashiCgn $createdCampaign) : void
    {
        forEach ($campaignData['orders'] as $orderData) {
            $this->stdOutLogger->info("Saving order with yashi_order_id: {$orderData['data']['orderId']}");

            $createdOrder = $this->dataManager->saveOrder($orderData['data'], $createdCampaign);
            $this->generateCreativeData($orderData, $createdOrder);
            $this->countManager->updateOrderCounts($createdOrder, $orderData['data']['date']);
        }
    }

    /**
     *
     * Generates the creatives
     * @param array $orderData
     * @param ZzYashiOrder $createdOrder
     */
    private function generateCreativeData(array $orderData, ZzYashiOrder $createdOrder) : void
    {
        forEach ($orderData['creatives'] as $creativeData) {
            $this->stdOutLogger->info("Saving creative with yashi_creative_id: {$creativeData['creativeId']}");

            $this->dataManager->saveCreative($creativeData, $createdOrder);
        }
    }

}