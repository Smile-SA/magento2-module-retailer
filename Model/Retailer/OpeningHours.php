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
use Smile\Retailer\Api\Data\OpeningHoursInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots;
use Smile\Retailer\Model\Retailer\TimeSlots\AbstractTimeSlots;

/**
 * Opening Hours Model
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OpeningHours extends AbstractTimeSlots implements OpeningHoursInterface
{
    /**
     * OpeningHours constructor. Just inject correct extension attribute code
     *
     * @param \Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots $timeSlotsResource The Resource Model
     * @param \Magento\Framework\Json\Helper\Data                    $jsonHelper        JSON Helper
     * @param null|string                                            $attributeCode     Extension Attribute code
     * @param null                                                   $retailerId        Retailer Id
     */
    public function __construct(
        TimeSlots $timeSlotsResource,
        Data $jsonHelper,
        $attributeCode = OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE,
        $retailerId = null
    ) {
        parent::__construct($timeSlotsResource, $jsonHelper, $attributeCode, $retailerId);
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
                $values[$range["day_of_week"]]["date"] = $range["day_of_week"];
                if (null !== $range["start_hour"] && null !== $range["end_hour"]) {
                    $values[$range["day_of_week"]][self::TIME_RANGES_DATA][] = [
                        $this->dateToHour($range["start_hour"]),
                        $this->dateToHour($range["end_hour"]),
                    ];
                }
            }
        }

        return $values;
    }
}
