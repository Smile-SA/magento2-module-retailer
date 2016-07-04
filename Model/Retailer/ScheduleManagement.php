<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Model\Retailer;

use Smile\Retailer\Api\RetailerScheduleManagementInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Api\OpeningHoursRepositoryInterface;
use Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface;

/**
 * Schedule Management Class for Retailers.
 * Handles all schedule related operations.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class ScheduleManagement implements RetailerScheduleManagementInterface
{
    /**
     * @var \Smile\Retailer\Api\OpeningHoursRepositoryInterface
     */
    private $openingHoursRepository;

    /**
     * @var \Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface
     */
    private $specialOpeningHoursRepository;

    /**
     * @var \Smile\Retailer\Api\RetailerRepositoryInterface
     */
    private $retailerRepository;

    /**
     * Management constructor.
     *
     * @param \Smile\Retailer\Api\OpeningHoursRepositoryInterface        $openingHoursRepository        Opening Hours repository
     * @param \Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface $specialOpeningHoursRepository Opening Hours repository
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface            $retailerRepository            Retailer repository
     */
    public function __construct(
        OpeningHoursRepositoryInterface $openingHoursRepository,
        SpecialOpeningHoursRepositoryInterface $specialOpeningHoursRepository,
        RetailerRepositoryInterface $retailerRepository
    ) {
        $this->openingHoursRepository        = $openingHoursRepository;
        $this->specialOpeningHoursRepository = $specialOpeningHoursRepository;
        $this->retailerRepository            = $retailerRepository;
    }

    /**
     * Save Special Opening Hours for a given retailer
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     *
     * @return \Smile\Seller\Api\Data\SellerInterface
     */
    public function saveSpecialOpeningHours(\Smile\Seller\Api\Data\SellerInterface $retailer)
    {
        /** @var $entity \Smile\Seller\Api\Data\SellerInterface */
        if ((int) $retailer->getAttributeSetId() !== (int) $this->getRetailerRepository()->getEntityAttributeSetId()) {
            return $retailer;
        }

        $openingHours = $retailer->getExtensionAttributes()->getSpecialOpeningHours();

        if (null !== $openingHours) {
            $openingHours->setRetailerId($retailer->getId());
            $this->getSpecialOpeningHoursRepository()->save($openingHours);
        }

        return $retailer;
    }

    /**
     * Load Special Opening Hours for a given retailer
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     *
     * @return \Smile\Seller\Api\Data\SellerInterface
     */
    public function loadSpecialOpeningHours(\Smile\Seller\Api\Data\SellerInterface $retailer)
    {
        /** @var $entity \Smile\Seller\Api\Data\SellerInterface */
        if ((int) $retailer->getAttributeSetId() !== (int) $this->getRetailerRepository()->getEntityAttributeSetId()) {
            return $retailer;
        }

        $entityExtension = $retailer->getExtensionAttributes();
        $openingHours    = $this->getSpecialOpeningHoursRepository()->getByRetailer($retailer);
        if ($openingHours) {
            $entityExtension->setSpecialOpeningHours($openingHours);
        }

        $retailer->setExtensionAttributes($entityExtension);

        return $retailer;
    }

    /**
     * Save Opening Hours for a given retailer
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     *
     * @return \Smile\Seller\Api\Data\SellerInterface
     */
    public function saveOpeningHours(\Smile\Seller\Api\Data\SellerInterface $retailer)
    {
        /** @var $entity \Smile\Seller\Api\Data\SellerInterface */
        if ((int) $retailer->getAttributeSetId() !== (int) $this->getRetailerRepository()->getEntityAttributeSetId()) {
            return $retailer;
        }

        $openingHours = $retailer->getExtensionAttributes()->getOpeningHours();

        if (null !== $openingHours) {
            $openingHours->setRetailerId($retailer->getId());
            $this->getOpeningHoursRepository()->save($openingHours);
        }

        return $retailer;
    }

    /**
     * Load Opening Hours for a given retailer
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     *
     * @return \Smile\Seller\Api\Data\SellerInterface
     */
    public function loadOpeningHours(\Smile\Seller\Api\Data\SellerInterface $retailer)
    {
        /** @var $entity \Smile\Seller\Api\Data\SellerInterface */
        if ((int) $retailer->getAttributeSetId() !== (int) $this->getRetailerRepository()->getEntityAttributeSetId()) {
            return $retailer;
        }

        $entityExtension = $retailer->getExtensionAttributes();
        $openingHours    = $this->getOpeningHoursRepository()->getByRetailer($retailer);
        if ($openingHours) {
            $entityExtension->setOpeningHours($openingHours);
        }

        $retailer->setExtensionAttributes($entityExtension);

        return $retailer;
    }

    /**
     * Retrieve Opening Hours Repository
     *
     * @return OpeningHoursRepositoryInterface
     */
    private function getOpeningHoursRepository()
    {
        return $this->openingHoursRepository;
    }

    /**
     * Retrieve Special Opening Hours Repository
     *
     * @return SpecialOpeningHoursRepositoryInterface
     */
    private function getSpecialOpeningHoursRepository()
    {
        return $this->specialOpeningHoursRepository;
    }

    /**
     * Retrieve Retailer Repository
     *
     * @return RetailerRepositoryInterface
     */
    private function getRetailerRepository()
    {
        return $this->retailerRepository;
    }
}
