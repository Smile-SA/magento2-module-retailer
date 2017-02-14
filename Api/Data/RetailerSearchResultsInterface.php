<?php

namespace Smile\Retailer\Api\Data;

/**
 * Class RetailerSearchResultsInterface
 *
 * @category Smile
 * @package  Smile\Retailer\Api\Data
 * @author   de Cramer Oliver<oldec@smile.fr>
 */
interface RetailerSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return RetailerInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param RetailerInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
