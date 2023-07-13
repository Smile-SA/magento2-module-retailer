<?php

declare(strict_types=1);

namespace Smile\Retailer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;
use Smile\Seller\Api\Data\SellerInterface;

/**
 * @api
 */
interface RetailerRepositoryInterface
{
    /**
     * Create retailer service
     *
     * @param RetailerInterface|SellerInterface $retailer The retailer
     * @return RetailerInterface|SellerInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(RetailerInterface|SellerInterface $retailer);

    /**
     * Get info about retailer by retailer id
     *
     * @param mixed $retailerId The retailer Id
     * @param ?int  $storeId    The store Id
     * @return RetailerInterface|SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(mixed $retailerId, ?int $storeId = null);

    /**
     * Get info about retailer by retailer code
     *
     * @param string $retailerCode The retailer Code
     * @param ?int $storeId        The store Id
     * @return RetailerInterface|SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCode(string $retailerCode, ?int $storeId = null);

    /**
     * Get relation list
     *
     * @param SearchCriteriaInterface $criteria Search criterai for collection
     * @return RetailerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): RetailerSearchResultsInterface;

    /**
     * Delete retailer by identifier
     *
     * @param RetailerInterface|SellerInterface $retailer retailer which will deleted
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(RetailerInterface|SellerInterface $retailer): bool;

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
