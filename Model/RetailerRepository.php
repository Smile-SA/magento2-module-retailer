<?php

namespace Smile\Retailer\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Smile\Retailer\Api\Data\RetailerInterface;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterface;
use Smile\Retailer\Api\Data\RetailerSearchResultsInterfaceFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\Collection;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;
use Smile\Seller\Api\Data\SellerInterface;
use Smile\Seller\Model\SellerRepository;
use Smile\Seller\Model\SellerRepositoryFactory;

/**
 * Retailer Repository implementation.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RetailerRepository implements RetailerRepositoryInterface
{
    private SellerRepository $sellerRepository;
    private SellerRepositoryFactory $sellerRepositoryFactory;
    private RetailerInterfaceFactory $retailerFactory;

    public function __construct(
        SellerRepositoryFactory $sellerRepositoryFactory,
        RetailerInterfaceFactory $retailerFactory,
        private RetailerSearchResultsInterfaceFactory $searchResultFactory,
        private CollectionFactory $collectionFactory,
        private CollectionProcessorInterface $collectionProcessor,
        private JoinProcessorInterface $joinProcessor
    ) {
        $this->sellerRepository = $sellerRepositoryFactory->create([
            'sellerFactory' => $retailerFactory,
            'attributeSetName' => RetailerInterface::ATTRIBUTE_SET_RETAILER,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function save(SellerInterface $retailer)
    {
        return $this->sellerRepository->save($retailer);
    }

    /**
     * @inheritdoc
     */
    public function get(int|string $retailerId, ?int $storeId = null)
    {
        return $this->sellerRepository->get($retailerId, $storeId);
    }

    /**
     * @inheritdoc
     */
    public function getByCode(string $retailerCode, ?int $storeId = null)
    {
        return $this->sellerRepository->getByCode($retailerCode, $storeId);
    }

    /**
     * @inheritdoc
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
                $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'ASC' : 'DESC'
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
     * @inheritdoc
     */
    public function delete(SellerInterface $retailer): bool
    {
        return $this->sellerRepository->delete($retailer);
    }

    /**
     * @inheritdoc
     */
    public function deleteByIdentifier(int $retailerId): bool
    {
        return $this->sellerRepository->deleteByIdentifier($retailerId);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
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
