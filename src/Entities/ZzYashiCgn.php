<?php

namespace Script\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZzYashiCgn
 *
 * @ORM\Table(name="zz__yashi_cgn")
 * @ORM\Entity
 */
class ZzYashiCgn
{
    /**
     * @var integer
     *
     * @ORM\Column(name="campaign_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $campaignId;

    /**
     * @var integer
     *
     * @ORM\Column(name="yashi_campaign_id", type="integer", nullable=true)
     */
    private $yashiCampaignId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="yashi_advertiser_id", type="integer", nullable=true)
     */
    private $yashiAdvertiserId;

    /**
     * @var string
     *
     * @ORM\Column(name="advertiser_name", type="string", length=100, nullable=true)
     */
    private $advertiserName;

    /**
     * ZzYashiCgn constructor.
     */
    public function __construct()
    {}

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    /**
     * @param int $campaignId
     */
    public function setCampaignId(int $campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @return int
     */
    public function getYashiCampaignId(): int
    {
        return $this->yashiCampaignId;
    }

    /**
     * @param int $yashiCampaignId
     */
    public function setYashiCampaignId(int $yashiCampaignId)
    {
        $this->yashiCampaignId = $yashiCampaignId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getYashiAdvertiserId(): int
    {
        return $this->yashiAdvertiserId;
    }

    /**
     * @param int $yashiAdvertiserId
     */
    public function setYashiAdvertiserId(int $yashiAdvertiserId)
    {
        $this->yashiAdvertiserId = $yashiAdvertiserId;
    }

    /**
     * @return string
     */
    public function getAdvertiserName(): string
    {
        return $this->advertiserName;
    }

    /**
     * @param string $advertiserName
     */
    public function setAdvertiserName(string $advertiserName)
    {
        $this->advertiserName = $advertiserName;
    }

}

