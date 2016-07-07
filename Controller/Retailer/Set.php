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
use Magento\Customer\Model\Session as CustomerSession;

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
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * Set constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context         Application context
     * @param \Magento\Customer\Model\Session       $customerSession Customre Session
     */
    public function __construct(Context $context, CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;

        parent::__construct($context);
    }

    /**
     * Dispatch request. Will bind submitted retailer id (if any) to current customer session
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if ($this->getRequest()->isPost()) {
            $retailerId = $this->getRequest()->getParam("retailer_id", false);
            if ($retailerId) {
                $this->customerSession->setRetailerId($retailerId);
            }

            $pickupDate = $this->getRequest()->getParam("pickup_date", false);
            if ($pickupDate) {
                $this->customerSession->setRetailerPickupDate($pickupDate);
            }

            $this->getResponse()->sendResponse();
        }
    }
}
