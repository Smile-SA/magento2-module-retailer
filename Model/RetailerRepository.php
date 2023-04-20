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
namespace Smile\Retailer\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterfaceFactory;
use Smile\Retailer\Model\ResourceModel\Retailer\Collection;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;
use Smile\Seller\Api\Data\SellerInterface;
use Smile\Seller\Api\Data\SellerInterfaceFactory;
use Smile\Seller\Model\SellerRepository;
use Smile\Seller\Model\SellerRepositoryFactory;

/**
 * Retailer Repository
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RetailerRepository implements RetailerRepositoryInterface
{
    /**
     * @var SellerRepository
     */
    private SellerRepository $sellerRepository;

    /**
     * @var RetailerSearchResultsInterfaceFactory
     */
    private RetailerSearchResultsInterfaceFactory $searchResultFactory;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private CollectionProcessorInterface $collectionProcessor;

    /**
     * @var JoinProcessorInterface
     */
    private JoinProcessorInterface $joinProcessor;

    /**
     * @var SellerRepositoryFactory
     */
    private SellerRepositoryFactory $sellerRepositoryFactory;

    /**
     * @var RetailerInterfaceFactory
     */
    private RetailerInterfaceFactory $retailerFactory;

    /**
     * Constructor.
     *
     * @param SellerRepositoryFactory               $sellerRepositoryFactory Seller repository.
     * @param RetailerInterfaceFactory              $retailerFactory         Retailer factory.
     * @param RetailerSearchResultsInterfaceFactory $searchResultFactory     Search Result factory.
     * @param CollectionFactory                     $collectionFactory       Collection factory.
     * @param CollectionProcessorInterface          $collectionProcessor     Search criteria collection processor.
     * @param JoinProcessorInterface                $joinProcessor           Extension attriubute join processor.
     */
    public function __construct(
        SellerRepositoryFactory $sellerRepositoryFactory,
        RetailerInterfaceFactory $retailerFactory,
        RetailerSearchResultsInterfaceFactory $searchResultFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $joinProcessor
    ) {
        $this->sellerRepository = $sellerRepositoryFactory->create([
            'sellerFactory'    => $retailerFactory,
            'attributeSetName' => RetailerInterface::ATTRIBUTE_SET_RETAILER,
        ]);

        $this->searchResultFactory = $searchResultFactory;
        $this->collectionFactory   = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->joinProcessor       = $joinProcessor;
    }

    /**
     * {@inheritDoc}
     */
    public function save(SellerInterface $retailer)
    {
        return $this->sellerRepository->save($retailer);
    }

    /**
     * {@inheritDoc}
     */
    public function get(int|string $retailerId, ?int $storeId = null)
    {
        return $this->sellerRepository->get($retailerId, $storeId);
    }

    /**
     * {@inheritDoc}
     */
    public function getByCode(string $retailerCode, ?int $storeId = null)
    {
        return $this->sellerRepository->getByCode($retailerCode, $storeId);
    }

    /**
     * Search for retailers.
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria
     *
     * @return RetailerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): RetailerSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->joinProcessor->process($collection);
        $collection->addAttributeToSelect('*');
        $this->collectionProcessor->process($searchCriteria, $collection);

        // Add filters from root filter group to the collection.
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        /** @var SortOrder $sortOrder */
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(SellerInterface $retailer): bool
    {
        return $this->sellerRepository->delete($retailer);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteByIdentifier(int $retailerId): bool
    {
        return $this->sellerRepository->deleteByIdentifier($retailerId);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup   $filterGroup Filter Group
     * @param Collection    $collection  Retailer collection
     *
     * @return void
     */
    protected function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection): void
    {
        $fields = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ? $filter->getConditionType() : 'eq';

            $fields[] = ['attribute' => $filter->getField(), $conditionType => $filter->getValue()];
        }

        if ($fields) {
            $collection->addFieldToFilter($fields);
        }
    }
}
