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
 * Version:           1.0.0
 * Author:            Smartsupp
 * Author URI:        http://www.smartsupp.com
 * Text Domain:       smartsupp
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

class Smartsupp_LiveChat_Model_Config_Source_Showhide
{
    public function toOptionArray()
    {
        return array(
            array('value' => '0', 'label' => Mage::helper('livechat')->__('Hide')),
            array('value' => '1', 'label' => Mage::helper('livechat')->__('Show')),
        );
    }
}
