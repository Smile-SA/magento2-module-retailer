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
use Smile\Retailer\Api\RetailerScheduleManagementInterface;

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
     * @var \Smile\Retailer\Api\RetailerScheduleManagementInterface
     */
    private $scheduleManagement;

    /**
     * SaveHandler constructor.
     *
     * @param \Smile\Retailer\Api\RetailerScheduleManagementInterface $scheduleManagement Schedule Manager
     */
    public function __construct(RetailerScheduleManagementInterface $scheduleManagement)
    {
        $this->scheduleManagement = $scheduleManagement;
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
        $entity = $this->scheduleManagement->saveSpecialOpeningHours($entity);

        return $entity;
    }
}
