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

use Magento\Framework\Model\AbstractModel;
use Smile\Retailer\Api\Data\OpeningHoursInterface;

/**
 * Opening Hours Model
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class OpeningHours extends AbstractModel implements OpeningHoursInterface
{
    /**
     * @var null
     */
    private $retailerId = null;

    /**
     * @var null
     */
    private $ranges = null;

    /**
     * Retrieve Time ranges of this opening hours
     *
     * @return array|null
     */
    public function getRanges()
    {
        if (null === $this->ranges) {
            $rangeData = $this->getResource()->getTimeSlots($this->getRetailerId(), OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE);
            $this->ranges = $this->aggregateTimeRangesByDate($rangeData);
        }

        return $this->ranges;
    }

    /**
     * Set Time ranges of current object
     *
     * @param array $rangesData The time ranges
     */
    public function setRanges($rangesData)
    {
        // TODO: Implement setRanges() method.
    }

    /**
     * Get retailer id of current object
     *
     * @return null
     */
    public function getRetailerId()
    {
        return $this->retailerId;
    }

    /**
     * Set current retailer Id
     *
     * @param int $retailerId The retailer Id
     *
     * @return $this
     */
    public function setRetailerId($retailerId)
    {
        $this->retailerId = $retailerId;

        return $this;
    }

    /**
     * Save time ranges
     *
     * @param array $rangeData Range data to save
     *
     * @return bool
     */
    public function saveRanges($rangeData)
    {
        $rangeData = $this->prepareTimeRangeStorage($rangeData);

        return $this->getResource()->saveTimeSlots(
            $this->getRetailerId(),
            OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE,
            $rangeData
        );
    }

    /**
     * Delete Opening hours of a given retailer Id
     *
     * @param integer $retailerId The retailer Id
     *
     * @return mixed
     */
    public function deleteByRetailerId($retailerId)
    {
        return $this->getResource()->deleteTimeSlotsByRetailerId($retailerId, OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE);
    }

    /**
     * Internal Constructor
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init('Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots');
    }

    /**
     * Aggregate Time ranges
     *
     * @param array $rangeData Range data to aggregate
     *
     * @return array
     */
    private function aggregateTimeRangesByDate($rangeData)
    {
        $values = [];

        if (!empty($rangeData)) {
            foreach ($rangeData as $range) {
                $values[$range["day_of_week"]][] = [
                    $this->dateToHour($range["start_hour"]),
                    $this->dateToHour($range["end_hour"]),
                ];
            }
        }

        return $values;
    }

    /**
     * Prepare Time ranges before storage
     *
     * @param array $rangeData Range data to aggregate
     *
     * @return array
     */
    private function prepareTimeRangeStorage($rangeData)
    {
        foreach ($rangeData as &$timeSlotItem) {
            // @TODO Better parsing
            if (is_string($timeSlotItem)) {
                $timeSlotItem = json_decode($timeSlotItem);
            }

            if (empty($timeSlotItem)) {
                continue;
            }

            foreach ($timeSlotItem as &$timeSlot) {
                $timeSlot[0] = $this->dateFromHour($timeSlot[0]);
                $timeSlot[1] = $this->dateFromHour($timeSlot[1]);
            }
        }

        return array_filter($rangeData);
    }

    /**
     * Build default date (01.01.1970) from an hour
     *
     * @TODO Rework this one
     *
     * @param string $hour The hour
     *
     * @return string
     */
    private function dateFromHour($hour)
    {
        $date = new \Zend_Date(0, \Zend_Date::TIMESTAMP);
        $date->setTime($hour);

        return $date->toString(\Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT);
    }

    /**
     * Extract hour from a date
     *
     * @TODO Rework this one
     *
     * @param string $date The date
     *
     * @return string
     */
    private function dateToHour($date)
    {
        $date = new \Zend_Date($date, \Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT);

        return $date->toString(\Zend_Date::TIME_SHORT);
    }
}
