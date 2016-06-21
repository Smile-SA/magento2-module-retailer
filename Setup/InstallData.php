<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Seller
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\Retailer\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * Seller Data install class.
 *
 * @category Smile
 * @package  Smile\Seller
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var RetailerSetupFactory
     */
    private $retailerSetupFactory;

    /**
     * InstallData constructor
     *
     * @param RetailerSetupFactory $retailerSetupFactory The Retailer Setup factory
     */
    public function __construct(RetailerSetupFactory $retailerSetupFactory)
    {
        $this->retailerSetupFactory = $retailerSetupFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var RetailerSetup $retailerSetup */
        $retailerSetup = $this->retailerSetupFactory->create(['setup' => $setup]);
        $retailerSetup->installEntities();

        $this->installRetailerAttributeSet($retailerSetup);

        $setup->endSetup();
    }


    /**
     * Initialize the Retailer attribute sets.
     *
     * @param RetailerSetup $setup The Retailer Setup
     */
    protected function installRetailerAttributeSet(RetailerSetup $setup)
    {
        $attributeSetsDefinition = $setup->getAttributeSetDefinition();
        $groupsDefinition        = $setup->getGroupsDefinition();

        foreach ($attributeSetsDefinition as $attributeSetName => $groups) {
            $setup->addAttributeSet(RetailerInterface::ENTITY, $attributeSetName);

            foreach ($groups as $groupName => $attributes) {
                $sortOrder = $groupsDefinition[$groupName];

                $setup->addAttributeGroup(RetailerInterface::ENTITY, $attributeSetName, $groupName, $sortOrder);

                foreach ($attributes as $key => $attributeCode) {
                    $sortOrder = ($key + 1 ) * 10;
                    $setup->addAttributeToGroup(
                        RetailerInterface::ENTITY,
                        $attributeSetName,
                        $groupName,
                        $attributeCode,
                        $sortOrder
                    );
                }
            }
        }
    }
}
