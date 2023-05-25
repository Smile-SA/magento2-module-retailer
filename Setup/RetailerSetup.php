<?php

namespace Smile\Retailer\Setup;

use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Seller\Setup\SellerSetup;

/**
 * Retailer Setup class : contains EAV Attributes declarations.
 */
class RetailerSetup extends SellerSetup
{
    /**
     * @inheritdoc
     */
    public function getDefaultEntities()
    {
        $entities = parent::getDefaultEntities();
        $entities[RetailerInterface::ENTITY]['attributes'] = array_merge(
            $entities[RetailerInterface::ENTITY]['attributes'],
            $this->getRetailerAttributes()
        );

        return $entities;
    }

    /**
     * Get the group definition.
     */
    public function getGroupsDefinition(): array
    {
        return [
            'General' => 10,
            'Images' => 20,
            'Meta Data' => 30,
        ];
    }

    /**
     * Get the attributes set definition for retailers.
     */
    public function getAttributeSetDefinition(): array
    {
        return [
            ucfirst(RetailerInterface::ATTRIBUTE_SET_RETAILER) => [
                'General' => [
                    'name',
                    'seller_code',
                    'is_active',
                    'description',
                ],
                'Meta Data' => [
                    'meta_title',
                    'meta_keywords',
                    'meta_description',
                ],
            ],
        ];
    }

    /**
     * Retrieve retailer specific attributes.
     */
    private function getRetailerAttributes(): array
    {
        return [];
    }
}
