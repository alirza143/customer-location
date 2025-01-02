<?php

namespace Magecrafts\CustomerLocation\Helper;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Location extends AbstractHelper
{
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var Session
     */
    private $customerSession;

    public function __construct(
        Context $context,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession
    ) {
        parent::__construct($context);
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * Save customer location.
     */
    public function saveCustomerLocation($latitude, $longitude)
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }

        $customer = $this->customerSession->getCustomerData();
        $locationData = json_encode(['latitude' => $latitude, 'longitude' => $longitude]);

        try {
            $customer->setCustomAttribute('customer_location', $locationData);
            $this->customerRepository->save($customer);
            return true;
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve customer location.
     */
    public function getCustomerLocation()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->customerSession->getCustomerData();
        $locationData = $customer->getCustomAttribute('customer_location');
        return $locationData ? json_decode($locationData->getValue(), true) : null;
    }
}
