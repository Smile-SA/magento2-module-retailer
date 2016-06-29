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
     * @return mixed
     */
    public function getOpeningHours();

    /**
     * Set Opening Hours of this Retailer
     *
     * @param array $openingHours The opening hours
     *
     * @return mixed
     */
    public function setOpeningHours($openingHours);
}
