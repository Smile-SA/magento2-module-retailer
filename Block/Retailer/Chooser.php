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

namespace Smile\Retailer\Block\Retailer;

use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Smile\Retailer\Api\RetailerScheduleManagementInterface;
use Smile\Seller\Model\ResourceModel\Seller\CollectionFactory;
use Smile\Retailer\Helper\Settings as SettingsHelper;

/**
 * Retailer Picker component
 *
 * @category Smile
 * @package  Smile\Retailer
 * @author   Romain Ruaud <romain.ruaud@smile.fr>
 */
class Chooser extends Template
{
    /**
     * Javascript component path.
     */
    const JS_COMPONENT = "Smile_Retailer/js/retailer/chooser";

    /**
     * Javascript template path.
     */
    const JS_TEMPLATE  = "Smile_Retailer/retailer/chooser";

    /**
     * Default number of days (interval) to retrieve dates for.
     */
    const DEFAULT_NUMBER_OF_DAY = '60';

    /**
     * The Data role, used for Javascript mapping of slider Widget
     *
     * @var string
     */
    private $dataScope = "retailer-pickup";

    /**
     * @var \Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory
     */
    private $retailerCollectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var RetailerScheduleManagementInterface
     */
    private $scheduleManagement;

    /**
     * @var array
     */
    private $openingDaysCache = [];

    /**
     * @var SettingsHelper
     */
    private $settingsHelper;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * Block Constructor
     *
     * @param Context                             $context                   Template context.
     * @param EncoderInterface                    $jsonEncoder               JSON Encoder.
     * @param CollectionFactory                   $retailerCollectionFactory Retailer Collection Factory
     * @param RetailerScheduleManagementInterface $scheduleManagement        Retailer Schedule Manager
     * @param \Magento\Framework\Stdlib\DateTime  $datetime                  DateTime Manager
     * @param SettingsHelper                      $settingsHelper            Retailer Settings Helper.
     * @param array                               $data                      Custom data.
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        CollectionFactory $retailerCollectionFactory,
        RetailerScheduleManagementInterface $scheduleManagement,
        \Magento\Framework\Stdlib\DateTime $datetime,
        SettingsHelper $settingsHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->jsonEncoder               = $jsonEncoder;
        $this->retailerCollectionFactory = $retailerCollectionFactory;
        $this->scheduleManagement        = $scheduleManagement;
        $this->dateTime                  = $datetime;
        $this->settingsHelper            = $settingsHelper;
    }

    /**
     * Return the config of the price slider JS widget.
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = [
            'component'         => self::JS_COMPONENT,
            'template'          => self::JS_TEMPLATE,
            'stores'            => $this->getStoresConfig(),
            'updateUrl'         => $this->getUpdateUrl(),
            'displayPickupDate' => $this->isPickupDateDisplayed(),
        ];

        return $this->jsonEncoder->encode([$this->getDataScope() => $config]);
    }

    /**
     * Retrieve the data role
     *
     * @return string
     */
    public function getDataScope()
    {
        return $this->dataScope;
    }

    /**
     * Retrieve the minimum Opening Date
     *
     * @return \DateTime
     */
    public function getMinDate()
    {
        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString('+1 day'));

        return $date;
    }

    /**
     * Retrieve the maximum Opening Date for a Seller
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $seller The Seller
     *
     * @return \DateTime
     */
    public function getMaxDate($seller)
    {
        $date = $this->getMinDate($seller);
        $date->add(\DateInterval::createFromDateString(sprintf('+%s day', self::DEFAULT_NUMBER_OF_DAY)));

        return $date;
    }

    /**
     * Check if a date is valid for a seller.
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $seller The Seller
     * @param \DateTime                              $date   The date
     *
     * @return bool
     */
    public function isValidDate($seller, $date)
    {
        $openingDays = $this->getOpeningDays($seller);

        return in_array($date->format('w'), $openingDays);
    }

    /**
     * Retrive the opening days of a seller
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $seller The Seller
     *
     * @return mixed
     */
    public function getOpeningDays($seller)
    {
        if (!isset($this->openingDaysCache[$seller->getId()])) {
            $this->scheduleManagement->loadOpeningHours($seller);
            $this->openingDaysCache[$seller->getId()] = [];
            foreach ($seller->getExtensionAttributes()->getOpeningHours()->getTimeRanges() as $dayData) {
                if (isset($dayData['time_ranges'])) {
                    $this->openingDaysCache[$seller->getId()][] = $dayData['date'];
                }
            }
        }

        return $this->openingDaysCache[$seller->getId()];
    }

    /**
     * Retrieve Retailer Update Url
     *
     * @return string
     */
    protected function getUpdateUrl()
    {
        return $this->getUrl('retailer/retailer/set');
    }

    /**
     * Retrieve Retailers Data
     *
     * @return array
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getStoresConfig()
    {
        $retailers = [];
        /** @var \Smile\Seller\Model\ResourceModel\Seller\Collection $retailerCollection */
        $retailerCollection = $this->retailerCollectionFactory->create(['attributeSetId' => 'Retailer']);
        $retailerCollection->addAttributeToSelect(['name']);
        $retailerCollection->addAttributeToFilter('is_active', true);

        foreach ($retailerCollection as $retailer) {
            $retailers[$retailer->getId()] = ['name' => $retailer->getName()];

            if ($this->isPickupDateDisplayed()) {
                $retailers[$retailer->getId()]['calendar'] = $this->getCalendar($retailer);
            }
        }

        return $retailers;
    }

    /**
     * Retrieve all Opening Dates of a given Seller.
     *
     * @param \Smile\Seller\Api\Data\SellerInterface $seller The Seller
     *
     * @return array
     */
    private function getCalendar($seller)
    {
        $calendar = [];
        $date = $this->getMinDate($seller);

        while ($date < $this->getMaxDate($seller)) {
            $date->add(\DateInterval::createFromDateString('+1 day'));
            if ($this->isValidDate($seller, $date)) {
                $calendar[] = $this->dateTime->formatDate($date, false);
            }
        }

        return $calendar;
    }

    /**
     * Check if we should display the pickup date.
     *
     * @return bool
     */
    private function isPickupDateDisplayed()
    {
        return $this->settingsHelper->isPickupDateDisplayed();
    }
}
