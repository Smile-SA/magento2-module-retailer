<?php

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
     * @return RetailerInterface[]
     */
    public function getItems(): array;

    /**
     * Set Retailers list.
     *
     * @param RetailerInterface[] $items List of retailers
     * @return $this
     */
    public function setItems(array $items): self;
}
