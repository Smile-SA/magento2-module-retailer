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

use Smile\Seller\Api\Data\SellerInterface;

/**
 * Retailer Interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface RetailerInterface extends SellerInterface
{
    const ATTRIBUTE_SET_RETAILER = "Retailer";

    /**
     * Retrieve Opening Hours of this Retailer
     *
     * @return \Smile\Retailer\Api\Data\OpeningHoursInterface
     */
    public function getOpeningHours();

    /**
     * Set Opening Hours of this Retailer
     *
     * @param array $openingHours The opening hours
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     */
    public function setOpeningHours($openingHours);

    /**
     * Retrieve Opening Hours of this Retailer
     *
     * @return \Smile\Retailer\Api\Data\SpecialOpeningHoursInterface
     */
    public function getSpecialOpeningHours();

    /**
     * Set Opening Hours of this Retailer
     *
     * @param array $specialOpeningHours The opening hours
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     */
    public function setSpecialOpeningHours($specialOpeningHours);

    /**
     * Check if a retailer is open at a given date time
     *
     * @param \DateTime $dateTime A date time
     *
     * @return bool
     */
    public function isOpen($dateTime = null);

    /**
     * Check if a retailer is closed at a given date time
     *
     * @param string $dateTime A date time
     *
     * @return bool
     */
    public function isClosed($dateTime = null);
}
