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
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Smile\Retailer\Api\RetailerRepositoryInterface;
use Smile\Seller\Model\SellerFactory;
use Smile\Retailer\Model\Retailer\OpeningHoursFactory;

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
     * @var \Smile\Retailer\Model\Retailer\OpeningHoursFactory|null
     */
    protected $openingHoursFactory = null;

    /**
     * Core registry
     *
     * @var Registry
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
     * @param Context                     $context              Application context
     * @param PageFactory                 $resultPageFactory    Result Page factory
     * @param ForwardFactory              $resultForwardFactory Result forward factory
     * @param Registry                    $coreRegistry         Application registry
     * @param RetailerRepositoryInterface $retailerRepository   Retailer Repository
     * @param SellerFactory               $retailerFactory      Retailer Factory
     * @param OpeningHoursFactory         $openingHoursFactory  Opening Hours Factory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Registry $coreRegistry,
        RetailerRepositoryInterface $retailerRepository,
        SellerFactory $retailerFactory,
        OpeningHoursFactory $openingHoursFactory
    ) {
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->coreRegistry         = $coreRegistry;
        $this->retailerRepository   = $retailerRepository;
        $this->retailerFactory      = $retailerFactory;
        $this->openingHoursFactory  = $openingHoursFactory;

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
        return $this->_authorization->isAllowed('Smile_Retailer::manage');
    }
}
