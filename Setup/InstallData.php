<?php

namespace Smile\Retailer\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * Seller Data install class.
 */
class InstallData implements InstallDataInterface
{
    public function __construct(private RetailerSetupFactory $retailerSetupFactory)
    {
    }

    /**
     * @inheritdoc
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
     */
    protected function installRetailerAttributeSet(RetailerSetup $setup): void
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
