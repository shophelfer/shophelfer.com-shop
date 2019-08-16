UPDATE database_version SET version = 'SH_1.17.0';

ALTER TABLE products_description ADD content_meta_index TINYINT(1) NULL DEFAULT NULL;
ALTER TABLE products_description ADD canonical_link TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
UPDATE countries SET countries_name = 'Republic North-Macedonia' WHERE countries_id = 126;
ALTER TABLE customers CHANGE customers_password customers_password VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

UPDATE admin_access SET start = 6 WHERE customers_id = 'groups';
UPDATE admin_access SET credits = 6 WHERE customers_id = 'groups';
UPDATE admin_access SET accounting = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET cache = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET server_info = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET whos_online = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET orders = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET campaigns = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET print_packingslip = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET print_order = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET popup_memo = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET mail = 2 WHERE customers_id = 'groups';
UPDATE admin_access SET coupon_admin = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET validproducts = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET validcategories = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET categories = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET backup = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET new_attributes = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET products_attributes = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET manufacturers = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET specials = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET products_expected = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_products_expected = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_products_viewed = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_products_purchased = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_customers = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_sales_report = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_campaigns = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET stats_stock_warning = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET banner_manager = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET banner_statistics = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET module_newsletter = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET content_manager = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET content_preview = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET blacklist = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET popup_image = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET csv_backend = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET products_vpe = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET cross_sell_groups = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET shop_offline = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET xajax = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET blz_update = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET removeoldpics = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET imagesliders = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET products_content = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET pdfbill_config = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET pdfbill_display = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET email_manager = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET email_preview = 1 WHERE customers_id = 'groups';
UPDATE admin_access SET waste_paper_bin = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET inventory = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET inventory_turnover = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET outstanding = 4 WHERE customers_id = 'groups';
UPDATE admin_access SET globaledit = 3 WHERE customers_id = 'groups';
UPDATE admin_access SET stock_range = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET dsgvo_export = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET blacklist_logs = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET whitelist_logs = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET seo_tool_box = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET quick_stockupdate = 5 WHERE customers_id = 'groups';
UPDATE admin_access SET invoiced_orders = 4 WHERE customers_id = 'groups';

