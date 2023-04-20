<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    De Cramer Oliver <oldec@smile.fr>
 * @copyright 2017 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\Retailer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Class RetailerSearchResultsInterface
 *
 * @category Smile
 * @package  Smile\Retailer\Api\Data
 * @author   De Cramer Oliver <oldec@smile.fr>
 */
interface RetailerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Retailers list.
     *
     * @return RetailerInterface[]
     */
    public function getItems(): array;

    /**
     * Set Retailers list.
     *
     * @param RetailerInterface[] $items List of retailers
     *
     * @return $this
     */
    public function setItems(array $items): self;
}
