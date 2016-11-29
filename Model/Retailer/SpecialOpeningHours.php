<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Model\Retailer;

use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Smile\Retailer\Api\Data\SpecialOpeningHoursInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots;
use Smile\Retailer\Model\Retailer\TimeSlots\AbstractTimeSlots;

/**
 * Special Opening Hours Model
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class SpecialOpeningHours extends AbstractTimeSlots implements SpecialOpeningHoursInterface
{
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * Special Opening Hours constructor. Just inject correct extension attribute code
     *
     * @param \Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots $timeSlotsResource The Resource Model
     * @param \Magento\Framework\Json\Helper\Data                    $jsonHelper        JSON Helper
     * @param TimezoneInterface                                      $localeDate        The Locale Date Interface
     * @param null|string                                            $attributeCode     Extension Attribute code
     * @param null                                                   $retailerId        Retailer Id
     */
    public function __construct(
        TimeSlots $timeSlotsResource,
        Data $jsonHelper,
        TimezoneInterface $localeDate,
        $attributeCode = SpecialOpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE,
        $retailerId = null
    ) {
        $this->localeDate = $localeDate;
        parent::__construct($timeSlotsResource, $jsonHelper, $attributeCode, $retailerId);
    }

    /**
     * Load submitted data and parse it as proper format
     *
     * @param array $rangesData The time ranges
     *
     * @return $this
     */
    public function loadPostData($rangesData)
    {
        $openingHoursRange = [];

        foreach ($rangesData as $range) {
            if (!isset($range['date']) || (trim($range['date']) == "")) {
                continue;
            }

            $date = $this->formatDate($range['date']);

            $openingHoursRange[$date] = $range['opening_hours'];
        }

        parent::loadPostData($openingHoursRange);
    }

    /**
     * Aggregate Time ranges
     *
     * @param array $rangeData Range data to aggregate
     *
     * @return array
     */
    protected function aggregateTimeRanges($rangeData)
    {
        $values = [];

        if (!empty($rangeData)) {
            foreach ($rangeData as $range) {
                $data = [];
                if (null !== $range["start_hour"] && null !== $range["end_hour"]) {
                    $data = [
                        $this->dateToHour($range["start_hour"]),
                        $this->dateToHour($range["end_hour"]),
                    ];
                }
                $values[$range["date"]]["date"] = $range["date"];
                $values[$range["date"]][self::TIME_RANGES_DATA][] = $data;
            }
        }

        return $values;
    }

    /**
     * Prepare date for save in DB
     *
     * @param string $date   The Date
     * @param string $format This Date format
     *
     * @return string
     */
    private function formatDate($date, $format = null)
    {
        if (null === $format) {
            $format = $this->localeDate->getDateFormatWithLongYear();
        }
        $date = new \Zend_Date($date, $format);

        return $date->toString(DateTime::DATE_INTERNAL_FORMAT);
    }
}
