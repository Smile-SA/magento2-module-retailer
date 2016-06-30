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

namespace Smile\Retailer\Api\Data;

/**
 * Opening Hours Interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface OpeningHoursInterface
{
    /**
     * The time slots type for this data
     */
    const EXTENSION_ATTRIBUTE_CODE = "opening_hours";

    /**
     * Retrieve time ranges as array
     *
     * @return null|array
     */
    public function getTimeRanges();

    /**
     * Load posted data into the object
     *
     * @param array $rangesData Time ranges data coming from a post request
     *
     * @return mixed
     */
    public function loadPostData($rangesData);

    /**
     * Retrieve retailer Id of this object
     *
     * @return null|int
     */
    public function getRetailerId();

    /**
     * Set the retailer Id of this object
     *
     * @param int $retailerId The retailer Id
     *
     * @return mixed
     */
    public function setRetailerId($retailerId);
}
