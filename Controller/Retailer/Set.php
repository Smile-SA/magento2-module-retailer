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
namespace Smile\Retailer\Controller\Retailer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Smile\Retailer\CustomerData\RetailerData;

/**
 * Frontend Controller meant to set current Retailer to customer session
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Set extends Action
{
    /**
     * @var \Smile\Retailer\CustomerData\RetailerData;
     */
    private $retailerData;

    /**
     * Set constructor.
     *
     * @param \Magento\Framework\App\Action\Context     $context      Application context
     * @param \Smile\Retailer\CustomerData\RetailerData $retailerData Retailer data management.
     */
    public function __construct(Context $context, RetailerData $retailerData)
    {
        parent::__construct($context);
        $this->retailerData = $retailerData;
    }

    /**
     * Dispatch request. Will bind submitted retailer id (if any) to current customer session
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $retailerId = $this->getRequest()->getParam("retailer_id", false);
        $pickupDate = $this->getRequest()->getParam("pickup_date", false);

        try {
            $this->retailerData->setParams($retailerId, $pickupDate);
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __("We are sorry, an error occured when switching retailer."));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }
}
