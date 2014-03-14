<?php
require_once 'modules/admin/models/GatewayPlugin.php';

/**
* @package Plugins
*/
class PluginMoneybookers extends GatewayPlugin
{

    function getVariables()
    {
        /* Specification
              itemkey     - used to identify variable in your other functions
              type        - text,textarea,yesno,password
              description - description of the variable, displayed in ClientExec
        */

        $variables = array (
                   /*T*/"Plugin Name"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"How CE sees this plugin (not to be confused with the Signup Name)"/*/T*/,
                                        "value"         =>/*T*/"Money Bookers"/*/T*/
                                       ),
                   /*T*/"Merchant E-mail"/*/T*/ => array (
                                        "type"          =>"text",
                                        "description"   =>/*T*/"E-mail address used to identify you to Moneybookers."/*/T*/,
                                        "value"         =>""
                                       ),
                   /*T*/"Secret Word"/*/T*/ => array (
                                        "type"          =>"text",
                                        "description"   =>/*T*/"Secret word set in your Money Bookers account."/*/T*/,
                                        "value"         =>""
                                       ),
                   /*T*/"Visa"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow Visa card acceptance with this plugin.  No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"MasterCard"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow MasterCard acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"AmericanExpress"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow American Express card acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Discover"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES to allow Discover card acceptance with this plugin. No will prevent this card type."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Invoice After Signup"/*/T*/ => array (
                                        "type"          =>"yesno",
                                        "description"   =>/*T*/"Select YES if you want an invoice sent to the customer after signup is complete."/*/T*/,
                                        "value"         =>"1"
                                       ),
                   /*T*/"Signup Name"/*/T*/ => array (
                                        "type"          =>"text",
                                        "description"   =>/*T*/"Select the name to display in the signup process for this payment type. Example: eCheck or Credit Card."/*/T*/,
                                        "value"         =>/*T*/"Money Bookers"/*/T*/
                                       ),
                   /*T*/"Dummy Plugin"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"1 = Only used to specify a billing type for a customer. 0 = full fledged plugin requiring complete functions"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Accept CC Number"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Selecting YES allows the entering of CC numbers when using this plugin type. No will prevent entering of cc information"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Auto Payment"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"No description"/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"30 Day Billing"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Select YES if you want ClientExec to treat monthly billing by 30 day intervals.  If you select NO then the same day will be used to determine intervals."/*/T*/,
                                        "value"         =>"0"
                                       ),
                   /*T*/"Check CVV2"/*/T*/ => array (
                                        "type"          =>"hidden",
                                        "description"   =>/*T*/"Select YES if you want to accept CVV2 for this plugin."/*/T*/,
                                        "value"         =>"0"
                                       )
        );
        return $variables;
    }

    function credit($params)
    {}

    function singlepayment($params) {
        //Function needs to build the url to the payment processor, then redirect
        //Plugin variables can be accesses via $params["plugin_[pluginname]_[variable]"] (ex. $params["plugin_paypal_UserID"])
        $stat_url = mb_substr($params['clientExecURL'],-1,1) == "//" ? $params['clientExecURL']."plugins/gateways/moneybookers/callback.php" : $params['clientExecURL']."/plugins/gateways/moneybookers/callback.php";

        $strForm  = '<html><body>';
        $strForm .= '<form name="frmMoneyBookers" action="https://www.moneybookers.com/app/payment.pl" method="post">';
        $strForm .= '<input type="hidden" name="pay_to_email" value="'.$params["plugin_moneybookers_Merchant E-mail"].'">';
        $strForm .= '<input type="hidden" name="detail1_description" value="Payment '.$params["companyName"].'">';
        $strForm .= '<input type="hidden" name="detail1_text" value="Invoice '.$params['invoiceNumber'].'">';
        $strForm .= '<input type="hidden" name="amount" value="'.sprintf("%01.2f", round($params["invoiceTotal"], 2)).'">';
        $strForm .= '<input type="hidden" name="transaction_id" value="'.$params['invoiceNumber'].'">';
        $strForm .= '<input type="hidden" name="status_url" value="'.$stat_url.'">';
        $strForm .= '<input type="hidden" name="return_url" value="'.$params["clientExecURL"].'">';
        $strForm .= '<input type="hidden" name="cancel_url" value="'.$params["clientExecURL"].'">';
        $strForm .= '<input type="hidden" name="language" value="EN">';
        $strForm .= '<input type="hidden" name="currency" value="'.$params["currencytype"].'">';
        $strForm .= '<input type="hidden" name="firstname" value="'.$params["userFirstName"].'">';
        $strForm .= '<input type="hidden" name="lastname" value="'.$params["userLastName"].'">';
        $strForm .= '<input type="hidden" name="address" value="'.$params["userAddress"].'">';
        $strForm .= '<input type="hidden" name="city" value="'.$params["userCity"].'".';
        $strForm .= '<input type="hidden" name="state" value="'.$params["userState"].'">';
        $strForm .= '<input type="hidden" name="postal_code" value="'.$params["userZipcode"].'">';
        $strForm .= "<script language=\"JavaScript\">\n";
        $strForm .= "document.forms['frmMoneyBookers'].submit();\n";
        $strForm .= "</script>";
        $strForm .= "</form>";
        $strForm .= "</body></html>";
        echo $strForm;
        exit;
    }
}
?>
