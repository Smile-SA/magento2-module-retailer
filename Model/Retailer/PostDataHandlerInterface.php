<?php
/**
 * DISCLAIMER
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future.
*
* @category  Smile
* @package   Smile\Retailer
* @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
* @copyright 2016 Smile
* @license   Open Software License ("OSL") v. 3.0
*/
namespace Smile\Retailer\Model\Retailer;

/**
 * Form data handler interface.
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface PostDataHandlerInterface
{
    /**
     * Process form data.
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface $retailer Retailer.
     * @param mixed                                      $data     Original form data.
     *
     * @return mixed
     */
    public function getData(\Smile\Retailer\Api\Data\RetailerInterface $retailer, $data);
}
