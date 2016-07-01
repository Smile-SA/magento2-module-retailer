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
namespace Smile\Retailer\Model\Retailer\SpecialOpeningHours;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;

/**
 * Save Handler for Opening Hours
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var \Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface
     */
    private $specialOpeningHoursRepository;

    /**
     * @var \Smile\Retailer\Api\RetailerRepositoryInterface
     */
    private $retailerRepository;

    /**
     * SaveHandler constructor.
     *
     * @param \Smile\Retailer\Api\SpecialOpeningHoursRepositoryInterface $specialOpeningHoursRepository Special Opening Hours Repository
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface            $retailerRepository            Retailer Repository
     */
    public function __construct(SpecialOpeningHoursRepositoryInterface $specialOpeningHoursRepository, RetailerRepositoryInterface $retailerRepository)
    {
        $this->specialOpeningHoursRepository = $specialOpeningHoursRepository;
        $this->retailerRepository            = $retailerRepository;
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

        $openingHours = $entity->getExtensionAttributes()->getSpecialOpeningHours();

        if (null !== $openingHours) {
            $this->specialOpeningHoursRepository->save($entity->getId(), $openingHours);
        }

        return $entity;
    }
}
