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

use Smile\Retailer\Api\Data\OpeningHoursInterface;
use Smile\Retailer\Api\Data\SpecialOpeningHoursInterface;
use Smile\Retailer\Api\RetailerScheduleManagementInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Api\OpeningHoursRepositoryInterface;
use Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface;
use Smile\Retailer\Api\Data\OpeningHoursInterfaceFactory;
use Smile\Retailer\Api\Data\SpecialOpeningHoursInterfaceFactory;

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
     * @var \Smile\Retailer\Api\Data\OpeningHoursFactoryInterface
     */
    private $openingHoursFactory;

    /**
     * @var \Smile\Retailer\Api\Data\SpecialOpeningHoursFactoryInterface
     */
    private $specialOpeningHoursFactory;

    /**
     * Management constructor.
     *
     * @param \Smile\Retailer\Api\OpeningHoursRepositoryInterface          $openingHoursRepository        Opening Hours repository
     * @param \Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface   $specialOpeningHoursRepository Opening Hours repository
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface              $retailerRepository            Retailer repository
     * @param \Smile\Retailer\Api\Data\OpeningHoursInterfaceFactory        $openingHoursFactory           Opening Hours Factory
     * @param \Smile\Retailer\Api\Data\SpecialOpeningHoursInterfaceFactory $specialOpeningHoursFactory    Special Opening Hours Factory
     */
    public function __construct(
        OpeningHoursRepositoryInterface $openingHoursRepository,
        SpecialOpeningHoursRepositoryInterface $specialOpeningHoursRepository,
        RetailerRepositoryInterface $retailerRepository,
        OpeningHoursInterfaceFactory $openingHoursFactory,
        SpecialOpeningHoursInterfaceFactory $specialOpeningHoursFactory
    ) {
        $this->openingHoursRepository        = $openingHoursRepository;
        $this->specialOpeningHoursRepository = $specialOpeningHoursRepository;
        $this->retailerRepository            = $retailerRepository;
        $this->openingHoursFactory           = $openingHoursFactory;
        $this->specialOpeningHoursFactory    = $specialOpeningHoursFactory;
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
     * Append Data from POST to a given retailer
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     * @param                                        $data     POST Data
     */
    public function setPostScheduleData(\Smile\Seller\Api\Data\SellerInterface $retailer, $data)
    {
        if (isset($data[OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE])) {
            $extension = $retailer->getExtensionAttributes();
            $openingHours = $this->getOpeningHoursFactory()->create();
            $openingHours->loadPostData($data[OpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE]);
            $extension->setOpeningHours($openingHours);
            $retailer->setExtensionAttributes($extension);
        }

        if (isset($data[SpecialOpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE])) {
            $extension = $retailer->getExtensionAttributes();
            $specialOpeningHours = $this->getSpecialOpeningHoursFactory()->create();
            $specialOpeningHours->loadPostData($data[SpecialOpeningHoursInterface::EXTENSION_ATTRIBUTE_CODE]);
            $extension->setSpecialOpeningHours($specialOpeningHours);
            $retailer->setExtensionAttributes($extension);
        }
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
     * Retrieve Opening Hours Factory
     *
     * @return OpeningHoursFactoryInterface
     */
    private function getOpeningHoursFactory()
    {
        return $this->openingHoursFactory;
    }

    /**
     * Retrieve Special Opening Hours Factory
     *
     * @return SpecialOpeningHoursFactoryInterface
     */
    private function getSpecialOpeningHoursFactory()
    {
        return $this->specialOpeningHoursFactory;
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
