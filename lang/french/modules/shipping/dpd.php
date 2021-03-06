<?php
/*------------------------------------------------------------------------------
   v 1.0
   XTC-DPD Shipping Module - Contribution for XT-Commerce http://xt-commerce.com
   modified by http://www.hwangelshop.de

   Copyrigt (c) 2004 cigamth
   ------------------------------------------------------------------------------
   $Id: gls.php,v 1.1 2004/08/13 10:00:13 HHGAG Exp $

   XTC-GLS Shipping Module - Contribution for XT-Commerce http://www.xt-commerce.com
   modified by http://www.hhgag.com

   Copyright (c) 2004 H.H.G.
   -----------------------------------------------------------------------------
   based on:
   (c) 2003 Deutsche Post Module
   Original written by Marcel Bossert-Schwab (webmaster@wernich.de), Version 1.2b
   Addon Released under GLSL V2.0 by Gunter Sammet (Gunter@SammySolutions.com)

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Released under the GNU General Public License

   ---------------------------------------------------------------------------*/
define('MODULE_SHIPPING_DPD_TEXT_TITLE', 'Deutscher Paket Dienst');
define('MODULE_SHIPPING_DPD_TEXT_DESCRIPTION', 'DPD - Service de colis allemand');
define('MODULE_SHIPPING_DPD_TEXT_WAY', 'Livraison à');
define('MODULE_SHIPPING_DPD_TEXT_UNITS', 'kg');
define('MODULE_SHIPPING_DPD_INVALID_ZONE', 'Désolé, ce transporteur ne peut pas expédier dans ce pays.');
define('MODULE_SHIPPING_DPD_UNDEFINED_RATE', 'Les frais d&apos;expédition ne peuvent pas être calculés pour le moment.');
define('MODULE_SHIPPING_DPD_FREE_SHIPPING', 'Livraison gratuite.');
define('MODULE_SHIPPING_DPD_SUBSIDIZED_SHIPPING', 'Nous avons subventionné l&apos;expédition.');

define('MODULE_SHIPPING_DPD_STATUS_TITLE', 'Deutscher Paket Dienst');
define('MODULE_SHIPPING_DPD_STATUS_DESC', 'Souhaitez-vous offrir un service d&apos;expédition avec DPD?');
define('MODULE_SHIPPING_DPD_HANDLING_TITLE', 'frais de manutention.');
define('MODULE_SHIPPING_DPD_HANDLING_DESC', 'Frais de manutention pour ce mode d&apos;expédition');
define('MODULE_SHIPPING_DPD_ALLOWED_TITLE' , 'Zones autorisées');
define('MODULE_SHIPPING_DPD_ALLOWED_DESC' , 'Veuillez entrer les zones <b>séparément</b> qui devrait être autorisé à utiliser ce module (par exemple AT,DE (laissez vide si vous voulez autoriser toutes les zones).');
define('MODULE_SHIPPING_DPD_SORT_ORDER_TITLE', 'Ordre de tri');
define('MODULE_SHIPPING_DPD_SORT_ORDER_DESC', 'Ordre de tri de l&apos;affichage. Le plus bas est affiché en premier.');
define('MODULE_SHIPPING_DPD_TAX_CLASS_TITLE', 'Catégorie fiscale');
define('MODULE_SHIPPING_DPD_TAX_CLASS_DESC', 'Utilisez la classe de taxe suivante sur les frais d&apos;expédition.');
define('MODULE_SHIPPING_DPD_ZONE_TITLE', 'Zone d&apos;expédition');
define('MODULE_SHIPPING_DPD_ZONE_DESC', 'Si une zone est sélectionnée, n&apos;activez cette méthode d&apos;expédition que pour cette zone.');

?>