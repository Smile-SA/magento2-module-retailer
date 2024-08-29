<?php

declare(strict_types=1);

namespace Smile\Retailer\Api;

use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;

/**
 * @api
 */
interface RetailerRepositoryInterface
{
    /**
     * Create retailer service
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface $retailer The retailer
     * @return \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface $retailer);

    /**
     * Get info about retailer by retailer id
     *
     * @param int $retailerId The retailer Id
     * @param ?int  $storeId    The store Id
     * @return \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $retailerId, ?int $storeId = null);

    /**
     * Get info about retailer by retailer code
     *
     * @param string $retailerCode The retailer Code
     * @param ?int $storeId        The store Id
     * @return \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCode(string $retailerCode, ?int $storeId = null);

    /**
     * Get relation list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria Search criterai for collection
     * @return RetailerSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria): RetailerSearchResultsInterface;

    /**
     * Delete retailer by identifier
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface $retailer to be deleted
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(
        \Smile\Retailer\Api\Data\RetailerInterface|\Smile\Seller\Api\Data\SellerInterface $retailer
    ): bool;

    /**
     * Delete retailer by identifier
     *
     * @param int $retailerId The retailer id
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteByIdentifier(int $retailerId): bool;
}
