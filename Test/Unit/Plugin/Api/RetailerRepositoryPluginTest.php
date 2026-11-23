<?php

declare(strict_types=1);

namespace Smile\Retailer\Test\Unit\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Exception\NoSuchEntityException;
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
    private RetailerRepositoryPlugin $plugin;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->userContext = $this->createStub(UserContextInterface::class);
        $this->sellerResource = $this->createStub(SellerResource::class);
        $this->sellerResource->method('getAttributeSetIdByName')->willReturnMap([['Retailer', self::RETAILER_SET_ID]]);
        $this->plugin = new RetailerRepositoryPlugin($this->userContext, $this->sellerResource);
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
