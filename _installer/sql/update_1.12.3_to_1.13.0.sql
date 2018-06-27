UPDATE database_version SET version = 'SH_1.13.0';

INSERT INTO content_manager VALUES (5, 0, 0, '', 1, 'Index', 'Welcome', '<p>{$greeting}<br />\r\n<br />\r\nThis is a basic installation of the <span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span> shop system. This page, the categories, products, services and offers are for demonstration purposes only. If you order products, they will neither be delivered nor billed.</p>\r\n<p>The <span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span> shop system is completely free and open source. All information about the project can be found on our website: <a target="_blank" rel="noopener" href="http://www.shophelfer.com"><span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span></a></p>\r\n<p>This text can be edited in the admin area under Content -> Content Manager - Entry index.</p>\r\n<p>&nbsp;</p>\r\n<p>Fishnet Services wishes you a lot of success with your new shop system!</p>', 0, 1, '', 0, 5, 0, '', '', '', NOW(), 1);

INSERT INTO content_manager VALUES (10, 0, 0, '', 2, 'Index', 'Willkommen', '<p>{$greeting}<br />\r\n<br />\r\nDies ist eine Grundinstallation des <span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span> Shopsystems. Diese Seite, die Kategorien, Produkte,&nbsp;Dienstleistungen und Angebote dienen nur der Demonstration der Funktionsweise dieses Shopsystemes. Wenn Sie Produkte bestellen, so werden diese weder ausgeliefert, noch in Rechnung gestellt.</p>\r\n<p>Das <span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span> Shopsystem ist komplett kostenlos und Open Source. Alle Informationen zu dem Projekt finden Sie auf unserer Webseite: <a target="_blank" rel="noopener" href="http://www.shophelfer.com"><span style="color: rgb(55, 116, 227);"><strong>fishnet-shop</strong></span><span style="color: rgb(74, 74, 82);"><strong>.com</strong></span></a></p>\r\n<p>Dieser Text kann im Adminbereich unter <b>Inhalte -&gt; Content Manager</b> - Eintrag Index bearbeitet werden.</p>\r\n<p>&nbsp;</p>\r\n<p>Fishnet Services w&uuml;nscht Ihnen viel Erfolg mit Ihrem neuem Shopsystem!</p>', 0, 1, '', 0, 5, 0, '', '', '', NOW(), 1);

INSERT INTO `configuration` (`configuration_id`, `configuration_key`, `configuration_value`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`) VALUES (NULL, 'STOCK_ATTRIBUTE_REORDER_LEVEL', '5', '9', '10', NULL, NOW(), NULL, NULL);

DELETE FROM `configuration` WHERE `configuration_key` = 'PRIVACY_STATEMENT_ID';

INSERT INTO configuration (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'DISPLAY_PRIVACY', 'true', 17, 18, NULL, NOW(), NULL, 'xtc_cfg_select_option(array(\'true\', \'false\'),');

INSERT INTO configuration (configuration_id, configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'PRIVACY_ID', '2', 17, 19, NULL, NOW(), NULL, NULL);

ALTER TABLE `admin_access` ADD `whitelist_logs` INT( 1 ) NOT NULL ;

ALTER TABLE `content_manager` CHANGE `group_ids` `group_ids` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_title` `content_title` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_heading` `content_heading` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_text` `content_text` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_meta_title` `content_meta_title` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_meta_description` `content_meta_description` LONGTEXT;
ALTER TABLE `content_manager` CHANGE `content_meta_keywords` `content_meta_keywords` LONGTEXT;

ALTER TABLE orders ADD ibn_reminderpdfnotifydate DATE NOT NULL ;
