<?php

namespace Smile\Retailer\Model\ResourceModel\Retailer;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\EntityFactory as EavEntityFactory;
use Magento\Eav\Model\ResourceModel\Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Validator\UniversalFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Model\Retailer;
use Smile\Seller\Model\ResourceModel\Seller as SellerResource;
use Smile\Seller\Model\ResourceModel\Seller\Collection as SellerResourceModelCollection;

/**
 * Retailers Collection.
 */
class Collection extends SellerResourceModelCollection
{
    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        ResourceConnection $resource,
        EavEntityFactory $eavEntityFactory,
        Helper $resourceHelper,
        StoreManagerInterface $storeManager,
        UniversalFactory $universalFactory,
        ?AdapterInterface $connection = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $eavConfig,
            $resource,
            $eavEntityFactory,
            $resourceHelper,
            $storeManager,
            $universalFactory,
            $connection,
            RetailerInterface::ATTRIBUTE_SET_RETAILER
        );
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Retailer::class, SellerResource::class);

        if ($this->sellerAttributeSetId == null) {
            if ($this->sellerAttributeSetName !== null) {
                $this->sellerAttributeSetId = $this->getResource()
                    ->getAttributeSetIdByName($this->sellerAttributeSetName);
            }
        }
    }
}
