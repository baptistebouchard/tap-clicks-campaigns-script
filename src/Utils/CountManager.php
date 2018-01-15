<?php

namespace Script\Utils;

use Doctrine\ORM\EntityManager;
use Script\Entities\ZzYashiCgn;
use Script\Entities\ZzYashiCgnData;
use Script\Entities\ZzYashiOrder;
use Script\Entities\ZzYashiOrderData;
use Script\Entities\ZzYashiCreativeData;

/**
 * This class computes and saves counts for Campaigns and Orders
 * Class CountManager
 * @package Script\Utils
 */
class CountManager
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
     * @param ZzYashiOrder $createdOrder
     * @param \DateTime $logDate
     */
    public function updateOrderCounts (ZzYashiOrder $createdOrder, \DateTime $logDate) : void
    {
        $counts = $this->getOrderCounts($createdOrder, $logDate);

        $orderDataEntity = $this->entityManager->getRepository(ZzYashiOrderData::class)->findOneBy([
            'order' => $createdOrder,
            'logDate' => $logDate->getTimestamp(),
        ]);

        $orderDataEntity = $this->utils->setEntityCounts($counts, $orderDataEntity);

        $this->entityManager->persist($orderDataEntity);
        $this->entityManager->flush();
    }

    /**
     * @param ZzYashiCgn $createdCampaign
     * @param \DateTime $logDate
     */
    public function updateCampaignCounts(ZzYashiCgn $createdCampaign, \DateTime $logDate) : void
    {
        $counts = $this->getCampaignCounts($createdCampaign, $logDate);

        $campaignDataEntity = $this->entityManager->getRepository(ZzYashiCgnData::class)->findOneBy([
            'campaign' => $createdCampaign,
            'logDate' => $logDate->getTimestamp(),
        ]);

        $campaignDataEntity = $this->utils->setEntityCounts($counts, $campaignDataEntity);

        $this->entityManager->persist($campaignDataEntity);
        $this->entityManager->flush();
    }

    /**
     *
     * In order To Make sure that no data has been added or suppressed,
     * We're doing the order counts sums through the DB
     *
     * @param $createdOrder
     * @param $logDate
     * @return mixed
     */
    public function getOrderCounts(ZzYashiOrder $createdOrder, \DateTime $logDate)
    {
        $qb = $this->entityManager->getRepository(ZzYashiCreativeData::class)->createQueryBuilder('cd');

        $qb->select(
            'SUM(cd.viewedCount_25) as view25',
            'SUM(cd.viewedCount_50) as view50',
            'SUM(cd.viewedCount_75) as view75',
            'SUM(cd.viewedCount_100) as view100',
            'SUM(cd.clickCount) as clicks',
            'SUM(cd.impressionCount) as impressions'
        )
            ->leftJoin('cd.creative', 'c')
            ->leftJoin('c.order', 'o')
            ->where('o.orderId = :order_id AND cd.logDate = :log_date')
            ->setParameter('order_id', $createdOrder->getOrderId())
            ->setParameter('log_date', $logDate->getTimestamp())
            ->getQuery();

        return $qb->getQuery()->getResult()[0];
    }

    /**
     * In order To Make sure that no data has been added or suppressed,
     * We're doing the  campaign counts sums through the DB.
     *
     * @param $createdCampaign
     * @param $logDate
     * @return mixed
     */
    public function getCampaignCounts(ZzYashiCgn $createdCampaign, \DateTime $logDate)
    {
        $qb = $this->entityManager->getRepository(ZzYashiOrderData::class)->createQueryBuilder('od');

        $qb->select(
            'SUM(od.viewedCount_25) as view25',
            'SUM(od.viewedCount_50) as view50',
            'SUM(od.viewedCount_75) as view75',
            'SUM(od.viewedCount_100) as view100',
            'SUM(od.clickCount) as clicks',
            'SUM(od.impressionCount) as impressions'
        )
            ->leftJoin('od.order', 'o')
            ->leftJoin('o.campaign', 'c')
            ->where('c.campaignId = :campaign_id AND od.logDate = :log_date')
            ->setParameter('campaign_id', $createdCampaign->getCampaignid())
            ->setParameter('log_date', $logDate->getTimestamp())
            ->getQuery();

        return $qb->getQuery()->getResult()[0];
    }

}