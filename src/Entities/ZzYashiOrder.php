<?php

namespace Script\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZzYashiOrder
 *
 * @ORM\Table(name="zz__yashi_order", indexes={@ORM\Index(name="fk_zz__yashi_order_campaign_id_idx", columns={"campaign_id"})})
 * @ORM\Entity
 */
class ZzYashiOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="order_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $orderId;

    /**
     * @var integer
     *
     * @ORM\Column(name="yashi_order_id", type="integer", nullable=true)
     */
    private $yashiOrderId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var \Script\Entities\ZzYashiCgn
     *
     * @ORM\ManyToOne(targetEntity="Script\Entities\ZzYashiCgn")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="campaign_id", referencedColumnName="campaign_id")
     * })
     */
    private $campaign;

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getYashiOrderId(): int
    {
        return $this->yashiOrderId;
    }

    /**
     * @param int $yashiOrderId
     */
    public function setYashiOrderId(int $yashiOrderId)
    {
        $this->yashiOrderId = $yashiOrderId;
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

