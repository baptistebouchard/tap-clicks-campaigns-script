<?php


use PHPUnit\Framework\TestCase;
use Script\Utils\ServiceUtils;


class ServiceUtilsTest extends TestCase
{
    public function getIsListedAdvertiserTestCases()
    {
        return [
            [
                [ 'advertiserId' => '9656'],
                true
            ],
            [
                [ 'advertiserId' => 9656],
                true
            ],
            [
                [ 'advertiserId' => '1234'],
                false
            ]
        ];
    }

    /**
     * @param $campaign
     * @param $expected
     * @dataProvider getIsListedAdvertiserTestCases
     */
    public function testIsListedAdvertiser($campaign, $expected)
    {
        $serviceUtils = new ServiceUtils();
        $result = $serviceUtils->isListedAdvertiser($campaign);
        $this->assertEquals($result, $expected);
    }

    public function getValidFileNamesTestCases()
    {
        return [
            [
                [ 'file1-2016-05-01', 'file1-2016-05-22', 'file1-2016-05-22', 'file1-2017-05', 'file1-2018-05-02' ],
                [ 'file1-2016-05-01', 'file1-2016-05-22', 'file1-2016-05-22' ],
            ],
            [
                [ 'file1-2017-05', 'file1-2018-05-02' ],
                [],
            ],
            [
                [ 'file1-2016-05-01', 'file1-2016-05-22', 'file1-2016-05-22' ],
                [ 'file1-2016-05-01', 'file1-2016-05-22', 'file1-2016-05-22' ],
            ]
        ];
    }

    /**
     * @param $fileNameList
     * @param $expected
     * @dataProvider getValidFileNamesTestCases
     */
    public function testGetValidFileNames($fileNameList, $expected)
    {
        $serviceUtils = new ServiceUtils();
        $result = $serviceUtils->getValidFileNames($fileNameList);
        $this->assertEquals($result, $expected);
    }
}