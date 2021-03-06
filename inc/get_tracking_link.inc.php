<?php
  /* --------------------------------------------------------------
   $Id$

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2015 Timo Paul Dienstleistungen

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  function get_tracking_link($orders_id, $lang_code) {
    if ($lang_code != 'de' && $lang_code != 'en') {
      $lang_code == DEFAULT_LANGUAGE;
    }
    $parcel_link = array();
    $tracking_links_query = xtc_db_query("SELECT * 
                                            FROM ".TABLE_ORDERS_TRACKING." ortr
                                            JOIN ".TABLE_CARRIERS." ca
                                                 ON ortr.ortra_carrier_id = ca.carrier_id
                                           WHERE ortr.ortra_order_id = '".(int)$orders_id."'
                                           ORDER BY ortr.ortra_id ASC");
    if (xtc_db_num_rows($tracking_links_query) > 0) {
      $i = 0;
      while ($tracking_link = xtc_db_fetch_array($tracking_links_query)) {
        $parcel_link[$i] = $tracking_link;
        
        $parcel_link[$i]['carrier_name'] = $tracking_link['carrier_name'];
        $parcel_link[$i]['parcel_id'] = $tracking_link['ortra_parcel_id'];
        $parcel_link[$i]['tracking_link'] = str_replace(array('$1', '$2'), array($tracking_link['ortra_parcel_id'], $lang_code), $tracking_link['carrier_tracking_link']);
        $parcel_link[$i]['tracking_id'] = $tracking_link['carrier_tracking_link'];
        
        $i++;
      }
    }

    return $parcel_link;
  }
?>