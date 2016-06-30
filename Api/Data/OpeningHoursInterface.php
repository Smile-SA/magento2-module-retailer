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
    const EXTENSION_ATTRIBUTE_CODE = "opening_hours";

    public function getTimeRanges();

    public function loadPostData($rangesData);

    public function getRetailerId();

    public function setRetailerId($retailerId);
}
