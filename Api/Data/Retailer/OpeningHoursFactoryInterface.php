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

namespace Smile\Retailer\Api\Data\Retailer;

/**
 * Opening Hours Factory Interface
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
interface OpeningHoursFactoryInterface
{
    /**
     * Create Opening Hours class instance with specified parameters
     *
     * @param array $data The Model Data
     *
     * @return \Smile\Retailer\Api\Data\OpeningHoursInterface
     */
    public function create(array $data = []);
}
