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
namespace Smile\Retailer\Model\Retailer\OpeningHours;

use Smile\Retailer\Api\Data\OpeningHours\ManagementInterface;
use Smile\Retailer\Api\OpeningHoursRepositoryInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;

/**
 * Opening Hours Management class
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Management implements ManagementInterface
{
    /**
     * @var \Smile\Retailer\Api\OpeningHoursRepositoryInterface
     */
    private $openingHoursRepository;

    /**
     * @var \Smile\Retailer\Api\RetailerRepositoryInterface
     */
    private $retailerRepository;

    /**
     * Management constructor.
     *
     * @param \Smile\Retailer\Api\OpeningHoursRepositoryInterface $openingHoursRepository Opening Hours repository
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface     $retailerRepository     Retailer repository
     */
    public function __construct(
        OpeningHoursRepositoryInterface $openingHoursRepository,
        RetailerRepositoryInterface $retailerRepository
    ) {
        $this->openingHoursRepository = $openingHoursRepository;
        $this->retailerRepository     = $retailerRepository;
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
     * Retrieve Retailer Repository
     *
     * @return RetailerRepositoryInterface
     */
    private function getRetailerRepository()
    {
        return $this->retailerRepository;
    }
}
