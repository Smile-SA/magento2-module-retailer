<?php

declare(strict_types=1);

namespace Smile\Retailer\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Api\Data\SellerInterface;
use Smile\Seller\Model\ResourceModel\Seller as SellerResource;

/**
 * Hide inactive retailers, and sellers that are not retailers, from guests and customers (storefront and web API).
 */
class RetailerRepositoryPlugin
{
    public function __construct(
        private UserContextInterface $userContext,
        private SellerResource $sellerResource,
        private StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Load the retailer as the current store view shows it when the caller is not an admin.
     *
     * Retailer attributes, is_active included, are store scoped.
     *
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeGet(
        RetailerRepositoryInterface $subject,
        int $retailerId,
        ?int $storeId = null
    ): ?array {
        if ($this->isBackendCaller()) {
            return null;
        }

        $storefrontStoreId = $this->getStorefrontStoreId();

        if ($storefrontStoreId === null) {
            throw NoSuchEntityException::singleField('retailerId', $retailerId);
        }

        return [$retailerId, $storefrontStoreId];
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
        if ($this->isBackendCaller()) {
            return $result;
        }

        $retailerAttributeSetId = $this->sellerResource->getAttributeSetIdByName($result->getAttributeSetName());
        $isRetailer = (int) $result->getData('attribute_set_id') === $retailerAttributeSetId;

        if (!$isRetailer || !$result->getIsActive()) {
            throw NoSuchEntityException::singleField('retailerId', $retailerId);
        }

        return $result;
    }

    /**
     * Whether the web API caller is an admin user or an integration.
     */
    private function isBackendCaller(): bool
    {
        return in_array(
            $this->userContext->getUserType(),
            [UserContextInterface::USER_TYPE_ADMIN, UserContextInterface::USER_TYPE_INTEGRATION],
            true
        );
    }

    /**
     * Current store view, or the default store view when the request runs in the admin scope (the "all" store code).
     *
     * Null when that store view is disabled or cannot be resolved: the storefront serves no such scope.
     */
    private function getStorefrontStoreId(): ?int
    {
        $store = $this->storeManager->getStore();

        if ((int) $store->getId() === Store::DEFAULT_STORE_ID) {
            $store = $this->storeManager->getDefaultStoreView();
        }

        if ($store === null || !$store->getIsActive()) {
            return null;
        }

        return (int) $store->getId();
    }
}
