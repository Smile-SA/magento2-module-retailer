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
use Smile\Retailer\Api\Data\SpecialOpeningHours\ManagementInterface;

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
     * @var \Smile\Retailer\Api\Data\SpecialOpeningHours\ManagementInterface
     */
    private $specialOpeningHoursManagement;

    /**
     * SaveHandler constructor.
     *
     * @param \Smile\Retailer\Api\Data\SpecialOpeningHours\ManagementInterface $openingHoursManagement Special Opening Hours Management
     */
    public function __construct(ManagementInterface $openingHoursManagement)
    {
        $this->specialOpeningHoursManagement = $openingHoursManagement;
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
        $entity = $this->specialOpeningHoursManagement->saveSpecialOpeningHours($entity);

        return $entity;
    }
}
