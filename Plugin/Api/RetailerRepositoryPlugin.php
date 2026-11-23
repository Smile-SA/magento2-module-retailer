<?php

declare(strict_types=1);

namespace Smile\Retailer\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Api\Data\SellerInterface;
use Smile\Seller\Model\ResourceModel\Seller as SellerResource;

/**
 * Hide inactive retailers, and sellers that are not retailers, from anonymous and customer web API callers.
 */
class RetailerRepositoryPlugin
{
    public function __construct(
        private UserContextInterface $userContext,
        private SellerResource $sellerResource
    ) {
    }

    /**
     * Throw "no such entity" when a non-admin caller requests an inactive retailer or another kind of seller.
     *
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        RetailerRepositoryInterface $subject,
        SellerInterface $result,
        int $retailerId
    ): SellerInterface {
        $isBackendCaller = in_array(
            $this->userContext->getUserType(),
            [UserContextInterface::USER_TYPE_ADMIN, UserContextInterface::USER_TYPE_INTEGRATION],
            true
        );

        if ($isBackendCaller) {
            return $result;
        }

        $retailerAttributeSetId = $this->sellerResource->getAttributeSetIdByName($result->getAttributeSetName());
        $isRetailer = (int) $result->getData('attribute_set_id') === $retailerAttributeSetId;

        if (!$isRetailer || !$result->getIsActive()) {
            throw NoSuchEntityException::singleField('retailerId', $retailerId);
        }

        return $result;
    }
}
