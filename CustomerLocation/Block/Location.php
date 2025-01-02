<?php
namespace Magecrafts\CustomerLocation\Block;

use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Location extends Template
{
    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * Location constructor.
     *
     * @param Context $context
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Check if the customer is logged in and has a location
     *
     * @return bool
     */
    public function hasLocation(): bool
    {
        $customerId = $this->customerSession->getCustomerId();
        if (!$customerId) {
            return false;
        }

        // Check if customer has already added a location
        $customer = $this->customerSession->getCustomer();
        $location = $customer->getCustomAttribute('customer_location');

        return $location ? true : false;
    }

    /**
     * Get URL for saving the location
     *
     * @return string
     */
    public function getSaveLocationUrl(): string
    {
        return $this->getUrl('customer_location/location/save');
    }
}
