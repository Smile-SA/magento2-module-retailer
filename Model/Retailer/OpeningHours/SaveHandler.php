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
use Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface;

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
     * @var \Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface
     */
    private $openingHoursRepository;

    /**
     * ReadHandler constructor.
     *
     * @param \Smile\Retailer\Api\Data\OpeningHours\OpeningHoursRepositoryInterface $openingHoursRepository Opening Hours Repository
     */
    public function __construct(OpeningHoursRepositoryInterface $openingHoursRepository)
    {
        $this->openingHoursRepository = $openingHoursRepository;
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
        $openingHours = $entity->getExtensionAttributes()->getOpeningHours();
        /*if ($entity->getTypeId() !== 'bundle' || empty($bundleProductOptions)) {
            return $entity;
        }*/

        $this->openingHoursRepository->save($entity->getId(), $openingHours);

        return $entity;
    }
}
