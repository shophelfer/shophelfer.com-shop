ALTER TABLE `imagesliders_info` ADD `imagesliders_alt` VARCHAR( 100 ) NOT NULL AFTER `imagesliders_title`;

INSERT INTO `email_manager` (`em_name` ,`em_language` ,`em_body` ,`em_delete` ,`em_type` ,`em_body_txt`) VALUES ('stock_reorder_mail', '1',  '<p>After the most recent offer the {$PRODUCTS_NAME} has been reduced to {$PRODUCTS_CURRENT_QTY} quantity, please re-stock</p>' , '0', 'mail',  'After the most recent offer the {$PRODUCTS_NAME} has been reduced to {$PRODUCTS_CURRENT_QTY} quantity, please re-stock');
INSERT INTO `email_manager` (`em_name` ,`em_language` ,`em_body` ,`em_delete` ,`em_type` ,`em_body_txt`) VALUES ('stock_reorder_mail', '2',  '<p>Nach dem letzten Verkauf des Produktes  {$PRODUCTS_NAME} ist der Bestand auf {$PRODUCTS_CURRENT_QTY}  gesunken. Bitte bestellen Sie nach.</p>' , '0', 'mail',  'Nach dem letzten Verkauf des Produktes  {$PRODUCTS_NAME} ist der Bestand auf {$PRODUCTS_CURRENT_QTY}  gesunken. Bitte bestellen Sie nach.');

UPDATE configuration SET use_function = 'xtc_cfg_pull_down_tax_classes' WHERE configuration_key = 'MODULE_ORDER_TOTAL_SHIPPING_TAX_CLASS';

INSERT INTO `configuration` (`configuration_key`, `configuration_value`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES ('PDFBILL_AUTOMATIC_INVOICE', 'false', '17', '16', NULL, now(), NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');
UPDATE configuration SET sort_order = 17 WHERE configuration_key = 'GOOGLE_RSS_FEED_REFID';

UPDATE `languages` SET `language_charset` = 'utf-8';

UPDATE database_version SET version = 'SH_1.7.0';

