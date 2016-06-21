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

use Smile\Retailer\Api\RetailerRepositoryInterface;
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
     * Create retailer service
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $seller The seller
     *
     * @return \Smile\Seller\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Smile\Seller\Api\Data\SellerInterface $seller)
    {
        $seller->setAttributeSetId(19);

        return parent::save($seller);
    }
}
