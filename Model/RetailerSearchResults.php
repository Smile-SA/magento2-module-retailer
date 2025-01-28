<?php

declare(strict_types=1);

namespace Smile\Retailer\Model;

use Magento\Framework\Api\SearchResults;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;

/**
 * Service Data Object with retailer search results.
 */
class RetailerSearchResults extends SearchResults implements RetailerSearchResultsInterface
{
}
