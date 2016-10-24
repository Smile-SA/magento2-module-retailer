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
namespace Smile\Retailer\Model;

use Magento\Framework\EntityManager\EntityManager;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Model\SellerFactory;
use Smile\Seller\Model\SellerRepository;

/**
 * Retailer Repository
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerRepository extends SellerRepository implements RetailerRepositoryInterface
{
    /**
     * RetailerRepository constructor.
     *
     * @param \Magento\Framework\EntityManager\EntityManager $entityManager           The entity manager
     * @param \Smile\Seller\Model\SellerFactory              $sellerFactory           The Seller Factory
     * @param \Smile\Seller\Model\CollectionFactory          $sellerCollectionFactory The Seller Collecrtion Factory
     * @param null|string                                    $attributeSetName        The attribute set name
     */
    public function __construct(
        EntityManager $entityManager,
        SellerFactory $sellerFactory,
        CollectionFactory $sellerCollectionFactory,
        $attributeSetName = RetailerInterface::ATTRIBUTE_SET_RETAILER
    ) {
        parent::__construct($entityManager, $sellerFactory, $sellerCollectionFactory, $attributeSetName);
    }
}
