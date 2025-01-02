<?php
namespace Magecrafts\CustomerLocation\Controller\Location;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;

class Save extends Action
{
    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * @var Session
     */
    protected Session $customerSession;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Execute the save action for customer location
     *
     * @return Json
     */
    public function execute(): Json
    {
        $resultJson = $this->resultJsonFactory->create();

        try {
            // Ensure the customer is logged in
            $customerId = $this->customerSession->getCustomerId();
            if (!$customerId) {
                throw new LocalizedException(__('Customer is not logged in.'));
            }

            // Retrieve the location data from the request
            $latitude = $this->getRequest()->getParam('latitude');
            $longitude = $this->getRequest()->getParam('longitude');
            if (!$latitude || !$longitude) {
                throw new LocalizedException(__('Latitude or Longitude is missing.'));
            }

            $locationData = json_encode(['latitude' => $latitude, 'longitude' => $longitude]);

            // Load the customer and set the custom attribute
            $customer = $this->customerRepository->getById($customerId);
            $customer->setCustomAttribute('customer_location', $locationData);

            // Save the updated customer data
            $this->customerRepository->save($customer);

            return $resultJson->setData(['success' => true, 'message' => __('Location saved successfully.')]);
        } catch (LocalizedException $e) {
            // Catch exception and return error message in response
            return $resultJson->setData(['success' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            // Catch any generic exceptions and return error message
            return $resultJson->setData(['success' => false, 'message' => __('An error occurred while saving the location.')]);
        }
    }
}
