<?php

declare(strict_types=1);

namespace Smile\Retailer\Test\Unit\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Model\Retailer;
use Smile\Retailer\Plugin\Api\RetailerRepositoryPlugin;
use Smile\Seller\Model\ResourceModel\Seller as SellerResource;

/**
 * Non-admin web API callers only get active retailers.
 */
class RetailerRepositoryPluginTest extends TestCase
{
    private const RETAILER_SET_ID = 7;

    private UserContextInterface&Stub $userContext;
    private SellerResource&Stub $sellerResource;
    private StoreManagerInterface&Stub $storeManager;
    private RetailerRepositoryPlugin $plugin;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->userContext = $this->createStub(UserContextInterface::class);
        $this->sellerResource = $this->createStub(SellerResource::class);
        $this->sellerResource->method('getAttributeSetIdByName')->willReturnMap([['Retailer', self::RETAILER_SET_ID]]);
        $this->storeManager = $this->createStub(StoreManagerInterface::class);
        $this->plugin = new RetailerRepositoryPlugin($this->userContext, $this->sellerResource, $this->storeManager);
    }

    /**
     * A guest gets an active retailer.
     */
    public function testGuestGetsActiveRetailer(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);
        $retailer = $this->createRetailer(true, self::RETAILER_SET_ID);

        $this->assertSame($retailer, $this->callAfterGet($retailer));
    }

    /**
     * A guest cannot get an inactive retailer.
     */
    public function testGuestCannotGetInactiveRetailer(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);

        $this->expectException(NoSuchEntityException::class);
        $this->callAfterGet($this->createRetailer(false, self::RETAILER_SET_ID));
    }

    /**
     * A customer cannot get a seller that is not a retailer.
     */
    public function testCustomerCannotGetSellerOfAnotherAttributeSet(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_CUSTOMER);

        $this->expectException(NoSuchEntityException::class);
        $this->callAfterGet($this->createRetailer(true, 99));
    }

    /**
     * Admin users and integrations get any seller.
     */
    #[DataProvider('backendUserTypes')]
    public function testBackendCallerGetsAnySeller(int $userType): void
    {
        $this->givenUserType($userType);
        $retailer = $this->createRetailer(false, 99);

        $this->assertSame($retailer, $this->callAfterGet($retailer));
    }

    /**
     * Backend user types.
     */
    public static function backendUserTypes(): array
    {
        return [
            'admin' => [UserContextInterface::USER_TYPE_ADMIN],
            'integration' => [UserContextInterface::USER_TYPE_INTEGRATION],
        ];
    }

    /**
     * A non-admin caller reads the retailer as the current store view shows it, whatever store id it passes.
     */
    #[DataProvider('requestedStoreIds')]
    public function testNonAdminCallerIsScopedToCurrentStore(?int $requestedStoreId): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);
        $this->givenCurrentStoreId(3);

        $this->assertSame([5, 3], $this->callBeforeGet(5, $requestedStoreId));
    }

    /**
     * Store ids a caller may pass.
     */
    public static function requestedStoreIds(): array
    {
        return [
            'none' => [null],
            'admin store' => [0],
            'other store view' => [9],
        ];
    }

    /**
     * The "all" store code of the web API does not give a non-admin caller the admin scope.
     */
    public function testNonAdminCallerOnAllStoresPathGetsDefaultStoreView(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_CUSTOMER);
        $this->givenCurrentStoreId(0);
        $this->storeManager->method('getDefaultStoreView')->willReturn($this->createStore(1, true));

        $this->assertSame([5, 1], $this->callBeforeGet(5, 0));
    }

    /**
     * Without a default store view a non-admin caller on the "all" store code gets nothing.
     */
    public function testNonAdminCallerOnAllStoresPathWithoutDefaultStoreViewGetsNoRetailer(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);
        $this->givenCurrentStoreId(0);
        $this->storeManager->method('getDefaultStoreView')->willReturn(null);

        $this->expectException(NoSuchEntityException::class);
        $this->callBeforeGet(5, null);
    }

    /**
     * A disabled store view is not a scope a non-admin caller can read retailers in.
     */
    public function testNonAdminCallerOnDisabledStoreViewGetsNoRetailer(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);
        $this->givenCurrentStoreId(3, false);

        $this->expectException(NoSuchEntityException::class);
        $this->callBeforeGet(5, null);
    }

    /**
     * A retailer active in the requested store but inactive in the current one stays hidden.
     */
    public function testRetailerInactiveInCurrentStoreIsHiddenWhateverStoreIsRequested(): void
    {
        $this->givenUserType(UserContextInterface::USER_TYPE_GUEST);
        $this->givenCurrentStoreId(3);
        $repository = $this->createStub(RetailerRepositoryInterface::class);
        $repository->method('get')->willReturnMap([
            [5, 3, $this->createRetailer(false, self::RETAILER_SET_ID)],
            [5, 9, $this->createRetailer(true, self::RETAILER_SET_ID)],
        ]);

        $arguments = $this->plugin->beforeGet($repository, 5, 9) ?? [5, 9];

        $this->expectException(NoSuchEntityException::class);
        $this->plugin->afterGet($repository, $repository->get(...$arguments), ...$arguments);
    }

    /**
     * Admin users and integrations keep the store id they ask for.
     */
    #[DataProvider('backendUserTypes')]
    public function testBackendCallerKeepsRequestedStore(int $userType): void
    {
        $this->givenUserType($userType);
        $this->givenCurrentStoreId(3);

        $this->assertNull($this->callBeforeGet(5, 0));
    }

    /**
     * Set the store the web API request runs in.
     */
    private function givenCurrentStoreId(int $storeId, bool $isActive = true): void
    {
        $this->storeManager->method('getStore')->willReturn($this->createStore($storeId, $isActive));
    }

    /**
     * Create a store stub.
     */
    private function createStore(int $storeId, bool $isActive): StoreInterface&Stub
    {
        $store = $this->createStub(StoreInterface::class);
        $store->method('getId')->willReturn((string) $storeId);
        $store->method('getIsActive')->willReturn((int) $isActive);

        return $store;
    }

    /**
     * Call the before plugin as the interceptor would.
     */
    private function callBeforeGet(int $retailerId, ?int $storeId): ?array
    {
        return $this->plugin->beforeGet(
            $this->createStub(RetailerRepositoryInterface::class),
            $retailerId,
            $storeId
        );
    }

    /**
     * Set the web API caller type.
     */
    private function givenUserType(int $userType): void
    {
        $this->userContext->method('getUserType')->willReturn($userType);
    }

    /**
     * Create a retailer stub.
     */
    private function createRetailer(bool $isActive, int $attributeSetId): Retailer&Stub
    {
        $retailer = $this->createStub(Retailer::class);
        $retailer->method('getIsActive')->willReturn($isActive);
        $retailer->method('getAttributeSetName')->willReturn('Retailer');
        $retailer->method('getData')->willReturnMap([['attribute_set_id', null, (string) $attributeSetId]]);

        return $retailer;
    }

    /**
     * Call the plugin as the repository would.
     */
    private function callAfterGet(Retailer $retailer): Retailer
    {
        return $this->plugin->afterGet($this->createStub(RetailerRepositoryInterface::class), $retailer, 1);
    }
}
