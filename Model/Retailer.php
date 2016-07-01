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
use Smile\Retailer\Model\Retailer\OpeningHours;
use Smile\Seller\Model\Seller;

/**
 * Retailer Model class
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Retailer extends Seller implements RetailerInterface
{
    /**
     * @var null
     */
    private $openingHours = null;

    /**
     * @var null
     */
    private $specialOpeningHours = null;

    /**
     * Get opening hours for this retailer
     *
     * @return \Smile\Retailer\Api\Data\OpeningHoursInterface
     */
    public function getOpeningHours()
    {
        if ($this->openingHours === null) {
            $this->openingHours = $this->getExtensionAttributes()->getOpeningHours();
        }

        return $this->openingHours;
    }

    /**
     * Opening Hours setter
     *
     * @param \Smile\Retailer\Api\Data\OpeningHoursInterface $openingHours The opening hours of this retailer
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     */
    public function setOpeningHours($openingHours)
    {
        $extension = $this->getExtensionAttributes();
        $extension->setOpeningHours($openingHours);
        $this->setExtensionAttributes($extension);

        return $this;
    }

    /**
     * Get opening hours for this retailer
     *
     * @return \Smile\Retailer\Api\Data\OpeningHoursInterface
     */
    public function getSpecialOpeningHours()
    {
        if ($this->specialOpeningHours === null) {
            $this->specialOpeningHours = $this->getExtensionAttributes()->getSpecialOpeningHours();
        }

        return $this->specialOpeningHours;
    }

    /**
     * Special Opening Hours setter
     *
     * @param \Smile\Retailer\Api\Data\SpecialOpeningHoursInterface $specialOpeningHours The special opening hours of this retailer
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     */
    public function setSpecialOpeningHours($specialOpeningHours)
    {
        $extension = $this->getExtensionAttributes();
        $extension->setSpecialOpeningHours($specialOpeningHours);
        $this->setExtensionAttributes($extension);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isOpen($retailer, $dateTime = null)
    {
        // TODO: Implement isOpen() method.
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed($retailer, $dateTime = null)
    {
        return !$this->isOpen($retailer, $dateTime);
    }

    /**
     * Retrieve custom attributes codes list
     *
     * @return array
     */
    protected function getCustomAttributesCodes()
    {
        $attributesCodes = parent::getCustomAttributesCodes();
        $attributesCodes[] = "opening_hours";

        return $attributesCodes;
    }
}
