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
     * @var \Magento\Framework\View\Result\PageFactory|null
     */
    protected $resultPageFactory = null;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory|null
     */
    protected $resultForwardFactory = null;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Smile\Retailer\Api\Data\RetailerInterfaceFactory
     */
    protected $retailerRepository;

    /**
     * Retailer Factory
     *
     * @var \Smile\Retailer\Api\Data\RetailerInterfaceFactory
     */
    protected $retailerFactory;

    /**
     * @var \Smile\Retailer\Api\RetailerScheduleManagementInterface
     */
    protected $scheduleManagement;

    /**
     * Abstract constructor.
     *
     * @param \Magento\Backend\App\Action\Context                 $context              Application context.
     * @param \Magento\Framework\View\Result\PageFactory          $resultPageFactory    Result Page factory.
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory Result forward factory.
     * @param \Magento\Framework\Registry                         $coreRegistry         Application registry.
     * @param \Smile\Retailer\Api\RetailerRepositoryInterface     $retailerRepository   Retailer Repository
     * @param \Smile\Retailer\Api\Data\RetailerInterfaceFactory   $retailerFactory      Retailer Factory.
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Smile\Retailer\Api\RetailerRepositoryInterface $retailerRepository,
        \Smile\Retailer\Api\Data\RetailerInterfaceFactory $retailerFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->retailerRepository   = $retailerRepository;
        $this->retailerFactory      = $retailerFactory;

        parent::__construct($context);
    }

    /**
     * Create result page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function createPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
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
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smile_Retailer::retailers');
    }
}
