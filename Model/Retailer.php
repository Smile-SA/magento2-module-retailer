<?php

declare(strict_types=1);

namespace Smile\Retailer\Model;

use Smile\Retailer\Api\Data\RetailerExtensionInterface;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Seller\Model\Seller;

/**
 * Retailer Model class.
 */
class Retailer extends Seller implements RetailerInterface
{
    /**
     * @inheritdoc
     */
    public function getExtensionAttributes(): ?RetailerExtensionInterface
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        // @phpstan-ignore-next-line - this if seems not necessary, TODO: test without it
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory
                ->create(RetailerInterface::class);
            $this->_setExtensionAttributes($extensionAttributes);
        }

        /** @var RetailerExtensionInterface $extensionAttributes */
        return $extensionAttributes;
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(RetailerExtensionInterface $extensionAttributes): self
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @inheritdoc
     */
    public function getAttributeSetName(): string
    {
        return ucfirst(self::ATTRIBUTE_SET_RETAILER);
    }
}
