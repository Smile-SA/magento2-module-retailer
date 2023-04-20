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
    const ATTRIBUTE_SET_RETAILER = "retailer";

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Smile\Retailer\Api\Data\RetailerExtensionInterface|null
     */
    public function getExtensionAttributes(): \Smile\Retailer\Api\Data\RetailerExtensionInterface|null;

    /**
     * Set an extension attributes object.
     *
     * @param \Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes The additional attributes
     *
     * @return $this
     */
    public function setExtensionAttributes(\Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes): self;
}
