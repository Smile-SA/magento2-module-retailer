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

namespace Smile\Retailer\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;
use Smile\Seller\Api\Data\SellerInterface;

/**
 * Retailer Repository interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface RetailerRepositoryInterface
{
    /**
     * Create retailer service
     *
     * @param RetailerInterface $retailer The retailer
     *
     * @return RetailerInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(RetailerInterface $retailer);

    /**
     * Get info about retailer by retailer id
     *
     * @param int|string $retailerId The retailer Id
     * @param ?int       $storeId    The store Id
     *
     * @return RetailerInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int|string $retailerId, ?int $storeId = null);

    /**
     * Get info about retailer by retailer code
     *
     * @param string $retailerCode The retailer Code
     * @param ?int $storeId        The store Id
     *
     * @return RetailerInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByCode(string $retailerCode, ?int $storeId = null);

    /**
     * Get relation list
     *
     * @param SearchCriteriaInterface $criteria Search criterai for collection
     *
     * @return RetailerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): RetailerSearchResultsInterface;

    /**
     * Delete retailer by identifier
     *
     * @param RetailerInterface $retailer retailer which will deleted
     *
     * @return bool Will returned True if deleted
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(RetailerInterface $retailer): bool;

    /**
     * Delete retailer by identifier
     *
     * @param int $retailerId The retailer id
     *
     * @return bool Will returned True if deleted
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteByIdentifier(int $retailerId): bool;
}
