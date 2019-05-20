<?php
/**
 * Smartsupp Live Chat integration module.
 * 
 * @package   Smartsupp
 * @author    Smartsupp <vladimir@smartsupp.com>
 * @link      http://www.smartsupp.com
 * @copyright 2015 Smartsupp.com
 * @license   GPL-2.0+
 *
 * Plugin Name:       Smartsupp Live Chat
 * Plugin URI:        http://www.smartsupp.com
 * Description:       Adds Smartsupp Live Chat code to Magento.
 * Version:           1.1.1
 * Author:            Smartsupp
 * Author URI:        http://www.smartsupp.com
 * Text Domain:       smartsupp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Smartsupp_LiveChat_Block_Display extends Mage_Core_Block_Template
{
    public function getChatApi()
    {
        if (strlen(trim(Mage::helper('livechat')->getChatApi())) > 1) {
            // intended to be NOT escaped
            return Mage::helper('livechat')->getChatApi();
        }

        return '';
    }
    
    public function getCustomerId()
    {
        if (Mage::helper('livechat')->getCustomerId()) {
            $customer = Mage::helper('customer')->getCurrentCustomer();
            $customerId = $customer->getId();

            return 'id : {label: "' . Mage::helper('livechat')->__('ID') . '", value: "' . $customerId . '"},';
        }

        return null;
    }

    public function getCustomerName()
    {
        if (Mage::helper('livechat')->getCustomerName()) {
            $customer = Mage::helper('customer')->getCurrentCustomer();
            $customerName = $this->jsQuoteEscape($customer->getFirstname() . ' ' . $customer->getLastname());

            return 'name : {label: "' . Mage::helper('livechat')->__('Name') . '", value: "' . $customerName . '"},';
        }

        return null;
    }    
    
    public function getCustomerEmail()
    {
        if (Mage::helper('livechat')->getCustomerEmail()) {
            $customer = Mage::helper('customer')->getCurrentCustomer();
            $customerEmail = $this->jsQuoteEscape($customer->getEmail());

            return 'email : {label: "' . Mage::helper('livechat')->__('Email') . '", value: "' . $customerEmail . '"},';
        }

        return null;
    }

    public function getCustomerPhone()
    {
        $customer = Mage::helper('customer')->getCurrentCustomer();

        if (Mage::helper('livechat')->getCustomerPhone() && $customer->getPrimaryBillingAddress()) {
            $customerPhone = $this->jsQuoteEscape($customer->getPrimaryBillingAddress()->getTelephone());

            return 'phone : {label: "' . Mage::helper('livechat')->__('Phone') . '", value: "' . $customerPhone . '"},';
        }

        return null;
    }

    public function getCustomerRole()
    {
        if (Mage::helper('livechat')->getCustomerRole()) {
            $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            $customerGroup = Mage::getModel('customer/group')->load($customerGroupId)->getCustomerGroupCode();
            $customerGroup = $this->jsQuoteEscape($customerGroup);

            return 'role : {label: "' . Mage::helper('livechat')->__('Role') . '", value: "' . $customerGroup . '"},';
        }

        return null;
    }

    public function getCustomerSpendings()
    {
        $customerEmail = Mage::helper('customer')->getCurrentCustomer()->getEmail();

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('customer_email', $customerEmail)
            ->addFieldToFilter('status', 'complete');

        $spend = 0;
        foreach ($orders as $order) {
            $spend += $order->getGrandTotal();
        }

        if (Mage::helper('livechat')->getCustomerSpendings()) {
            return 'spendings : {label: "' . Mage::helper('livechat')->__('Spendings') . '", value: "' . $spend . '"},';
        }

        return null;
    }

    public function getCustomerOrders()
    {
        $customerEmail = Mage::helper('customer')->getCurrentCustomer()->getEmail();

        $orders = Mage::getModel('sales/order')->getCollection()
            ->addAttributeToFilter('customer_email', $customerEmail)
            ->addFieldToFilter('status', 'complete');

        $count = 0;
        foreach ($orders as $order) {
                $count++;
        }

        if (Mage::helper('livechat')->getCustomerOrders()) {
            return 'orders : {label: "' . Mage::helper('livechat')->__('Orders') . '", value: "' . $count . '"},';
        }

        return null;
    }

    protected function _toHtml()
    {
        /**
         * @var Smartsupp_LiveChat_Helper_Data $liveChatHelper
         */
        $liveChatHelper = Mage::helper('livechat');

        if (!$liveChatHelper->getEnabled()) {
            return null;
        }

        if ($liveChatHelper->getForceLogin() && !Mage::getSingleton('customer/session')->isLoggedIn()) {
            return null;
        }

        $smartsuppApiJs = $this->getChatApi();
        $smartsuppVariablesJs = '';

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $variables = array('id', 'name', 'email', 'phone', 'role', 'spendings', 'orders');

            foreach ($variables as $variableName) {
                $methodName = 'getCustomer' . ucfirst($variableName);
                $value = $this->$methodName();

                if ($value) {
                    $smartsuppVariablesJs .= $value;
                }
            }
        }

        $customer = Mage::helper('customer')->getCurrentCustomer();

        $block = $this->getLayout()->createBlock(
            'core/template',
            'smartsupp_livechat',
            array(
                'template' => 'livechat/widget.phtml',
                'key' => Mage::helper('livechat')->getChatId(),
                'dashboard_name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'optional_api_js' => $smartsuppApiJs,
                'variables_js' => $smartsuppVariablesJs,
                'cookie_domain' => '',
            )
        );

        return $block->toHtml();
    }
}
