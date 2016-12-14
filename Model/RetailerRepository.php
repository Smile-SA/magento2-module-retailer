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

use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Model\SellerRepositoryFactory;

/**
 * Retailer Repository
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class RetailerRepository implements RetailerRepositoryInterface
{
    /**
     * @var \Smile\Seller\Model\SellerRepository
     */
    private $sellerRepository;

    /**
     * Constructor.
     *
     * @param \Smile\Seller\Model\SellerRepositoryFactory       $sellerRepositoryFactory Seller repository.
     * @param \Smile\Retailer\Api\Data\RetailerInterfaceFactory $retailerFactory         Retailer factory.
     */
    public function __construct(
        \Smile\Seller\Model\SellerRepositoryFactory $sellerRepositoryFactory,
        \Smile\Retailer\Api\Data\RetailerInterfaceFactory $retailerFactory
    ) {
        $this->sellerRepository = $sellerRepositoryFactory->create([
            'sellerFactory'    => $retailerFactory,
            'attributeSetName' => RetailerInterface::ATTRIBUTE_SET_RETAILER,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function save(\Smile\Retailer\Api\Data\RetailerInterface $retailer)
    {
        return $this->sellerRepository->save($retailer);
    }

    /**
     * {@inheritDoc}
     */
    public function get($retailerId, $storeId = null)
    {
        return $this->sellerRepository->get($retailerId, $storeId);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(\Smile\Retailer\Api\Data\RetailerInterface $retailer)
    {
        return $this->sellerRepository->delete($retailer);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByIdentifier($retailerId)
    {
        return $this->sellerRepository->deleteByIdentifier($retailerId);
    }
}
