<?php
/* -----------------------------------------------------------------------------------------
   $Id: send_order.php 1510 2010-11-22 13:24:04Z dokuman $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce; www.oscommerce.com
   (c) 2003      nextcommerce; www.nextcommerce.org
   (c) 2006      xt:Commerce; www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require_once (DIR_FS_INC.'xtc_get_order_data.inc.php');
require_once (DIR_FS_INC.'xtc_get_attributes_model.inc.php');
// check if customer is allowed to send this order!
$order_query_check = xtc_db_query("SELECT customers_id
                                     FROM ".TABLE_ORDERS."
                                    WHERE orders_id='".$insert_id."'");

$order_check = xtc_db_fetch_array($order_query_check);
//BOF - web28 - 2010-03-20 - Send Order by Admin
//if ($_SESSION['customer_id'] == $order_check['customers_id'] ) {
if ($_SESSION['customer_id'] == $order_check['customers_id'] || $send_by_admin) {
//EOF - web28 - 2010-03-20 - Send Order by Admin

  $order = new order($insert_id);

// BOF - Tomcraft - 2009-10-03 - Paypal Express Modul
  if (isset($_SESSION['paypal_express_new_customer']) 
      && $_SESSION['paypal_express_new_customer'] == 'true'
      && !isset($send_by_admin)
      && is_object($order)
      ) 
  {
    require_once (DIR_FS_INC.'xtc_random_charcode.inc.php');
  
    $vlcode = xtc_random_charcode(32);
    $link = xtc_href_link(FILENAME_PASSWORD_DOUBLE_OPT, 'action=verified&customers_id='.$order->customer['ID'].'&key='.$vlcode, 'SSL', false);

    $sql_data_array = array('password_request_key' => $vlcode);
    xtc_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . $order->customer['ID'] . "'");
  
    $smarty->assign('NEW_PASSWORD', $link);
  }
// EOF - Tomcraft - 2009-10-03 - Paypal Express Modul

  //BOF - web28 - 2010-03-20 - Send Order by Admin
  if (isset($send_by_admin)) {//DokuMan - 2010-09-18 - Undefined variable: send_by_admin
    $xtPrice = new xtcPrice($order->info['currency'], $order->info['status']);
  }
  //EOF - web28 - 2010-03-20 - Send Order by Admin

  $smarty->assign('address_label_customer', xtc_address_format($order->customer['format_id'], $order->customer, 1, '', '<br />'));
  $smarty->assign('address_label_shipping', xtc_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'));
  $smarty->assign('address_label_payment', xtc_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'));
  $smarty->assign('csID', $order->customer['csID']);

  $order_total = $order->getTotalData($insert_id); //ACHTUNG für Bestellbestätigung  aus Admin Funktion in admin/includes/classes/order.php
  $smarty->assign('order_data', $order->getOrderData($insert_id)); //ACHTUNG für Bestellbestätigung  aus Admin Funktion in admin/includes/classes/order.php
  $smarty->assign('order_total', $order_total['data']);

  $smarty->assign('agree_download', $_SESSION['agree_download']);

  // assign language to template for caching Web28 2012-04-25 - change all $_SESSION['language'] to $order->info['language']
  $smarty->assign('language', $order->info['language']);
  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  $smarty->assign('logo_path', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
  //$smarty->assign('oID', $insert_id);
  $smarty->assign('oID', $order->info['order_id']); //DokuMan - 2011-08-31 - fix order_id assignment

  //shipping method
  if ($order->info['shipping_class'] != '') {
    $shipping_class = explode('_', $order->info['shipping_class']);    
    include (DIR_FS_CATALOG . 'lang/'.$order->info['language'].'/modules/shipping/'.$shipping_class[0].'.php');
    $shipping_method = constant(strtoupper('MODULE_SHIPPING_'.$shipping_class[0].'_TEXT_TITLE'));
  }
  $smarty->assign('SHIPPING_METHOD', $shipping_method);
  
  //payment method
  if ($order->info['payment_method'] != '' && $order->info['payment_method'] != 'no_payment') {    
    include_once (DIR_FS_CATALOG . 'lang/'.$order->info['language'].'/modules/payment/'.$order->info['payment_method'].'.php');
    $payment_method = constant(strtoupper('MODULE_PAYMENT_'.$order->info['payment_method'].'_TEXT_TITLE'));
  }
  $smarty->assign('PAYMENT_METHOD', $payment_method);
  
  $smarty->assign('DATE', xtc_date_long($order->info['date_purchased']));
  $smarty->assign('NAME', $order->customer['name']);

  //BOF - web28 - 2010-08-20 - Fix for more personalized e-mails to the customer (show salutation and surname)
  $gender_query = xtc_db_query("SELECT customers_gender FROM " . TABLE_CUSTOMERS . " WHERE customers_id = '" . $order->customer['id'] . "'");
  $gender = xtc_db_fetch_array($gender_query);
  if ($gender['customers_gender']=='f') {
    $smarty->assign('GENDER', FEMALE);
  } elseif ($gender['customers_gender']=='m') {
    $smarty->assign('GENDER', MALE);
  } else {
    $smarty->assign('GENDER', '');
  }
  //EOF - web28 - 2010-08-20 - Fix for more personalized e-mails to the customer (show salutation and surname)

  //BOF - web28 - 2010-08-20 - Erweiterung Variablen für Bestätigungsmail
  $smarty->assign('CITY', $order->customer['city']);
  $smarty->assign('POSTCODE', $order->customer['postcode']);
  $smarty->assign('STATE', $order->customer['state']);
  $smarty->assign('COUNTRY', $order->customer['country']);
  $smarty->assign('COMPANY', $order->customer['company']);
  $smarty->assign('STREET', $order->customer['street_address']);
  $smarty->assign('FIRSTNAME', $order->customer['firstname']);
    $smarty->assign('LASTNAME', $order->customer['lastname']);
  //EOF - web28 - 2010-08-20 - Erweiterung Variablen für Bestätigungsmail

  $smarty->assign('COMMENTS', $order->info['comments']);
  $smarty->assign('EMAIL', $order->customer['email_address']);
  $smarty->assign('PHONE',$order->customer['telephone']);

  // PAYMENT MODUL TEXTS
  // EU Bank Transfer
  if ($order->info['payment_method'] == 'eustandardtransfer') {
    $smarty->assign('PAYMENT_INFO_HTML', MODULE_PAYMENT_EUTRANSFER_TEXT_DESCRIPTION);
    $smarty->assign('PAYMENT_INFO_TXT', str_replace("<br />", "\n", MODULE_PAYMENT_EUTRANSFER_TEXT_DESCRIPTION));
  }

  // MONEYORDER
  if ($order->info['payment_method'] == 'moneyorder') {
    $smarty->assign('PAYMENT_INFO_HTML', MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION);
    $smarty->assign('PAYMENT_INFO_TXT', str_replace("<br />", "\n", MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION));
  }
  
  // Cash on Delivery
  if ($order->info['payment_method'] == 'cod') {
    $smarty->assign('PAYMENT_INFO_HTML', MODULE_PAYMENT_COD_TEXT_INFO);
    $smarty->assign('PAYMENT_INFO_TXT', str_replace("<br />", "\n", MODULE_PAYMENT_COD_TEXT_INFO));
  }
  
  // banktransfer
  if ($order->info['payment_method'] == 'banktransfer') {
    // add SEPA info
    $oID = $order->info['order_id'];
    if(isset($send_by_admin)) {
      require (DIR_FS_CATALOG_MODULES.'payment/banktransfer.php');
      include(DIR_FS_LANGUAGES.$order->info['language'].'/modules/payment/banktransfer.php');
      $payment_modules = new banktransfer();
    }
    $rec = $payment_modules->info();
    // SEPA info required?
    if (!empty($rec['banktransfer_iban'])) {
      if (!function_exists('xtc_date_short')) {
        require_once (DIR_FS_INC.'xtc_date_short.inc.php');
      }
      $smarty->assign('PAYMENT_BANKTRANSFER_CREDITOR_ID', MODULE_PAYMENT_BANKTRANSFER_CI);
      // set due date based on date_purchased and due_delay
      $due_date = date('Y-m-d', strtotime($order->info['date_purchased'] . ' + ' . MODULE_PAYMENT_BANKTRANSFER_DUE_DELAY . ' days'));
      $smarty->assign('PAYMENT_BANKTRANSFER_DUE_DATE',  xtc_date_short($due_date));
      $total = $xtPrice->xtcFormat($order_total['total'], true);
      $smarty->assign('PAYMENT_BANKTRANSFER_TOTAL', $total);
      $smarty->assign('PAYMENT_BANKTRANSFER_MANDATE_REFERENCE', MODULE_PAYMENT_BANKTRANSFER_REFERENCE_PREFIX . $oID);
      $smarty->assign('PAYMENT_BANKTRANSFER_IBAN', $rec['banktransfer_iban']);
      $smarty->assign('PAYMENT_BANKTRANSFER_BANKNAME', $rec['banktransfer_bankname']);

      $iban_nr = $rec['banktransfer_iban'];
      $iban_number = implode(" ", str_split($iban_nr, 4));
      $smarty->assign('PAYMENT_BANKTRANSFER_IBAN_READABLE', $iban_number);

      $sepa_info = $smarty->fetch('db:sepa_info.html');
          
      $smarty->assign('PAYMENT_INFO_HTML', $sepa_info);
      $smarty->assign('PAYMENT_INFO_TXT', str_replace("<br />", "\n", $sepa_info));
      
      // separate pre-notification necessary?
      if ($rec['banktransfer_owner_email'] != $order->customer['email_address']) {
        $banktransfer_owner_email = $rec['banktransfer_owner_email'];
        $sepa_html_mail = $smarty->fetch('db:sepa_mail.html');
        $sepa_txt_mail = $smarty->fetch('db:sepa_mail.txt');
        $sepa_subject = $smarty->fetch('db:sepa_mail.subject');
        // no pre-notification in order mail
        $smarty->clearAssign('PAYMENT_INFO_HTML');
        $smarty->clearAssign('PAYMENT_INFO_TXT');
      }
    }
  }
	
  //allow duty-note in email
  if(!is_object($main)) {
    require_once(DIR_FS_CATALOG.'includes/classes/main.php');
    $main = new main();
  }
  $smarty->assign('DELIVERY_DUTY_INFO', $main->getDeliveryDutyInfo($order->delivery['country_iso_2']));

  //absolute image path
  $smarty->assign('img_path', HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES.'product_images/'. (defined('SHOW_IMAGES_IN_EMAIL_DIR')? SHOW_IMAGES_IN_EMAIL_DIR : 'thumbnail').'_images/');
  // dont allow cache
  $smarty->caching = 0;

  // BOF - Tomcraft - 2011-06-17 - Added revocation to email  
  $shop_content_data = $main->getContentData(REVOCATION_ID);  
  $revocation = $shop_content_data['content_text'];  
  $smarty->assign('REVOCATION_HTML', $revocation);
  $smarty->assign('REVOCATION_TXT', $revocation); //replace br, strip_tags, html_entity_decode are allready execute in xtc_php_mail  function

  // EOF - Tomcraft - 2011-06-17 - Added revocation to email
  
  if (DISPLAY_PRIVACY == 'true') {
    $privacy_content = $main->getContentData(PRIVACY_ID);
    $privacy = $privacy_content['content_text'];
    $smarty->assign('PRIVACY_INFO_TXT', $privacy);
  }

  ## PayOne
  if (strpos($order->info['payment_method'], 'payone') !== false) {
    require_once(DIR_FS_EXTERNAL.'payone/modules/send_order.php');
  }
  

  ## PayPal
  require_once(DIR_FS_EXTERNAL.'paypal/modules/send_order.php');
	$paypal_payment_method = array(
	  'paypalplus',
	  'paypalclassic',
	  'paypalcart',
	  'paypallink',
	  'paypalpluslink'
	);
  if(in_array($order->info['payment_method'], $paypal_payment_method)){
	  require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalInfo.php');
	  $paypal = new PayPalInfo($order->info['payment_method']);
	  $admin_info_array = $paypal->order_info($order->info['order_id']);
	  $payment_array = $paypal->get_payment_data($order->info['order_id']);
	
	if (isset($admin_info_array['instruction']) && $payment_array['payment_method'] == 'pay_upon_invoice') {
		  $smarty->assign('paypal_payment_method', $order->info['payment_method']);
		  //Zahlungsanweisung - Paypal Invoice Data
		  $smarty->assign('paypal_amount', $admin_info_array['instruction']['amount']['total'].' '.$admin_info_array['instruction']['amount']['currency']);
		  $smarty->assign('paypal_reference', $admin_info_array['instruction']['reference']);
		  $smarty->assign('paypal_paydate', $admin_info_array['instruction']['date']);
		  $smarty->assign('paypal_bank_account', $admin_info_array['instruction']['bank']['name']);
		  $smarty->assign('paypal_holder', $admin_info_array['instruction']['bank']['holder']);
		  $smarty->assign('paypal_iban', $admin_info_array['instruction']['bank']['iban']);
		  $smarty->assign('paypal_bic', $admin_info_array['instruction']['bank']['bic']);
	}
  }
  $html_mail = $smarty->fetch('db:order_mail.html');
  $txt_mail = $smarty->fetch('db:order_mail.txt');

  //email attachments
  $email_attachments = defined('EMAIL_BILLING_ATTACHMENTS') ? EMAIL_BILLING_ATTACHMENTS : '';
  
  // create subject
  $smarty->assign('nr', $insert_id);
  $smarty->assign('date', xtc_date_long($order->info['date_purchased']));
  $smarty->assign('lastname', $order->customer['lastname']);      
  $smarty->assign('firstname', $order->customer['firstname']);
  $order_subject = $smarty->fetch('db:order_mail.subject');
  
  // send mail to admin
  xtc_php_mail(EMAIL_BILLING_ADDRESS,
               EMAIL_BILLING_NAME,
               EMAIL_BILLING_ADDRESS,
               STORE_NAME,
               EMAIL_BILLING_FORWARDING_STRING,
               $order->customer['email_address'],
               $order->customer['firstname'].' '.$order->customer['lastname'],
               $email_attachments,
               '',
               $order_subject,
               $html_mail,
               $txt_mail
               );
  // send mail to customer
  if (SEND_EMAILS == 'true' || $send_by_admin) {
    xtc_php_mail(EMAIL_BILLING_ADDRESS,
                 EMAIL_BILLING_NAME,
                 $order->customer['email_address'],
                 $order->customer['firstname'].' '.$order->customer['lastname'],
                 '',
                 EMAIL_BILLING_REPLY_ADDRESS,
                 EMAIL_BILLING_REPLY_ADDRESS_NAME,
                 $email_attachments,
                 '',
                 $order_subject,
                 $html_mail,
                 $txt_mail
                 );
                 
    if (isset($sepa_html_mail)) {
      xtc_php_mail(EMAIL_BILLING_ADDRESS,
                   EMAIL_BILLING_NAME,
                   $banktransfer_owner_email,
                   '',
                   '',
                   EMAIL_BILLING_REPLY_ADDRESS,
                   EMAIL_BILLING_REPLY_ADDRESS_NAME,
                   '',
                   '',
                   $sepa_subject,
                   $sepa_html_mail,
                   $sepa_txt_mail
                 );
    }
  }

  if (AFTERBUY_ACTIVATED == 'true') {
    require_once (DIR_WS_CLASSES.'afterbuy.php');
    $aBUY = new xtc_afterbuy_functions($insert_id);
    if ($aBUY->order_send())
      $aBUY->process_order();
  }
  //BOF - web28 - 2010-03-20 - Send Order by Admin
  if(isset($send_by_admin)) { //DokuMan - 2010-09-18 - Undefined variable: send_by_admin
    $customer_notified = '1';
    $orders_status_id = '1';
    //Comment out the next line for setting  the $orders_status_id= '1 '- Auskommentieren der nächste Zeile, um die $orders_status_id = '1' zu setzen
    $orders_status_id = ($order->info['orders_status']  < 1) ? '1' : $order->info['orders_status'];

    //web28 - 2011-03-20 - Fix order status
    xtc_db_query("UPDATE ".TABLE_ORDERS."
                     SET orders_status = '".xtc_db_input($orders_status_id)."',
                         last_modified = now()
                   WHERE orders_id = '".xtc_db_input($insert_id)."'");

    //web28 - 2011-08-26 - Fix order status history
    xtc_db_query("INSERT INTO ".TABLE_ORDERS_STATUS_HISTORY."
                          SET orders_id = '".xtc_db_input($insert_id)."',
                              orders_status_id = '".xtc_db_input($orders_status_id)."',
                              date_added = now(),
                              customer_notified = '".$customer_notified."',
                              comments = '".COMMENT_SEND_ORDER_BY_ADMIN."'");

    $messageStack->add_session(SUCCESS_ORDER_SEND, 'success');

    if (isset($_GET['site']) && $_GET['site'] == 1) { //DokuMan - 2010-09-18 - Undefined variable
      xtc_redirect(xtc_href_link(FILENAME_ORDERS, 'oID='.$_GET['oID'].'&action=edit'));
    } else xtc_redirect(xtc_href_link(FILENAME_ORDERS, 'oID='.$_GET['oID']));
  }
  //EOF - web28 - 2010-03-20 - Send Order by Admin

} else {
  $smarty->assign('ERROR', 'You are not allowed to view this order!');
  $smarty->display(CURRENT_TEMPLATE.'/module/error_message.html');
}
?>
