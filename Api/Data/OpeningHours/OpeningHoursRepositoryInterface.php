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

namespace Smile\Retailer\Api\Data\OpeningHours;

/**
 * Opening Hours Repository Interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface OpeningHoursRepositoryInterface
{
    /**
     * Get opening hours list for a given retailer
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return mixed
     */
    public function getList($retailer);

    /**
     * Retrieve opening hours for a given retailer
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer The retailer
     *
     * @return array
     */
    public function getByRetailer($retailer);

    /**
     * Save opening hours list for a given retailer Id
     *
     * @param integer $retailerId   The retailer Id
     * @param array   $openingHours The Opening Hours
     *
     * @return mixed
     */
    public function save($retailerId, $openingHours);

    /**
     * Delete opening hours list for a given retailer Id
     *
     * @param integer $retailerId The retailer Id
     *
     * @return mixed
     */
    public function deleteByRetailerId($retailerId);
}
