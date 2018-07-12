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

class Smartsupp_LiveChat_Block_Adminhtml_Config_Source_Hint extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<p><img style="vertical-align:middle" src="data:image/png;base64,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"></p>';

        $buttonSignUp = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label'     => $this->__('Not a Smartsupp user yet?'),
            'onclick'   => "window.open('". Smartsupp_LiveChat_Helper_Data::SMARTSUPP_SIGNUP_URL ."', '_blank');",
            'class'     => 'add',
            'type'      => 'button',
            'id'        => 'smartsupp-signup',
        ))
        ->toHtml();

        $buttonDashboard  = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label'     => $this->__('Smartsupp Dashboard'),
            'onclick'   => "window.open('". Smartsupp_LiveChat_Helper_Data::SMARTSUPP_DASHBOARD_URL ."', '_blank');",
            'class'     => 'go',
            'type'      => 'button',
            'id'        => 'smartsupp-dashboard',
        ))
        ->toHtml();

        return $html . '<p>' . $buttonSignUp . '&nbsp;&nbsp;' . $buttonDashboard . '</p>';
    }
}
