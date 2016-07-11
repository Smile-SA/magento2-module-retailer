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
namespace Smile\Retailer\Model\Retailer\TimeSlots;

use Smile\Retailer\Api\Data\TimeSlotsInterface;
use Magento\Framework\Data\AbstractDataObject;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Stdlib\DateTime;
use Smile\Retailer\Api\Data\OpeningHoursInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots as ResourceModel;
use Zend_Date;

/**
 * Generic Model for Time Slots entities
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
abstract class AbstractTimeSlots extends AbstractDataObject implements TimeSlotsInterface
{
    /**
     * @var null
     */
    private $retailerId = null;

    /**
     * @var null
     */
    private $timeRanges = null;

    /**
     * @var string The format used to manipulate date
     */
    private $dateFormat = DateTime::DATETIME_INTERNAL_FORMAT;

    /**
     * @var string The format used to manipulate time
     */
    private $timeFormat = Zend_Date::TIME_SHORT;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    private $jsonHelper;

    /**
     * @var \Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots
     */
    private $timeSlotsResource;

    /**
     * @var null|string
     */
    private $attributeCode = null;

    /**
     * OpeningHours constructor.
     *
     * @param \Smile\Retailer\Model\ResourceModel\Retailer\TimeSlots $timeSlotsResource Resource Model for Timeslots Management
     * @param \Magento\Framework\Json\Helper\Data                    $jsonHelper        JSON helper
     * @param string                                                 $attributeCode     The extension attribute code (the time of slot)
     * @param int                                                    $retailerId        The retailer Id
     */
    public function __construct(ResourceModel $timeSlotsResource, JsonHelper $jsonHelper, $attributeCode = null, $retailerId = null)
    {
        $this->timeSlotsResource = $timeSlotsResource;
        $this->jsonHelper = $jsonHelper;
        $this->retailerId = (int) $retailerId;
        $this->attributeCode = $attributeCode;
    }

    /**
     * Retrieve Time ranges of this opening hours
     *
     * @return array|null
     */
    public function getTimeRanges()
    {
        if (null === $this->timeRanges) {
            $this->loadTimeRanges();
        }

        return $this->timeRanges;
    }

    /**
     * Retrieve Time ranges of this opening hours
     *
     * @return array|null
     */
    public function loadTimeRanges()
    {
        if (null === $this->timeRanges) {
            $rangeData = $this->timeSlotsResource->getTimeSlots(
                $this->getRetailerId(),
                $this->attributeCode
            );

            $this->timeRanges = $this->aggregateTimeRanges($rangeData);
        }
    }

    /**
     * Retrieve Date Format used
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Retrieve Time Format used
     *
     * @return string
     */
    public function getTimeFormat()
    {
        return $this->timeFormat;
    }

    /**
     * Set Time format to Use
     *
     * @param string $timeFormat The time format to use
     */
    public function setTimeFormat($timeFormat)
    {
        $this->timeFormat = $timeFormat;
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
        foreach ($rangesData as &$timeSlotItem) {
            if (is_string($timeSlotItem)) {
                try {
                    $timeSlotItem = $this->jsonHelper->jsonDecode($timeSlotItem);
                } catch (\Zend_Json_Exception $exception) {
                    $timeSlotItem = [];
                }
            }

            if (!empty($timeSlotItem)) {
                foreach ($timeSlotItem as &$timeSlot) {
                    $timeSlot = array_map([$this, 'dateFromHour'], $timeSlot);
                }
            }
        }

        $this->timeRanges = $rangesData;

        return $this;
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
     * Save time ranges into DB
     *
     * @return bool
     */
    public function saveTimeRanges()
    {
        return $this->timeSlotsResource->saveTimeSlots(
            $this->getRetailerId(),
            $this->attributeCode,
            $this->timeRanges
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
        return $this->getTimeSlotResource()->deleteTimeSlotsByRetailerId($retailerId, OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE);
    }

    /**
     * Internal Constructor
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) Method is inherited
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
    abstract protected function aggregateTimeRanges($rangeData);

    /**
     * Build default date (01.01.1970) from an hour
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod) called via an array_map
     *
     * @param string $hour The hour
     *
     * @return string
     */
    protected function dateFromHour($hour)
    {
        $date = new Zend_Date(0, Zend_Date::TIMESTAMP); // Init as 1970-01-01 since field is store on a DATETIME column.
        $date->setTime($hour);

        return $date->toString($this->getDateFormat());
    }

    /**
     * Extract hour from a date
     *
     * @param string $date The date
     *
     * @return string
     */
    protected function dateToHour($date)
    {
        $date = new Zend_Date($date, $this->getDateFormat());

        return $date->toString($this->getTimeFormat());
    }
}
