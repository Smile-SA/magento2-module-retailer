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
use Magento\Framework\View\Result\PageFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Model\SellerFactory;

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
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var RetailerRepositoryInterface
     */
    protected $retailerRepository;

    /**
     * Retailer Factory
     *
     * @var SellerFactory
     */
    protected $retailerFactory;

    /**
     * Abstract constructor.
     *
     * @param \Magento\Backend\App\Action\Context        $context             Application context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory   Tesult Page factory
     * @param \Magento\Framework\Registry                $coreRegistry        Application registry
     * @param RetailerRepositoryInterface                $retailerRepository  Retailer Repository
     * @param SellerFactory                              $retailerFactory     Retailer Factory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        SellerFactory $retailerFactory
    ) {
        $this->resultPageFactory   = $resultPageFactory;
        $this->coreRegistry        = $coreRegistry;
        $this->retailerRepository  = $retailerRepository;
        $this->retailerFactory     = $retailerFactory;

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
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smile_Retailer::manage');
    }
}
