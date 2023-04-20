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
namespace Smile\Retailer\Model;

use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Seller\Model\Seller;

/**
 * Retailer Model class
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Retailer extends Seller implements RetailerInterface
{
    /**
     * {@inheritDoc}
     */
    public function getExtensionAttributes(): \Smile\Retailer\Api\Data\RetailerExtensionInterface|null
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory
                ->create('Smile\Retailer\Api\Data\RetailerInterface');
            $this->_setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }

    /**
     * {@inheritDoc}
     */
    public function setExtensionAttributes(\Smile\Retailer\Api\Data\RetailerExtensionInterface $extensionAttributes): self
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Retrieve AttributeSetName
     *
     * @return string
     */
    public function getAttributeSetName(): string
    {
        return ucfirst(self::ATTRIBUTE_SET_RETAILER);
    }
}
