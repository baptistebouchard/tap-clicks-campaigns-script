<?php

namespace Script\Utils;

/**
 * Simple Helper Class to validate or filter data.
 * Class ServiceUtils
 * @package Script\Utils
 */
class ServiceUtils
{

    const DATE_FILTER ='2016-05';
    const LISTED_ADVERTISER = [
        '9656',
        '8876',
        '9518',
        '9528',
        '8334'
    ];

    /**
     * @param $campaign
     * @return bool
     */
    public function isListedAdvertiser($campaign)
    {
        return in_array($campaign['advertiserId'], $this::LISTED_ADVERTISER, false);
    }

    /**
     * Get Files containing a Date in May 2016
     * @param $fileNameList
     * @return array
     */
    public function getValidFileNames($fileNameList)
    {
        $validFiles = array_filter($fileNameList, function ($fileName) {
            return strpos($fileName, $this::DATE_FILTER);
        });

        return array_map(function ($filePath) {
            // we only retrieve the fileName;
            $explode = explode('/', $filePath);
            return $explode[count($explode) - 1];
        }, $validFiles);
    }
}