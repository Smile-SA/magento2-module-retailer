<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade Smile Elastic Suite to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Api\Data;

/**
 * Generic Interface for retailer time slots entities
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface TimeSlotsInterface
{
    /**
     * Field used for time ranges
     */
    const TIME_RANGES_DATA = "time_ranges";

    /**
     * Retrieve time ranges as array
     *
     * @return mixed[]
     */
    public function getTimeRanges();

    /**
     * Load posted data into the object
     *
     * @param array $rangesData Time ranges data coming from a post request
     *
     * @return TimeSlotsInterface
     */
    public function loadPostData($rangesData);

    /**
     * Retrieve retailer Id of this object
     *
     * @return int
     */
    public function getRetailerId();

    /**
     * Set the retailer Id of this object
     *
     * @param int $retailerId The retailer Id
     *
     * @return TimeSlotsInterface
     */
    public function setRetailerId($retailerId);
}
