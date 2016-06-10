<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\Retailer\Setup;

use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Seller\Setup\SellerSetup;

/**
 * Retailer Setup class : contains EAV Attributes declarations.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerSetup extends SellerSetup
{
    /**
     * Default entities and attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
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
     * Get the group definition
     *
     * @return array
     */
    public function getGroupsDefinition()
    {
        return [
            'General'       => 10,
            'Images'        => 20,
            'Meta Data'     => 30,
        ];
    }

    /**
     * Get the attributes set definition for retailers
     *
     * @return array
     */
    public function getAttributeSetDefinition()
    {
        return [
            RetailerInterface::ATTRIBUTE_SET_RETAILER => [
                'General' => [
                    'name',
                    'seller_code',
                    'is_active',
                    'description',
                    'test_retailer'
                ],
                'Images' => [
                    'image',
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
     * Retrieve retailer specific attributes
     *
     * @return array
     */
    private function getRetailerAttributes()
    {
        $attributes = [
            'test_retailer' => [
                'type' => 'varchar',
                'label' => 'Test Retailer',
                'input' => 'text',
                'sort_order' => 100,
            ]
        ];

        return $attributes;
    }
}
