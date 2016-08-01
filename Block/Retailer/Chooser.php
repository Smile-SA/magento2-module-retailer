<?php

namespace Smile\Retailer\Block\Retailer;

use Magento\Framework\Stdlib\DateTime;

class Chooser extends \Magento\Framework\View\Element\Template
{
    const JS_COMPONENT = "Smile_Retailer/js/retailer/chooser";

    const JS_TEMPLATE  = "Smile_Retailer/retailer/chooser";

    const DEFAULT_NUMBER_OF_DAY = '60';

    /**
     * The Data role, used for Javascript mapping of slider Widget
     *
     * @var string
     */
    private $dataScope = "retailer-pickup";

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var \Smile\Retailer\Model\ResourceModel\Retailer\CollectionFactory
     */
    private $retailerCollectionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var \Smile\Retailer\Api\RetailerScheduleManagementInterface
     */
    private $scheduleManagement;


    private $openingDaysCache = [];

    /**
     *
     * @param Context          $context      Template context.
     * @param EncoderInterface $jsonEncoder  JSON Encoder.
     * @param array            $data         Custom data.
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Smile\Seller\Model\ResourceModel\Seller\CollectionFactory $retailerCollectionFactory,
        \Smile\Retailer\Api\RetailerScheduleManagementInterface $scheduleManagement,
        \Magento\Framework\Stdlib\DateTime $datetime,
        array $data = []
    ) {
            parent::__construct($context, $data);
            $this->jsonEncoder               = $jsonEncoder;
            $this->retailerCollectionFactory = $retailerCollectionFactory;
            $this->scheduleManagement        = $scheduleManagement;
            $this->dateTime                  = $datetime;
    }

    /**
     * Return the config of the price slider JS widget.
     *
     * @return string
     */
    public function getJsonConfig()
    {
        $config = [
            'component' => self::JS_COMPONENT,
            'template'  => self::JS_TEMPLATE,
            'stores'    => $this->getStoresConfig(),
            'updateUrl' => $this->getUpdateUrl(),
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

    private function getUpdateUrl()
    {
        return $this->getUrl('retailer/retailer/set');
    }

    private function getStoresConfig()
    {
        $retailers = [];
        $retailerCollection = $this->retailerCollectionFactory->create(['attributeSetId' => 'Retailer']);
        $retailerCollection->addAttributeToSelect(['name']);

        foreach ($retailerCollection as $retailer) {
            $retailers[$retailer->getId()] = [
                'name'     => $retailer->getName(),
                'calendar' => $this->getCalendar($retailer)
            ];
        }

        return $retailers;
    }

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
     *
     * @param unknown $seller
     * @return \DateTime
     */
    public function getMinDate($seller)
    {
        $date = new \DateTime();
        $date->add(\DateInterval::createFromDateString('+1 day'));
        return $date;
    }

    public function getMaxDate($seller)
    {
        $date = $this->getMinDate($seller);
        $date->add(\DateInterval::createFromDateString(sprintf('+%s day', self::DEFAULT_NUMBER_OF_DAY)));
        return $date;
    }

    public function isValidDate($seller, $date)
    {
        $openingDays = $this->getOpeningDays($seller);
        return in_array($date->format('w'), $openingDays);
    }

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
}
