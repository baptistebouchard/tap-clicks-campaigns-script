<?php
/**
 * Created by PhpStorm.
 * User: baptistebouchard
 * Date: 18-01-14
 * Time: 13:33
 */

namespace Script\Utils;

/**
 * Shared utils functions for Data Manager and Count Manager
 * Class DataUtils
 * @package Script\Utils
 */
class DataUtils
{
    /**
     *
     * Sets Count Values To Entity CampaignData, OrderData, CreativeData
     * @param array $counts
     * @param $entity
     * @return mixed
     */
    public function setEntityCounts(array $counts, $entity)
    {
        $entity->setClickCount($counts['clicks']);
        $entity->setImpressionCount($counts['impressions']);
        $entity->setViewedCount25($counts['view25']);
        $entity->setViewedCount50($counts['view50']);
        $entity->setViewedCount75($counts['view75']);
        $entity->setViewedCount100($counts['view100']);
        return $entity;
    }
}