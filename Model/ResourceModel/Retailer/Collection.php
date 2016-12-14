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

use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * Retailers Collection
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName) The properties are inherited
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Collection extends \Smile\Seller\Model\ResourceModel\Seller\Collection
{
    /**
     * Collection constructor.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList) Parent construct already has 10 arguments.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactory             $entityFactory    Entity Factory
     * @param \Psr\Log\LoggerInterface                                     $logger           Logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy    Fetch Strategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager     Event Manager
     * @param \Magento\Eav\Model\Config                                    $eavConfig        EAV Config
     * @param \Magento\Framework\App\ResourceConnection                    $resource         Resource Connection
     * @param \Magento\Eav\Model\EntityFactory                             $eavEntityFactory EAV Entity Factory
     * @param \Magento\Eav\Model\ResourceModel\Helper                      $resourceHelper   Resource Helper
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager     The Store Manager
     * @param \Magento\Framework\Validator\UniversalFactory                $universalFactory Universal Factory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null          $connection       Database Connection
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null
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
    protected function _construct()
    {
        $this->_init('Smile\Retailer\Model\Retailer', 'Smile\Seller\Model\ResourceModel\Seller');

        if ($this->sellerAttributeSetId == null) {
            if ($this->sellerAttributeSetName !== null) {
                $this->sellerAttributeSetId = $this->getResource()->getAttributeSetIdByName($this->sellerAttributeSetName);
            }
        }
    }
}
