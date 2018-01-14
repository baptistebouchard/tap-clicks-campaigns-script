<?php

namespace Script\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZzYashiCgnData
 *
 * @ORM\Table(name="zz__yashi_cgn_data", uniqueConstraints={@ORM\UniqueConstraint(name="campaign_id_UNIQUE", columns={"campaign_id", "log_date"})}, indexes={@ORM\Index(name="fk_zz__yashi_cgn_data_campaign_id_idx", columns={"campaign_id"})})
 * @ORM\Entity
 */
class ZzYashiCgnData
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_date", type="integer", nullable=true)
     */
    private $logDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="impression_count", type="integer", nullable=true)
     */
    private $impressionCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="click_count", type="integer", nullable=true)
     */
    private $clickCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="25viewed_count", type="integer", nullable=true)
     */
    private $viewedCount_25;

    /**
     * @var integer
     *
     * @ORM\Column(name="50viewed_count", type="integer", nullable=true)
     */
    private $viewedCount_50;

    /**
     * @var integer
     *
     * @ORM\Column(name="75viewed_count", type="integer", nullable=true)
     */
    private $viewedCount_75;

    /**
     * @var integer
     *
     * @ORM\Column(name="100viewed_count", type="integer", nullable=true)
     */
    private $viewedCount_100;

    /**
     * @var \Script\Entities\ZzYashiCgn
     *
     * @ORM\ManyToOne(targetEntity="\Script\Entities\ZzYashiCgn")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     * })
     */
    private $campaign;

    /**
     * ZzYashiCgnData constructor.
     * @param int $logDate
     * @param ZzYashiCgn $campaign
     */
    public function __construct(
        int $logDate,
        ZzYashiCgn $campaign
    ) {
        $this->logDate = $logDate;
        $this->campaign = $campaign;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getLogDate(): int
    {
        return $this->logDate;
    }

    /**
     * @param int $logDate
     */
    public function setLogDate(int $logDate)
    {
        $this->logDate = $logDate;
    }

    /**
     * @return int
     */
    public function getImpressionCount(): int
    {
        return $this->impressionCount;
    }

    /**
     * @param int $impressionCount
     */
    public function setImpressionCount(int $impressionCount)
    {
        $this->impressionCount = $impressionCount;
    }

    /**
     * @return int
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * @param int $clickCount
     */
    public function setClickCount(int $clickCount)
    {
        $this->clickCount = $clickCount;
    }

    /**
     * @return int
     */
    public function getViewedCount25(): int
    {
        return $this->viewedCount_25;
    }

    /**
     * @param int $viewedCount_25
     */
    public function setViewedCount25(int $viewedCount_25)
    {
        $this->viewedCount_25 = $viewedCount_25;
    }

    /**
     * @return int
     */
    public function getViewedCount50(): int
    {
        return $this->viewedCount_50;
    }

    /**
     * @param int $viewedCount_50
     */
    public function setViewedCount50(int $viewedCount_50)
    {
        $this->viewedCount_50 = $viewedCount_50;
    }

    /**
     * @return int
     */
    public function getViewedCount75(): int
    {
        return $this->viewedCount_75;
    }

    /**
     * @param int $viewedCount_75
     */
    public function setViewedCount75(int $viewedCount_75)
    {
        $this->viewedCount_75 = $viewedCount_75;
    }

    /**
     * @return int
     */
    public function getViewedCount100(): int
    {
        return $this->viewedCount_100;
    }

    /**
     * @param int $viewedCount_100
     */
    public function setViewedCount100(int $viewedCount_100)
    {
        $this->viewedCount_100 = $viewedCount_100;
    }

    /**
     * @return ZzYashiCgn
     */
    public function getCampaign(): ZzYashiCgn
    {
        return $this->campaign;
    }

    /**
     * @param ZzYashiCgn $campaign
     */
    public function setCampaign(ZzYashiCgn $campaign)
    {
        $this->campaign = $campaign;
    }


}

