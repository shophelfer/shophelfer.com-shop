<?php
/* --------------------------------------------------------------
   payone_logs.php 2013-00-00 mabr
   Gambio GmbH
   http://www.gambio.de
   Copyright (c) 2013 Gambio GmbH
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   --------------------------------------------------------------


   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_cod_fee.php,v 1.02 2003/02/24); www.oscommerce.com
   (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers ; http://www.themedia.at & http://www.oscommerce.at
   (c) 2003 XT-Commerce - community made shopping http://www.xt-commerce.com ($Id: ot_cod_fee.php 1003 2005-07-10 18:58:52Z mz $)

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

require_once 'includes/application_top.php';

// include language
require_once (DIR_FS_EXTERNAL.'payone/lang/'.$_SESSION['language'].'.php');

require_once (DIR_FS_EXTERNAL.'payone/classes/PayoneModified.php');
$payone = new PayoneModified();

if ($payone->checkConfig() === false) {
  xtc_redirect(xtc_href_link('payone_config.php'));
}

// check start and end Date
$startDate = "";
$startDateG = 0;
if (isset($_GET['startD']) && (xtc_not_null($_GET['startD'])) ) {
  $sDay = $_GET['startD'];
  $startDateG = 1;
} else {
  $sDay = 1;
}
if (isset($_GET['startM']) && (xtc_not_null($_GET['startM'])) ) {
  $sMon = $_GET['startM'];
  $startDateG = 1;
} else {
  $sMon = 1;
}
if (isset($_GET['startY']) && (xtc_not_null($_GET['startY'])) ) {
  $sYear = $_GET['startY'];
  $startDateG = 1;
} else {
  $sYear = date("Y");
}
if ($startDateG) {
  $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
} else {
  $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
}

$endDate = "";
$endDateG = 0;
if (isset($_GET['endD']) && (xtc_not_null($_GET['endD'])) ) {
  $eDay = $_GET['endD'];
  $endDateG = 1;
} else {
  $eDay = 1;
}
if (isset($_GET['endM']) && (xtc_not_null($_GET['endM'])) ) {
  $eMon = $_GET['endM'];
  $endDateG = 1;
} else {
  $eMon = 1;
}
if (isset($_GET['endY']) && (xtc_not_null($_GET['endY'])) ) {
  $eYear = $_GET['endY'];
  $endDateG = 1;
} else {
  $eYear = date("Y");
}
if ($endDateG) {
  $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
} else {
  $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
}

$mode = ((isset($_GET['mode']) && in_array($_GET['mode'], array('api', 'transactions'))) ? $_GET['mode'] : 'api');
$page = ((isset($_GET['page'])) ? $_GET['page'] : '1');

$messages_ns = 'messages_'.basename(__FILE__);
if(!isset($_SESSION[$messages_ns])) {
	$_SESSION[$messages_ns] = array();
}

$messages = $_SESSION[$messages_ns];
$_SESSION[$messages_ns] = array();

$entries_per_page = 50;
$search = xtc_db_prepare_input($_GET['search']);
$total_logs = $payone->getLogsCount($mode, $startDate, $endDate, $search);
$total_pages = max(1, ceil($total_logs / $entries_per_page));
$limit = $entries_per_page;
$offset = ($page - 1) * $entries_per_page;
$logs = $payone->getLogs($mode, $limit, $offset, $startDate, $endDate, $search);

$event_id = '';
if(isset($_GET['event_id'])) {
  $event_id = (int)$_GET['event_id'];
	$event_data = $payone->getLogData($mode, $event_id);
}


require (DIR_WS_INCLUDES.'head.php');
?>
		<style>
			p.message {
				margin: .5ex auto;
				background: rgb(240, 230, 140);
				border: 1px solid rgb(255, 0, 0);
				padding: 1em;
			}

			dl.adminform {
				position: relative;
				overflow: auto;
			}

			dl.adminform dd, dl.adminform dt {
				float: left;
			}

			dl.adminform dt {
				clear: left;
				width: 15em;
			}

			input[type="submit"].btn_wide {
				width: auto;
			}

			#start_date, #end_date { width: 8em; }

			#logsform { display: block; width: 60%; margin: auto; }
			p.nologs { width: 60%; margin: 1.5em auto; }
			table.payone_logs {
				width: 60%;
				margin: 1.5em auto;
				border-collapse: collapse;
				background: #eee;
			}
			table.payone_logs th {
				background: #ddd;
			}
			table.payone_logs th, table.payone_logs td { padding: .2em .3em; }

			div.event {
				overflow: auto;
				background: #eee;
				width: 100%;
			}

			div.event_id {
				background: #ddd;
				font-size: 1.3em;
				padding: .4em .5em;
			}

			table.event_log {
				width: calc(50% - 2em);
				float: left;
				margin: 1em;
				background: #ddd;
			}

			table.event_log td.label { width: 40%; }
		</style>
	</head>
	<body>
		<!-- header //-->
		<?php require DIR_WS_INCLUDES . 'header.php'; ?>
		<!-- header_eof //-->

		<!-- body //-->
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <!-- body_text //-->
				<td class="boxCenter" width="100%" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="0" class="">
						<tr>
							<td>
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
									<tr>
										<td class="pageHeading" style="padding-left: 0px"><?php echo PAYONE_LOGS_TITLE; ?></td>
										<td width="80" rowspan="2">&nbsp;</td>
									</tr>
									<tr>
										<td class="main" valign="top">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="main">
								<?php foreach($messages as $msg) { ?>
								<p class="message"><?php echo $msg; ?></p>
								<?php } ?>

                <?php echo xtc_draw_form('log', basename($PHP_SELF), '', 'get').xtc_draw_hidden_field(xtc_session_name(), xtc_session_id()); ?>
                  <table style="border: 1px solid #cccccc; width:100%; padding:5px; background:#f1f1f1;">
                    <tr>
                      <td class="menuBoxHeading">
                        <?php echo START_DATE;?>
                        <select name="startD" size="1">
                          <?php                                  
                          if ($startDate) {
                            $j = date("j", $startDate);                                    
                          } else {
                            $j = 1;
                          }
                          for ($i = 1; $i < 32; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <select name="startM" size="1">
                          <?php
                          if ($startDate) {
                            $m = date("n", $startDate);
                          } else {
                            $m = 1;
                          }
                          for ($i = 1; $i < 13; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php if ($m == $i) echo " selected"; ?>><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <select name="startY" size="1">
                          <?php
                          if ($startDate) {
                            $y = date("Y") - date("Y", $startDate);
                          } else {
                            $y = 0;
                          }
                          for ($i = 10; $i >= 0; $i--) {
                            ?>
                            <option value="<?php echo date("Y") - $i; ?>"<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </td>
                      <td class="menuBoxHeading">
                        <?php echo END_DATE; ?>
                        <select name="endD" size="1">
                          <?php
                          echo $endDate;
                        
                          if ($endDate) {
                            $j = date("j", $endDate - (60 * 60 * 24));
                          } else {
                            $j = date("j");
                          }
                          for ($i = 1; $i < 32; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <select name="endM" size="1">
                          <?php
                          if ($endDate) {
                            $m = date("n", $endDate - 60* 60 * 24);
                          } else {
                            $m = date("n");
                          }
                          for ($i = 1; $i < 13; $i++) {
                            ?>
                            <option value="<?php echo $i; ?>"<?php if ($m == $i) echo " selected"; ?>><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <select name="endY" size="1">
                          <?php
                          if ($endDate) {
                            $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
                          } else {
                            $y = 0;
                          }
                          for ($i = 10; $i >= 0; $i--) {
                            ?>
                            <option value="<?php echo date("Y") - $i; ?>"<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </td>
                      <td class="menuBoxHeading">
                        <select name="mode">
                          <option value="api" <?php echo $_GET['mode'] == 'api' ? 'selected="selected"' : '' ?>><?php echo API; ?></option>
                          <option value="transactions" <?php echo $_GET['mode'] == 'transactions' ? 'selected="selected"' : '' ?>><?php echo TRANSACTIONS; ?></option>
                        </select>
                      </td>
                      <td class="menuBoxHeading">
                      <?php
                        echo SEARCH;
                        echo xtc_draw_input_field('search', $search, 'style="width: 135px"'); 
                      ?>
                      </td>
                      <td class="menuBoxHeading">
                        <?php echo PAGE; ?>
                        <select name="page" id="pageselect">
                          <?php for($pageno = 1; $pageno <= $total_pages; $pageno++) { ?>
                            <option value="<?php echo $pageno; ?>" <?php echo (($pageno == $page) ? 'selected="selected"' : ''); ?>><?php echo $pageno ?></option>
                          <?php } ?>
                        </select>
                      </td>
                    </tr>
                  </table>  
                  <div class="main mrg5 txta-r">
                    <?php echo '<input type="submit" class="btn btn-default" onclick="this.blur();" value="' . BUTTON_UPDATE . '"/>'; ?>
                  </div>
                  <br/>                  
								</form>

								<?php if(empty($logs)) { ?>
									<p class="nologs"><?php echo NO_LOGS; ?></p>
								<?php } else { ?>
									<table border="0" width="100%" cellspacing="0" cellpadding="0">
										<tr class="dataTableHeadingRow">
											<td class="dataTableHeadingContent"><?php echo EVENT_ID; ?></td>
											<td class="dataTableHeadingContent"><?php echo DATETIME; ?></td>
											<td class="dataTableHeadingContent"><?php echo CUSTOMER; ?></td>
										</tr>
										<?php foreach($logs as $log) { ?>
											<tr <?php echo (($log['event_id'] == $event_id) ? 'class="dataTableRowSelected"' : 'class="dataTableRow"'); ?> onmouseover="this.style.cursor='pointer'" onclick="document.location.href='<?php echo xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('event_id')).'event_id='.$log['event_id']); ?>'">
												<td class="dataTableContent"><?php echo $log['event_id']; ?></td>
												<td class="dataTableContent"><?php echo $log['date_created']; ?></td>
												<td class="dataTableContent"><?php echo $log['customers_name']; ?>&nbsp;</td>
											</tr>
                      <?php if (isset($_GET['event_id']) && !empty($event_data) && $log['event_id'] == $event_id) { ?>
                      <tr>
                        <td class="dataTableContent" colspan="3">
                          <div class="event">
                            <?php foreach($event_data as $event_log) { ?>
                              <table class="event_log">
                                <tr>
                                  <td class="label"><?php echo EVENT_LOG_COUNT; ?></td>
                                  <td class="value">
                                    <?php echo $event_log['log_count'] ?>
                                    <?php if($event_log['log_count'] == 1) echo '(##request)'; ?>
                                    <?php if($event_log['log_count'] == 2) echo '(##response)'; ?>
                                    <?php if($event_log['log_count'] > 2) echo '(##additional_event)'; ?>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="label"><?php echo DATETIME; ?></td>
                                  <td class="value">
                                    <?php echo $event_log['date_created'] ?>
                                  </td>
                                </tr>
                                <?php foreach($event_log['message'] as $name => $value) { ?>
                                  <tr>
                                    <td class="label"><?php echo $name ?></td>
                                    <td class="value"><?php echo $value ?></td>
                                  </tr>
                                <?php } ?>
                              </table>
                            <?php } ?>
                          </div>
                        </td>
                      </tr>
                      <?php } ?>
										<?php } ?>
									</table>
								<?php }?>
							</td>
						</tr>
					</table>
				</td>
				<!-- body_text_eof //-->
			</tr>
		</table>
		<!-- body_eof //-->

		<!-- footer //-->
		<?php require DIR_WS_INCLUDES . 'footer.php'; ?>
		<!-- footer_eof //-->
	</body>
</html>
<?php
require DIR_WS_INCLUDES . 'application_bottom.php';
?>
