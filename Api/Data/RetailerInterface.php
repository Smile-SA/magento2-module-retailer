<?php

declare(strict_types=1);

namespace Smile\Retailer\Api\Data;

use Smile\Seller\Api\Data\SellerInterface;

/**
 * @api
 * @method mixed getData(...$key)
 * @method mixed setData(...$data)
 * @phpcs:disable SlevomatCodingStandard.Namespaces.ReferenceUsedNamesOnly.ReferenceViaFullyQualifiedName
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
interface RetailerInterface extends SellerInterface
{
    public const ATTRIBUTE_SET_RETAILER = "retailer";

    /**
     * Retrieve existing extension attributes object or create a new one. - need concrete type declaration to generate RetailerExtensionInterface
     *
     * @return \Smile\Retailer\Api\Data\RetailerExtensionInterface|null
     */
    public function getExtensionAttributes(): ?RetailerExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes The additional attributes - need concrete type declaration
     * @return $this
     */
    public function setExtensionAttributes(\Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes): self;
}
