<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalinstallment.php 11793 2019-04-18 11:20:51Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
  
  require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');
  $paypal_installment = new PayPalPayment('paypalinstallment');

  require_once (DIR_FS_INC.'xtc_get_countries.inc.php');
  $country = xtc_get_countriesList(((isset($_SESSION['country'])) ? $_SESSION['country'] : ((isset($_SESSION['customer_country_id'])) ? $_SESSION['customer_country_id'] : STORE_COUNTRY)), true);
  
  if ($paypal_installment->enabled === true
      && $country['countries_iso_code_2'] == 'DE'
      && ($_SESSION['customers_status']['customers_status_show_price_tax'] == '1'
          || $_SESSION['customers_status']['customers_status_add_tax_ot'] == '0'
          )
      ) 
  {
    $amount = $_SESSION['cart']->show_total(); 
    $presentment = $paypal_installment->get_presentment_details($amount, $_SESSION['currency'], $country['countries_iso_code_2'], 'cart', true);
    $module_smarty->assign('PAYPAL_INSTALLMENT', $presentment);
  }
?>