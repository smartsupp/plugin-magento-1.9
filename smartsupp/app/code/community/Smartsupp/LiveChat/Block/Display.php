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
 * Version:           1.1.0
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
            return $this->jsQuoteEscape(Mage::helper('livechat')->getChatApi());
        }
        return null;
    }
	
    public function getCustomerId()
    {
        if (Mage::helper('livechat')->getCustomerId()) {
            return 'id : {label: "' . Mage::helper('livechat')->__('ID') . '", value: "' . $this->jsQuoteEscape(Mage::helper('customer')->getCurrentCustomer()->getId()) . '"},';
        }
        return null;
    }

    public function getCustomerName()
    {
        if (Mage::helper('livechat')->getCustomerName()) {
            return 'name : {label: "' . Mage::helper('livechat')->__('Name') . '", value: "' . $this->jsQuoteEscape(Mage::helper('customer')->getCurrentCustomer()->getFirstname() . ' ' . Mage::helper('customer')->getCurrentCustomer()->getLastname()) . '"},';
        }
        return null;
    }	
	
    public function getCustomerEmail()
    {
        if (Mage::helper('livechat')->getCustomerEmail()) {
            return 'email : {label: "' . Mage::helper('livechat')->__('Email') . '", value: "' . $this->jsQuoteEscape(Mage::helper('customer')->getCurrentCustomer()->getEmail()) . '"},';
        }
        return null;
    }

    public function getCustomerPhone()
    {
        if (Mage::helper('livechat')->getCustomerPhone() && Mage::helper('customer')->getCurrentCustomer()->getPrimaryBillingAddress()) {
            return 'phone : {label: "' . Mage::helper('livechat')->__('Phone') . '", value: "' . $this->jsQuoteEscape(Mage::helper('customer')->getCurrentCustomer()->getPrimaryBillingAddress()->getTelephone()) . '"},';
        }
        return null;
    }

    public function getCustomerRole()
    {
        if (Mage::helper('livechat')->getCustomerRole()) {
            return 'role : {label: "' . Mage::helper('livechat')->__('Role') . '", value: "' . $this->jsQuoteEscape(Mage::getModel('customer/group')->load(Mage::getSingleton('customer/session')->getCustomerGroupId())->getCustomerGroupCode()) . '"},';
        }
        return null;
    }

    public function getCustomerSpendings()
    {
        $orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('customer_email', Mage::helper('customer')->getCurrentCustomer()->getEmail())->addFieldToFilter('status', 'complete');

        $spendings = 0;
        foreach ($orders as $order) {
            $spendings += $order->getGrandTotal();
        }
        if (Mage::helper('livechat')->getCustomerSpendings()) {
            return 'spendings : {label: "' . Mage::helper('livechat')->__('Spendings') . '", value: "' . $this->jsQuoteEscape($spendings) . '"},';
        }
        return null;
    }

    public function getCustomerOrders()
    {
        $orders = Mage::getModel('sales/order')->getCollection()->addAttributeToFilter('customer_email', Mage::helper('customer')->getCurrentCustomer()->getEmail())->addFieldToFilter('status', 'complete');

        $count = 0;
        foreach ($orders as $order) {
                $count++;
        }
        if (Mage::helper('livechat')->getCustomerOrders()) {
            return 'orders : {label: "' . Mage::helper('livechat')->__('Orders') . '", value: "' . $this->jsQuoteEscape($count) . '"},';
        }
        return null;
    }

    protected function _toHtml()
    {
        if (!Mage::helper('livechat')->getEnabled()) {
        	return null;
		}

		if (Mage::helper('livechat')->getForceLogin() && !Mage::getSingleton('customer/session')->isLoggedIn()) {
			return null;
		}

        if (strlen($this->getChatApi()) > 0) {
            $smartsupp_api_js = $this->getChatApi();
        } else {
            $smartsupp_api_js = '';
        }

        $smartsupp_variables_js = '';
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            if ($this->getCustomerId()) {
                $smartsupp_variables_js .= $this->getCustomerId();
            }
            if ($this->getCustomerName()) {
                $smartsupp_variables_js .= $this->getCustomerName();
            }
            if ($this->getCustomerEmail()) {
                $smartsupp_variables_js .= $this->getCustomerEmail();
            }
            if ($this->getCustomerPhone()) {
                $smartsupp_variables_js .= $this->getCustomerPhone();
            }
            if ($this->getCustomerRole()) {
                $smartsupp_variables_js .= $this->getCustomerRole();
            }
            if ($this->getCustomerSpendings()) {
                $smartsupp_variables_js .= $this->getCustomerSpendings();
            }
            if ($this->getCustomerOrders()) {
                $smartsupp_variables_js .= $this->getCustomerOrders();
            }
        }
        
        $block = $this->getLayout()->createBlock(
            'core/template',
            'smartsupp_livechat',
            array(
                'template' => 'livechat/widget.phtml',
                'key' => Mage::helper('livechat')->getChatId(),
                'dashboard_name' => $this->jsQuoteEscape(Mage::helper('customer')->getCurrentCustomer()->getFirstname() . ' ' . Mage::helper('customer')->getCurrentCustomer()->getLastname()),
                'optional_api_js' => $smartsupp_api_js,
                'variables_js' => $this->jsQuoteEscape($smartsupp_variables_js),
                'cookie_domain' => '', //$this->getModel()->getCookie()->getDomain(),
            )
        );

        return $block->toHtml();
    }
}
