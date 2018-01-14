<?php

namespace Script\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZzYashiCreative
 *
 * @ORM\Table(name="zz__yashi_creative", indexes={@ORM\Index(name="fk_zz__yashi_creative_order_id_idx", columns={"order_id"})})
 * @ORM\Entity
 */
class ZzYashiCreative
{
    /**
     * @var integer
     *
     * @ORM\Column(name="creative_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $creativeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="yashi_creative_id", type="integer", nullable=true)
     */
    private $yashiCreativeId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_url", type="string", length=255, nullable=true)
     */
    private $previewUrl;

    /**
     * @var \Script\Entities\ZzYashiOrder
     *
     * @ORM\ManyToOne(targetEntity="Script\Entities\ZzYashiOrder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="order_id")
     * })
     */
    private $order;

    /**
     * @return int
     */
    public function getCreativeId(): int
    {
        return $this->creativeId;
    }

    /**
     * @param int $creativeId
     */
    public function setCreativeId(int $creativeId)
    {
        $this->creativeId = $creativeId;
    }

    /**
     * @return int
     */
    public function getYashiCreativeId(): int
    {
        return $this->yashiCreativeId;
    }

    /**
     * @param int $yashiCreativeId
     */
    public function setYashiCreativeId(int $yashiCreativeId)
    {
        $this->yashiCreativeId = $yashiCreativeId;
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
     * @return string
     */
    public function getPreviewUrl(): string
    {
        return $this->previewUrl;
    }

    /**
     * @param string $previewUrl
     */
    public function setPreviewUrl(string $previewUrl)
    {
        $this->previewUrl = $previewUrl;
    }

    /**
     * @return ZzYashiOrder
     */
    public function getOrder(): ZzYashiOrder
    {
        return $this->order;
    }

    /**
     * @param ZzYashiOrder $order
     */
    public function setOrder(ZzYashiOrder $order)
    {
        $this->order = $order;
    }


}

