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

class Smartsupp_LiveChat_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SMARTSUPP_SIGNUP_URL = 'https://www.smartsupp.com/cs/sign/up';
    const SMARTSUPP_DASHBOARD_URL = 'https://dashboard.smartsupp.com';

    public function getEnabled ()
    {
        return Mage::getStoreConfigFlag('livechat/settings/enabled');
    }

    public function getChatId ()
    {
        return Mage::getStoreConfig('livechat/settings/chatid');
    }
    
    public function getChatApi ()
    {
        return Mage::getStoreConfig('livechat/settings/chatapi');
    }
    
    public function getForceLogin ()
    {
        return Mage::getStoreConfigFlag('livechat/settings/force_login');
    }
    
    public function getCustomerId ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customerid');
    }
    
    public function getCustomerName ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customername');
    }

    public function getCustomerEmail ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customeremail');
    }

    public function getCustomerPhone ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customerphone');
    }

    public function getCustomerRole ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customerrole');
    }

    public function getCustomerSpendings ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customerspendings');
    }

    public function getCustomerOrders ()
    {
        return Mage::getStoreConfigFlag('livechat/variables/customerorders');
    }
}
