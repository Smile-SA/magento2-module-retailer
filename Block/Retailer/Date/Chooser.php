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
namespace Smile\Retailer\Block\Retailer\Date;

use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\View\Element\Template\Context;

/**
 * Date picker for retailer
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Chooser extends \Magento\Framework\View\Element\Template
{
    /**
     * @var null|\Magento\Framework\View\Element\Html\Date
     */
    private $datePicker = null;

    /**
     * @var array Configuration for the input, can be passed by layouting.
     */
    private $inputData = [];

    /**
     * Chooser constructor. Iniitializes the retailer collection
     *
     * @param \Magento\Framework\View\Element\Template\Context $context Application context
     * @param array                                            $data    Constructor Data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        if (isset($data['input']) && !empty($data['input'])) {
            $this->inputData = array_merge($data['input'], $this->inputData);
        }
    }

    /**
     * Retrieve DatePicker element
     *
     * @return \Magento\Framework\View\Element\Html\Date
     */
    public function getDatePicker()
    {
        if (null === $this->datePicker) {
            $this->datePicker = $this->getLayout()->createBlock('Magento\Framework\View\Element\Html\Date')
                ->setData($this->inputData);
            $this->datePicker->setFormat($this->_localeDate->getDateFormatWithLongYear());
            $this->datePicker->setDateFormat($this->_localeDate->getDateFormatWithLongYear());
        }

        return $this->datePicker;
    }

    /**
     * @return string
     */
    public function getDatePickerHtml()
    {
        return $this->getDatepicker()->getHtml();
    }

    /**
     * Retrieve Html Id of the chooser input
     *
     * @return string
     */
    public function getInputId()
    {
        return $this->getDatePicker()->getId();
    }
}
