<?php

namespace Smile\Retailer\Api\Data;

use Smile\Retailer\Api\Data\RetailerExtensionInterface;
use Smile\Seller\Api\Data\SellerInterface;

/**
 * @api
 */
interface RetailerInterface extends SellerInterface
{
    public const ATTRIBUTE_SET_RETAILER = 'retailer';

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Smile\Retailer\Api\Data\RetailerExtensionInterface|null
     */
    public function getExtensionAttributes(): ?RetailerExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes The additional attributes
     * @return $this
     */
    public function setExtensionAttributes(RetailerExtensionInterface $extensionAttributes): self;
}
