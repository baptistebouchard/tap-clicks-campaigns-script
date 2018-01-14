<?php

namespace Script\Utils;


use Doctrine\ORM\EntityManager;
use Script\Entities\ZzYashiCgn;
use Script\Entities\ZzYashiCgnData;
use Script\Entities\ZzYashiOrder;
use Script\Entities\ZzYashiOrderData;
use Script\Entities\ZzYashiCreative;
use Script\Entities\ZzYashiCreativeData;

/**
 * This class, generates and Saves campaign, order and creatives
 * Class DataManager
 * @package Script\Utils
 */
class DataManager
{
    private $entityManager;

    /**
     * DataManager constructor.
     * @param EntityManager $entityManager
     * @param DataUtils $utils
     */
    public function __construct(EntityManager $entityManager, DataUtils $utils)
    {
        $this->entityManager = $entityManager;
        $this->utils = $utils;
    }

    /**
     * @param $campaign
     * @return ZzYashiCgn|null|object
     */
    public function saveCampaign($campaign)
    {
        $campaignEntity = $this->entityManager->getRepository(ZzYashiCgn::class)->findOneBy([
            'yashiCampaignId' => $campaign['campaignId']
        ]);

        if (empty($campaignEntity)) {
            $campaignEntity = new ZzYashiCgn();
        }

        $campaignEntity->setName($campaign['campaignName']);
        $campaignEntity->setYashiCampaignId($campaign['campaignId']);
        $campaignEntity->setAdvertiserName($campaign['advertiserName']);
        $campaignEntity->setYashiAdvertiserId($campaign['advertiserId']);

        $this->entityManager->persist($campaignEntity);
        $this->persistCampaignData($campaign, $campaignEntity);
        $this->entityManager->flush();

        return $campaignEntity;
    }

    /**
     * @param $campaign
     * @param $campaignEntity
     * @return ZzYashiCgnData|null|object
     */
    public function persistCampaignData($campaign, $campaignEntity)
    {
        $campaignDataEntity = $this->entityManager->getRepository(ZzYashiCgnData::class)->findOneBy([
            'campaign' => $campaignEntity,
            'logDate' => $campaign['date']->getTimestamp(),
        ]);

        if (empty($campaignDataEntity)) {
            $campaignDataEntity = new ZzYashiCgnData($campaign['date']->getTimestamp(), $campaignEntity);
            $this->entityManager->persist($campaignDataEntity);
        }
    }

    /**
     * @param $orderData
     * @param $campaign
     * @return null|object|ZzYashiOrder
     */
    public function saveOrder($orderData, $campaign)
    {
        $orderEntity = $this->entityManager->getRepository(ZzYashiOrder::class)->findOneBy([
            'yashiOrderId' => $orderData['orderId']
        ]);
        if (empty($orderEntity)) {
            $orderEntity = new ZzYashiOrder();
        }
        $orderEntity->setCampaign($campaign);
        $orderEntity->setName($orderData['orderName']);
        $orderEntity->setYashiOrderId($orderData['orderId']);


        $this->entityManager->persist($orderEntity);
        $this->persistOrderData($orderData, $orderEntity);
        $this->entityManager->flush();
        return $orderEntity;
    }

    /**
     * @param $orderData
     * @param $orderEntity
     */
    public function persistOrderData($orderData, $orderEntity)
    {
        $orderDataEntity = $this->entityManager->getRepository(ZzYashiOrderData::class)->findOneBy([
            'order' => $orderEntity,
            'logDate' => $orderData['date']->getTimestamp(),
        ]);
        if (empty($orderDataEntity)) {
            $orderDataEntity = new ZzYashiOrderData($orderData['date']->getTimestamp(), $orderEntity);
            $this->entityManager->persist($orderDataEntity);
        }
    }


    /**
     * @param $creativeData
     * @param $order
     * @return null|object|ZzYashiCreative
     */
    public function saveCreative($creativeData, $order)
    {
        $creativeEntity = $this->entityManager->getRepository(ZzYashiCreative::class)->findOneBy([
            'yashiCreativeId' => $creativeData['creativeId']
        ]);
        if (empty($creativeEntity)) {
            $creativeEntity = new ZzYashiCreative();
        }
        $creativeEntity->setName($creativeData['creativeName']);
        $creativeEntity->setYashiCreativeId($creativeData['creativeId']);
        $creativeEntity->setOrder($order);
        $creativeEntity->setPreviewUrl($creativeData['createPreviewUrl']);


        $this->entityManager->persist($creativeEntity);
        $this->persistCreativeData($creativeData, $creativeEntity);
        $this->entityManager->flush();
        return $creativeEntity;
    }

    /**
     * @param $creativeData
     * @param $creativeEntity
     */
    public function persistCreativeData($creativeData, $creativeEntity)
    {
        $creativeDataEntity = $this->entityManager->getRepository(ZzYashiCreativeData::class)->findOneBy([
            'creative' => $creativeEntity,
            'logDate' => $creativeData['date']->getTimestamp(),
        ]);
        if (empty($creativeDataEntity)) {
            $creativeDataEntity = new ZzYashiCreativeData($creativeData['date']->getTimestamp(), $creativeEntity);
        }

        $creativeDataEntity = $this->utils->setEntityCounts($creativeData, $creativeDataEntity);
        $this->entityManager->persist($creativeDataEntity);
    }
}
