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
namespace Smile\Retailer\Block\Retailer\Pickup;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Retailer + Date picker
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Wrapper extends Template
{
    /**
     * @var Template The retailer picker child block
     */
    private $retailerPicker = null;

    /**
     * @var Template The date picker child block
     */
    private $datePicker = null;

    /**
     * @var string
     */
    protected $_template = "pickup/wrapper.phtml";

    /**
     * Wrapper constructor. Needs a retailer picker child block, and a date picker child block.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context Application context
     * @param array                                            $data    Constructor Data
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        if (!isset($data['retailer_picker'])) {
            throw new \InvalidArgumentException("Cannot instantiate block without retailer picker child.");
        }

        if (!isset($data['date_picker'])) {
            throw new \InvalidArgumentException("Cannot instantiate block without date picker child.");
        }
    }

    /**
     * Get Retailer Picker Child Block
     *
     * @return Template
     */
    public function getRetailerPicker()
    {
        return $this->retailerPicker;
    }

    /**
     * Get Date Picker Child Block
     *
     * @return Template
     */
    public function getDatePicker()
    {
        return $this->datePicker;
    }


    /**
     * Retrieve child blocks before rendering
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName) Method is inherited
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->retailerPicker = $this->getChildBlock($this->getData('retailer_picker'));
        $this->datePicker     = $this->getChildBlock($this->getData('date_picker'));

        return parent::_beforeToHtml();
    }
}
