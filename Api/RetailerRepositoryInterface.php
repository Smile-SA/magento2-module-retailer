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
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Smile\Retailer\Api\Data\RetailerInterface $retailer);

    /**
     * Get info about retailer by retailer id
     *
     * @param int $retailerId The retailer Id
     * @param int $storeId    The store Id
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($retailerId, $storeId = null);

    /**
     * Delete retailer by identifier
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer retailer which will deleted
     *
     * @return bool Will returned True if deleted
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(\Smile\Retailer\Api\Data\RetailerInterface $retailer);

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
    public function deleteByIdentifier($retailerId);
}
