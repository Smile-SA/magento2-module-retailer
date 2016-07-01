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
namespace Smile\Retailer\Model\ResourceModel\Retailer;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Time Slots resource model, used to manage time based slots in a dedicated table
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class TimeSlots extends AbstractDb
{
    /**
     * Save time slots for a given retailer
     *
     * @param integer $retailerId    The retailer id
     * @param null    $attributeCode The time slot type to store
     * @param array   $timeSlots     The time slots to save, array based
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveTimeSlots($retailerId, $attributeCode, $timeSlots)
    {
        $data = [];
        $this->deleteTimeSlotsByRetailerId($retailerId, $attributeCode);

        foreach ($timeSlots as $date => $timeSlotItem) {
            $dateField = "date";
            if (is_numeric($date)) {
                $dateField = "day_of_week";
            }

            foreach ($timeSlotItem as $timeSlot) {
                $data[] = [
                    "retailer_id"    => $retailerId,
                    "attribute_code" => $attributeCode,
                    $dateField       => $date,
                    "start_hour"     => $timeSlot[0],
                    "end_hour"       => $timeSlot[1],
                ];
            }
        }

        $result = true;
        if (!empty($data)) {
            $result = (bool) $this->getConnection()->insertMultiple($this->getMainTable(), $data);
        }

        return $result;
    }

    /**
     * Retrieve all time slots of a given retailer
     *
     * @param integer $retailerId    The retailer id
     * @param null    $attributeCode The time slot type to retrieve, if any
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTimeSlots($retailerId, $attributeCode)
    {
        $binds = [':retailer_id' => (int) $retailerId, ':attribute_code' => (string) $attributeCode];

        $select = $this->getConnection()->select();
        $select->from($this->getMainTable())
            ->where("retailer_id = :retailer_id")
            ->where("attribute_code = :attribute_code");

        $timeSlotsData = $this->getConnection()->fetchAll($select, $binds);

        return $timeSlotsData;
    }

    /**
     * Delete Time Slots for a given retailer Id
     *
     * @param integer $retailerId    The retailer id
     * @param null    $attributeCode The time slot type to delete, if any
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteTimeSlotsByRetailerId($retailerId, $attributeCode = null)
    {
        $deleteCondition = ["retailer_id = ?" => $retailerId];
        if (null !== $attributeCode) {
            $deleteCondition["attribute_code = ?"] = $attributeCode;
        }

        return (bool) $this->getConnection()->delete($this->getMainTable(), $deleteCondition);
    }

    /**
     * Define main table name and attributes table
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) The method is inherited
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('smile_retailer_time_slots', 'retailer_id');
    }
}
