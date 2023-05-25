<?php

namespace Smile\Retailer\Block\Adminhtml\Retailer\Edit\Button;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Smile\Retailer\Api\Data\RetailerInterface;

/**
 * Abstract Retailer edit button.
 */
class AbstractButton implements ButtonProviderInterface
{
    public function __construct(
        protected Context $context,
        protected Registry $registry
    ) {
    }

    /**
     * Generate url by route and parameters.
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrl($route, $params);
    }

    /**
     * Get retailer
     */
    public function getRetailer(): ?RetailerInterface
    {
        return $this->registry->registry('current_seller');
    }

    /**
     * @inheritdoc
     */
    public function getButtonData()
    {
        return [];
    }
}
