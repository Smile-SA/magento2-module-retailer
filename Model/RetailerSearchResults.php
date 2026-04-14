<?php

declare(strict_types=1);

namespace Smile\Retailer\Model;

use Magento\Framework\Api\SearchResults;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;

class RetailerSearchResults extends SearchResults implements RetailerSearchResultsInterface
{
}
