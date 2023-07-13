<?php

declare(strict_types=1);

namespace Smile\Retailer\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Setup\Patch\RetailerSetup;
use Smile\Retailer\Setup\Patch\RetailerSetupFactory;

/**
 * Class default groups and attributes for customer
 */
class DefaultRetailerAttributes implements DataPatchInterface, PatchVersionInterface
{
    public function __construct(
        private readonly RetailerSetupFactory $retailerSetupFactory,
        private readonly ModuleDataSetupInterface $moduleDataSetup
    ) {
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function apply(): self
    {
        /** @var RetailerSetup $retailerSetup */
        $retailerSetup = $this->retailerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $retailerSetup->installEntities();

        $this->installRetailerAttributeSet($retailerSetup);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getVersion(): string
    {
        return '2.0.1';
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
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
