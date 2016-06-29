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
namespace Smile\Retailer\Api\Data\OpeningHours;

/**
 * Opening Hours Management Interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface OpeningHoursManagementInterface
{
    /**
     * Check if a retailer is open at a given date time
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     * @param mixed                                      $dateTime A date time
     *
     * @return mixed
     */
    public function isOpen($retailer, $dateTime);

    /**
     * Check if a retailer is closed at a given date time
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     * @param mixed                                      $dateTime A date time
     *
     * @return mixed
     */
    public function isClosed($retailer, $dateTime);
}
