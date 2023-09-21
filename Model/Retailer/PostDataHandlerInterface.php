<?php

declare(strict_types=1);

namespace Smile\Retailer\Model\Retailer;

use Exception;
use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * Form data handler interface.
 */
interface PostDataHandlerInterface
{
    /**
     * Process form data.
     *
     * @throws Exception
     */
    public function getData(RetailerInterface $retailer, mixed $data): mixed;
}
