<?php

declare(strict_types=1);

namespace Smile\Retailer\Controller\Adminhtml;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;

/**
 * Abstract Retailer controller.
 *
 * @method mixed getData(...$key)
 * @method mixed setData(...$data)
 */
abstract class AbstractRetailer extends Action
{
    public function __construct(
        Context $context,
        protected PageFactory $resultPageFactory,
        protected ForwardFactory $resultForwardFactory,
        protected Registry $coreRegistry,
        protected RetailerRepositoryInterface $retailerRepository,
        protected RetailerInterfaceFactory $retailerFactory,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Create result page.
     */
    protected function createPage(): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Smile_Retailer::retailers')
            ->addBreadcrumb(__('Sellers'), __('Retailers'));

        return $resultPage;
    }

    /**
     * @inheritdoc
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Smile_Retailer::retailers');
    }

    /**
     * Get all selected ids.
     */
    protected function getAllSelectedIds(): array
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $retailerIds = $collection->getAllIds();
        } catch (Exception) {
            $retailerIds = [];
        }

        return $retailerIds;
    }
}
