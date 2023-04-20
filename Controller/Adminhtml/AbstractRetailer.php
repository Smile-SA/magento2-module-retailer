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
namespace Smile\Retailer\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Smile\Retailer\Api\Data\RetailerInterfaceFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Retailer\Api\RetailerScheduleManagementInterface;
use Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory;

/**
 * Abstract Retailer controller
 *
 * @category Smile
 * @package  Smile\ElasticsuiteRetailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
abstract class AbstractRetailer extends Action
{
    /**
     * @var ?PageFactory
     */
    protected ?PageFactory $resultPageFactory = null;

    /**
     * @var ?ForwardFactory
     */
    protected ?ForwardFactory $resultForwardFactory = null;

    /**
     * Core registry
     *
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var RetailerRepositoryInterface
     */
    protected RetailerRepositoryInterface $retailerRepository;

    /**
     * Retailer Factory
     *
     * @var RetailerInterfaceFactory
     */
    protected RetailerInterfaceFactory $retailerFactory;

    /**
     * @var RetailerScheduleManagementInterface
     */
    protected RetailerScheduleManagementInterface $scheduleManagement;

    /**
     * @var Filter
     */
    protected Filter $filter;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * Abstract constructor.
     *
     * @param Context                       $context              Application context.
     * @param PageFactory                   $resultPageFactory    Result Page factory.
     * @param ForwardFactory                $resultForwardFactory Result forward factory.
     * @param Registry                      $coreRegistry         Application registry.
     * @param RetailerRepositoryInterface   $retailerRepository   Retailer Repository
     * @param RetailerInterfaceFactory      $retailerFactory      Retailer Factory.
     * @param Filter                        $filter               Mass Action Filter.
     * @param CollectionFactory             $collectionFactory    Retailer collection for Mass Action.
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        RetailerInterfaceFactory $retailerFactory,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->retailerRepository   = $retailerRepository;
        $this->retailerFactory      = $retailerFactory;
        $this->filter               = $filter;
        $this->collectionFactory    = $collectionFactory;

        parent::__construct($context);
    }

    /**
     * Create result page
     *
     * @return Page
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
     * Check if allowed to manage retailer
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Smile_Retailer::retailers');
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    /**
     * @return array
     */
    protected function getAllSelectedIds(): array
    {
        $retailerIds = [];
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $retailerIds = $collection->getAllIds();
        } catch (\Exception $e) {
        }

        return $retailerIds;
    }
}
