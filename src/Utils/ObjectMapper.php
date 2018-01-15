<?php

namespace Script\Utils;

/**
 * This class loads raw data from csv
 * It Generates Campaign, Order and Creatives Array maps.
 * Class ObjectMapper
 * @package Script\Utils
 */
class ObjectMapper
{

    /**
     * Maps campaign data from csv row.
     * @param array $csvRow
     * @return array
     */
    public function getCampaignFromRow(array $csvRow) : array
    {
        return [
            'date' => $this->createDateTimeFromString($csvRow[0]),
            'advertiserId' => $csvRow[1],
            'advertiserName' => $csvRow[2],
            'campaignId' => $csvRow[3],
            'campaignName' => $csvRow[4],
        ];
    }

    /**
     * Maps order data from csv row.
     * @param array $csvRow
     * @return array
     */
    public function getOrderFromRow(array $csvRow) : array
    {
        return [
            'date' => $this->createDateTimeFromString($csvRow[0]),
            'orderId' => $csvRow[5],
            'orderName' => $csvRow[6]
        ];
    }

    /**
     * Maps creative data from csv row.
     * @param array $csvRow
     * @return array
     */
    public function getCreativeFromRow(array $csvRow) : array
    {
        return [
            'date' => $this->createDateTimeFromString($csvRow[0]),
            'creativeId' => $csvRow[7],
            'creativeName' => $csvRow[8],
            'createPreviewUrl' => $csvRow[9],
            'impressions' => $csvRow[10],
            'clicks' => $csvRow[11],
            'view25' => $csvRow[12],
            'view50' => $csvRow[13],
            'view75' => $csvRow[14],
            'view100' => $csvRow[15]
        ];
    }

    /**
     * Creates Date set to 0 hours To Be able to use the date as key.
     * @param string $dateString
     * @return \DateTime | bool
     */
    private function createDateTimeFromString(string $dateString)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s.u', $dateString . ' 00:00:00.000000');
    }

    /**
     * Group Creative Data by Campaign and Order and return the group data array.
     * @param array $data
     * @param array $campaign
     * @param array $order
     * @param array $creative
     * @return array
     */
    public function setDataHierarchy(array $data, array $campaign, array $order, array $creative) : array
    {
        // using the unique keys campaignId and oderId
        // we map the data to avoid duplicating the campaigns and orders.
        $data[$campaign['campaignId']]['data'] = $campaign;
        $data[$campaign['campaignId']]['orders'][$order['orderId']]['data'] = $order;
        $data[$campaign['campaignId']]['orders'][$order['orderId']]['creatives'][] = $creative;
        return $data;
    }
}