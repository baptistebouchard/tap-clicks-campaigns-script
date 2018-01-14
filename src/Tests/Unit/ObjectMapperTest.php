<?php

use PHPUnit\Framework\TestCase;
use Script\Utils\ObjectMapper;

class ObjectMapperTest extends TestCase
{

    public function getRow()
    {
        return [
            '2016-05-01',
            1234,
            'advertiserName',
            5433,
            'campaignName',
            'orderId',
            'orderName',
            'creativeId',
            'creativeName',
            'createPreviewUrl',
            'impressions',
            'clicks',
            'view25',
            'view50',
            'view75',
            'view100'
        ];
    }

    public function testGetCampaignFromRow()
    {
        $objectMapper = new ObjectMapper();
        $csvRow = $this->getRow();
        $expected = [
            'date' => \DateTime::createFromFormat('Y-m-d H:i:s.u', '2016-05-01 00:00:00.000000'),
            'advertiserId' => 1234,
            'advertiserName' => 'advertiserName',
            'campaignId' => 5433,
            'campaignName' => 'campaignName',
        ];
        $campaign = $objectMapper->getCampaignFromRow($csvRow);
        $this->assertEquals($expected, $campaign);
    }

    public function testGetOrderFromRow()
    {
        $objectMapper = new ObjectMapper();
        $csvRow = $this->getRow();
        $expected = [
            'date' => \DateTime::createFromFormat('Y-m-d H:i:s.u', '2016-05-01 00:00:00.000000'),
            'orderId' => 'orderId',
            'orderName' => 'orderName',
        ];
        $campaign = $objectMapper->getOrderFromRow($csvRow);
        $this->assertEquals($expected, $campaign);
    }

    public function testGetCreativeFromRow()
    {
        $objectMapper = new ObjectMapper();
        $csvRow = $this->getRow();
        $expected = [
            'date' => \DateTime::createFromFormat('Y-m-d H:i:s.u', '2016-05-01 00:00:00.000000'),
            'creativeId' => 'creativeId',
            'creativeName' => 'creativeName',
            'createPreviewUrl' => 'createPreviewUrl',
            'impressions' => 'impressions',
            'clicks' => 'clicks',
            'view25' => 'view25',
            'view50' => 'view50',
            'view75' => 'view75',
            'view100' => 'view100'
        ];
        $campaign = $objectMapper->getCreativeFromRow($csvRow);
        $this->assertEquals($expected, $campaign);
    }

    public function getCampaign($id)
    {
        return [
            'date' => random_int(100000, 1000000),
            'advertiserId' => 1234,
            'advertiserName' => 'advertiserName',
            'campaignId' => $id,
            'campaignName' => 'campaignName',
        ];
    }

    public function getOrder($id)
    {
        return [
            'date' => random_int(100000, 1000000),
            'orderId' => $id,
            'orderName' => 'orderName',
        ];
    }

    public function getCreative($id)
    {
        return [
            'date' => random_int(100000, 1000000),
            'creativeId' => $id,
            'creativeName' => 'creativeName',
            'createPreviewUrl' => 'createPreviewUrl',
            'impressions' => random_int(1, 100),
            'clicks' => random_int(1, 100),
            'view25' => random_int(1, 100),
            'view50' => random_int(1, 100),
            'view75' => random_int(1, 100),
            'view100' => random_int(1, 100),
        ];
    }


    public function getDataHierarchyTestCases()
    {
        $campaign1 = $this->getCampaign(1);
        $campaign2 = $this->getCampaign(2);
        $order1 = $this->getOrder(1);
        $order2 = $this->getOrder(2);
        $creative1 = $this->getCreative(1);
        $creative2 = $this->getCreative(2);

        return [
            [
                $campaign1,
                $order1,
                $creative1,
                [],
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ],

                        ]
                    ]
                ]
            ],
            [
                $campaign1,
                $order1,
                $creative2,
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ],

                        ]
                    ]
                ],
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1, $creative2]
                            ],

                        ]
                    ]
                ]
            ],
            [
                $campaign1,
                $order2,
                $creative2,
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ]
                        ]
                    ]
                ],
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ],
                            $order2['orderId'] => [
                                'data' => $order2,
                                'creatives' => [$creative2]
                            ],
                        ]
                    ]
                ]
            ],
            [
                $campaign2,
                $order2,
                $creative2,
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ]
                        ]
                    ]
                ],
                [
                    $campaign1['campaignId'] => [
                        'data' => $campaign1,
                        'orders' => [
                            $order1['orderId'] => [
                                'data' => $order1,
                                'creatives' => [$creative1]
                            ]
                        ]
                    ],
                    $campaign2['campaignId'] => [
                        'data' => $campaign2,
                        'orders' => [
                            $order2['orderId'] => [
                                'data' => $order2,
                                'creatives' => [$creative2]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @param $campaign
     * @param $order
     * @param $creative
     * @param $actualData
     * @param $expectedData
     * @dataProvider getDataHierarchyTestCases
     */
    public function testSetDataHierarchy($campaign, $order, $creative, $actualData, $expectedData)
    {
        $objectMapper = new ObjectMapper();
        $data = $objectMapper->setDataHierarchy($actualData, $campaign, $order, $creative);
        $this->assertEquals($expectedData, $data);
    }
}