<?php

declare(strict_types=1);

namespace Smile\Retailer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface RetailerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Retailers list.
     *
     * @return \Smile\Retailer\Api\Data\RetailerInterface[]
     */
    public function getItems();

    /**
     * Set Retailers list.
     *
     * @param \Smile\Retailer\Api\Data\RetailerInterface[] $items List of retailers
     * @return $this
     */
    public function setItems(array $items);
}
