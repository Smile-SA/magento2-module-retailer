<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Retailer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
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
use Smile\Seller\Model\ResourceModel\Seller\Collection as SellerResourceModelCollection;

/**
 * Retailers Collection
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName) The properties are inherited
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Collection extends SellerResourceModelCollection
{
    /**
     * Collection constructor.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList) Parent construct already has 10 arguments.
     *
     * @param EntityFactory             $entityFactory    Entity Factory
     * @param LoggerInterface           $logger           Logger
     * @param FetchStrategyInterface    $fetchStrategy    Fetch Strategy
     * @param ManagerInterface          $eventManager     Event Manager
     * @param Config                    $eavConfig        EAV Config
     * @param ResourceConnection        $resource         Resource Connection
     * @param EavEntityFactory          $eavEntityFactory EAV Entity Factory
     * @param Helper                    $resourceHelper   Resource Helper
     * @param StoreManagerInterface     $storeManager     The Store Manager
     * @param UniversalFactory          $universalFactory Universal Factory
     * @param AdapterInterface|null     $connection       Database Connection
     */
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
        AdapterInterface $connection = null
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
     * Init collection and determine table names
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) The method is inherited
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('Smile\Retailer\Model\Retailer', 'Smile\Seller\Model\ResourceModel\Seller');

        if ($this->sellerAttributeSetId == null) {
            if ($this->sellerAttributeSetName !== null) {
                $this->sellerAttributeSetId = $this->getResource()->getAttributeSetIdByName($this->sellerAttributeSetName);
            }
        }
    }
}
