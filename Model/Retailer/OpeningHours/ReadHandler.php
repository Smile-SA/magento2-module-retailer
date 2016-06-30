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

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Smile\Retailer\Api\OpeningHoursRepositoryInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;

/**
 * Read Handler for Opening Hours
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class ReadHandler implements ExtensionInterface
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
     * ReadHandler constructor.
     *
     * @param \Smile\Retailer\Api\OpeningHoursRepositoryInterface $openingHoursRepository Opening Hours Repository
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface     $retailerRepository     RetailerRepository
     */
    public function __construct(OpeningHoursRepositoryInterface $openingHoursRepository, RetailerRepositoryInterface $retailerRepository)
    {
        $this->openingHoursRepository = $openingHoursRepository;
        $this->retailerRepository     = $retailerRepository;
    }

    /**
     * Perform action on relation/extension attribute
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param object $entity    The entity
     * @param array  $arguments Arguments
     *
     * @return object|bool
     */
    public function execute($entity, $arguments = [])
    {
        /** @var $entity \Smile\Seller\Api\Data\SellerInterface */
        if ((int) $entity->getAttributeSetId() !== (int) $this->retailerRepository->getEntityAttributeSetId()) {
            return $entity;
        }

        $entityExtension = $entity->getExtensionAttributes();
        $openingHours    = $this->openingHoursRepository->getByRetailer($entity);
        if ($openingHours) {
            $entityExtension->setOpeningHours($openingHours);
        }

        $entity->setExtensionAttributes($entityExtension);

        return $entity;
    }
}
