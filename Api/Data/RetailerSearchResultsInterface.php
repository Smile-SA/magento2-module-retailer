<?php

declare(strict_types=1);

namespace Smile\Retailer\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\DataObject;

/**
 * @api
 */
interface RetailerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Retailers list.
     *
     * @return RetailerInterface[]|DataObject[]
     */
    public function getItems(): array;

    /**
     * Set Retailers list.
     *
     * @param RetailerInterface[]|DataObject[] $items List of retailers
     * @return $this
     */
    public function setItems(array $items): self;
}
