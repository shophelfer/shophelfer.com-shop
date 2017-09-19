<?php
  /* --------------------------------------------------------------
   $Id: products_content.php 4143 2012-12-18 14:55:48Z Tomcraft1980 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercecoding standards www.oscommerce.com
   (c) 2003 nextcommerce (content_manager.php,v 1.18 2003/08/25); www.nextcommerce.org
   (c) 2006 XT-Commerce (content_manager.php 1304 2005-10-12)

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');
  require_once(DIR_FS_INC . 'xtc_format_filesize.inc.php');
  require_once(DIR_FS_INC . 'xtc_filesize.inc.php');
  require_once(DIR_FS_INC . 'xtc_wysiwyg.inc.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $special = (isset($_GET['special']) ? $_GET['special'] : '');
  $id = (isset($_GET['id']) ? $_GET['id'] : '');
  $g_coID = (isset($_GET['coID']) ? (int)$_GET['coID'] : '');
  $languages = xtc_get_languages();

  if ($special=='delete_product') {
    xtc_db_query("DELETE FROM ".TABLE_PRODUCTS_CONTENT." where content_id='".$g_coID."'");
    xtc_redirect(xtc_href_link(FILENAME_PRODUCTS_CONTENT,'pID='.(int)$_GET['pID']));
  } // if get special

  if ($id=='update_product' or $id=='insert_product') {
    // set allowed c.groups
    $group_ids='';
    if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
      $group_ids .= 'c_'.$b."_group ,";
    }
    $customers_statuses_array=xtc_get_customers_statuses();
    if (strstr($group_ids,'c_all_group')) {
      $group_ids='c_all_group,';
      for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
        $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
     }
    }

    $content_title=xtc_db_prepare_input($_POST['cont_title']);
    $content_link=xtc_db_prepare_input($_POST['cont_link']);
    $content_language=xtc_db_prepare_input($_POST['language']);
    $product=xtc_db_prepare_input($_POST['product']);
    $upload_file=xtc_db_prepare_input($_POST['file_upload']);
    $filename=xtc_db_prepare_input($_POST['file_name']);
    $coID=xtc_db_prepare_input($_POST['coID']);
    $file_comment=xtc_db_prepare_input($_POST['file_comment']);
    $select_file=xtc_db_prepare_input($_POST['select_file']);
    $group_ids = $group_ids;
    $error=false; // reset error flag

    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      if ($languages[$i]['code']==$content_language) $content_language=$languages[$i]['id'];
    } // for

    if (strlen($content_title) < 1) {
      $error = true;
      $messageStack->add(ERROR_TITLE,'error');
    }  // if

    if ($error == false) {
       // mkdir() wont work with php in safe_mode
       //if  (!is_dir(DIR_FS_CATALOG.'media/products/'.$product.'/')) {
       //  $old_umask = umask(0);
       //  xtc_mkdirs(DIR_FS_CATALOG.'media/products/'.$product.'/',0777);
       //  umask($old_umask);
       //}
      if ($select_file=='default') {
        $accepted_file_upload_files_extensions = array("xls","xla","hlp","chm","ppt","ppz","pps","pot","doc","dot","pdf","rtf","swf","cab","tar","zip","au","snd","mp2","rpm","stream","wav","gif","jpeg","jpg","jpe","png","tiff","tif","bmp","csv","txt","rtf","tsv","mpeg","mpg","mpe","qt","mov","avi","movie","rar","7z");
        $accepted_file_upload_files_mime_types = array("application/msexcel","application/mshelp","application/mspowerpoint","application/msword","application/pdf","application/rtf","application/x-shockwave-flash","application/x-tar","application/zip","audio/basic","audio/x-mpeg","audio/x-pn-realaudio-plugin","audio/x-qt-stream","audio/x-wav","image/gif","image/jpeg","image/png","image/tiff","image/bmp","text/comma-separated-values","text/plain","text/rtf","text/tab-separated-values","video/mpeg","video/quicktime","video/x-msvideo","video/x-sgi-movie","application/x-rar-compressed","application/x-7z-compressed");
        if ($content_file = xtc_try_upload('file_upload', DIR_FS_CATALOG.'media/products/','644',$accepted_file_upload_files_extensions,$accepted_file_upload_files_mime_types)) {
          $content_file_name = $content_file->filename;
          $old_filename = $content_file->filename;
          $timestamp = str_replace('.','',microtime());
          $timestamp = str_replace(' ','',$timestamp);
          $content_file_name = $timestamp.strstr($content_file_name,'.');
          $rename_string = DIR_FS_CATALOG.'media/products/'.$content_file_name;
          rename(DIR_FS_CATALOG.'media/products/'.$old_filename,$rename_string);
          copy($rename_string,DIR_FS_CATALOG.'media/products/backup/'.$content_file_name);
        }
        if ($content_file_name=='')
          $content_file_name=$filename;
      } else {
        $content_file_name = $select_file;
      }

      // update data in table
      // set allowed c.groups
      $group_ids='';
      if(isset($_POST['groups'])) foreach($_POST['groups'] as $b){
        $group_ids .= 'c_'.$b."_group ,";
      }
      $customers_statuses_array=xtc_get_customers_statuses();
      if (strstr($group_ids,'c_all_group')) {
        $group_ids='c_all_group,';
        for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
          $group_ids .='c_'.$customers_statuses_array[$i]['id'].'_group,';
       }
      }

      $sql_data_array = array(
                              'products_id' => $product,
                              'group_ids' => $group_ids,
                              'content_name' => $content_title,
                              'content_file' => $content_file_name,
                              'content_link' => $content_link,
                              'file_comment' => $file_comment,
                              'languages_id' => $content_language);

      if ($id=='update_product') {
        xtc_db_perform(TABLE_PRODUCTS_CONTENT, $sql_data_array, 'update', "content_id = '" . $coID . "'");
        $content_id = xtc_db_insert_id();
      } else {
        xtc_db_perform(TABLE_PRODUCTS_CONTENT, $sql_data_array);
        $content_id = xtc_db_insert_id();
      } // if get id

      // rename filename
      xtc_redirect(xtc_href_link(FILENAME_PRODUCTS_CONTENT,'pID='.$product));
    }// if error
  }

require (DIR_WS_INCLUDES.'head.php');

?>
</head>
<body>
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php');?>
    <!-- header_eof //-->
    <!-- body //-->
    <div class='row'>
        <div class='col-xs-12'>
            <p class='h2'><?php echo HEADING_PRODUCTS_CONTENT;?></p>
        </div>	
	

<?php
                          switch ($action) {
                            case 'edit_products_content':
                            case 'new_products_content':
                              if ($action =='edit_products_content') {
                                $content_query=xtc_db_query("SELECT
                                                              content_id,
                                                              products_id,
                                                              group_ids,
                                                              content_name,
                                                              content_file,
                                                              content_link,
                                                              languages_id,
                                                              file_comment,
                                                              content_read
                                                             FROM ".TABLE_PRODUCTS_CONTENT."
                                                             WHERE content_id='".$g_coID."'
                                                             LIMIT 1"); //DokuMan - 2011-05-13 - added LIMIT 1
                                $content=xtc_db_fetch_array($content_query);
                              }
                              // get products names.
                              $products_query=xtc_db_query("SELECT
                                                                   products_id,
                                                                   products_name
                                                              FROM ".TABLE_PRODUCTS_DESCRIPTION."
                                                             WHERE language_id='".(int)$_SESSION['languages_id']."'
                                                          ORDER BY products_name"); // Tomcraft - 2010-09-15 - Added default sort order to products_name for product-content in content-manager
                              $products_array=array();
                              while ($products_data=xtc_db_fetch_array($products_query)) {
                                $products_array[]=array('id' => $products_data['products_id'],
                                                      'text' => $products_data['products_name']);
                              }

                              // get languages
                              $languages_array = array();
                              for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                if ($languages[$i]['id']==$content['languages_id']) {
                                  $languages_selected=$languages[$i]['code'];
                                  $languages_id=$languages[$i]['id'];
                                }
                                $languages_array[] = array('id' => $languages[$i]['code'],
                                                         'text' => $languages[$i]['name']);
                              }

                              // get used content files
                              $content_files_query=xtc_db_query("SELECT DISTINCT
                                                                                 content_name,
                                                                                 content_file
                                                                            FROM ".TABLE_PRODUCTS_CONTENT."
                                                                           WHERE content_file!=''");
                              $content_files=array();
                              while ($content_files_data=xtc_db_fetch_array($content_files_query)) {
                                $content_files[]=array('id' => $content_files_data['content_file'],
                                                     'text' => $content_files_data['content_name']);
                              }

                              // add default value to array
                              $default_array[]=array('id' => 'default','text' => TEXT_SELECT);
                              $default_value='default';
                              $content_files=array_merge($default_array,$content_files);
                              // mask for product content

                              if ($action !='new_products_content') {
                                echo xtc_draw_form('edit_content',FILENAME_PRODUCTS_CONTENT,'action=edit_products_content&id=update_product&coID='.$g_coID,'post','enctype="multipart/form-data"').xtc_draw_hidden_field('coID',$g_coID);
                              } else {
                                echo xtc_draw_form('edit_content',FILENAME_PRODUCTS_CONTENT,'action=edit_products_content&id=insert_product','post','enctype="multipart/form-data"');
                              }
                                ?>
                                <div class="col-xs-12 main"><?php echo TEXT_CONTENT_DESCRIPTION; ?></div>
                                <div class="main col-xs-12">
                                  <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-1" ><?php echo TEXT_PRODUCT; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_pull_down_menu('product',$products_array,$content['products_id']); ?></div>
                                  </div>
                                  <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-1" ><?php echo TEXT_LANGUAGE; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_pull_down_menu('language',$languages_array,$languages_selected); ?></div>
                                  </div>
                                  <?php
                                    if (GROUP_CHECK=='true') {
                                      $customers_statuses_array = xtc_get_customers_statuses();
                                      $customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
                                      ?>
                                        <div class="main">
                                          <div style="width: 380px; border: 1px solid; border-right: 1px solid; border-color: #ff0000; background:#FFCC33;">
                                            <?php
                                              for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
                                                if (strstr($content['group_ids'],'c_'.$customers_statuses_array[$i]['id'].'_group')) {
                                                  $checked = 'checked ';
                                                } else {
                                                  $checked = '';
                                                }
                                                echo '<input type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'"'.$checked.'> '.$customers_statuses_array[$i]['text'].'<br />';
                                              }
                                            ?>
                                          </div>
                                        </div>
                                      <?php
                                    }
                                  ?>
                                  <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-1" ><?php echo TEXT_TITLE_FILE; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_input_field('cont_title',$content['content_name'],'size="60"'); ?></div>
                                  </div>
                                  <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-1" ><?php echo TEXT_LINK; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_input_field('cont_link',$content['content_link'],'size="60"'); ?></div>
                                  </div>
                                  <div class="col-xs-12">
                                    <div  class="col-xs-12 col-sm-1" ><?php echo TEXT_FILE_DESC; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_textarea_field('file_comment','','100','30',$content['file_comment']); ?></div>
                                  </div>
                                  <div class="col-xs-12">
                                    <div class="col-xs-12 col-sm-1" ><?php echo TEXT_CHOOSE_FILE; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_pull_down_menu('select_file',$content_files,$default_value); ?><?php echo ' '.TEXT_CHOOSE_FILE_DESC; ?></div>
                                  </div>
                                  <div class="col-xs-12">
                                    <div  class="col-xs-12 col-sm-1"><?php echo TEXT_UPLOAD_FILE; ?></div>
                                    <div class="col-xs-12 col-sm-11" ><?php echo xtc_draw_file_field('file_upload').' '.TEXT_UPLOAD_FILE_LOCAL; ?></div>
                                  </div>
                                  <?php
                                    if ($content['content_file']!='') {
                                      ?>
                                      <div class="col-xs-12">
                                        <div class="col-xs-12 col-sm-1" ><?php echo TEXT_FILENAME; ?></div>
                                        <div  class="col-xs-12 col-sm-11"><?php echo xtc_draw_hidden_field('file_name',$content['content_file']).xtc_image('../'. DIR_WS_IMAGES. 'icons/icon_'.str_replace('.','',strstr($content['content_file'],'.')).'.gif').$content['content_file']; //DokuMan - 2011-09-06 - change path ?></div>
                                      </div>
                                      <?php
                                    }
                                  ?>
                                  <div class="col-xs-12 text-right">
                                    <?php echo '<input type="submit" class="btn btn-default" onclick="this.blur();" value="' . BUTTON_SAVE . '"/>'; ?><a class="btn btn-default" onclick="this.blur();" href="<?php echo xtc_href_link(FILENAME_PRODUCTS_CONTENT); ?>"><?php echo BUTTON_BACK; ?></a>
                                  </div>
                                </div>
                              </form>
                              <?php
                              break;
                          }

                if (!$action) {
                    // products content
                    // load products_ids into array
                    $products_id_query=xtc_db_query("SELECT DISTINCT
                                                                     pc.products_id,
                                                                     pd.products_name
                                                                FROM ".TABLE_PRODUCTS_CONTENT." pc,
                                                                     ".TABLE_PRODUCTS_DESCRIPTION." pd
                                                               WHERE pd.products_id=pc.products_id
                                                                 AND pd.language_id='".(int)$_SESSION['languages_id']."'");
                    $products_ids=array();
                    while ($products_id_data=xtc_db_fetch_array($products_id_query)) {
                      $products_ids[]=array('id'=>$products_id_data['products_id'],
                                          'name'=>$products_id_data['products_name']);
                    } // while
                    ?>
                    <?php
                      $total_space_media_products = xtc_spaceUsed(DIR_FS_CATALOG.'media/products/'); // DokuMan - 2011-09-06 - sum up correct filesize avoiding global variable
                      echo '<div class="col-xs-12 main">'.USED_SPACE.xtc_format_filesize($total_space_media_products).'</div></br>';
                    ?>
                <div class="table-responsive col-xs-12">
                    <table class='table table-bordered'>
                      <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent" nowrap width="5%" ><?php echo TABLE_HEADING_PRODUCTS_ID; ?></td>
                        <td class="dataTableHeadingContent" width="95%" align="left"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                      </tr>
                      <?php
                        for ($i=0,$n=sizeof($products_ids); $i<$n; $i++) {
                          echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
                            ?>
                            <td class="dataTableContent_products" align="left"><?php echo $products_ids[$i]['id']; ?></td>
                            <td class="dataTableContent_products" align="left"><b><?php echo xtc_image(DIR_WS_CATALOG.'images/icons/arrow.gif'); ?><a href="<?php echo xtc_href_link(FILENAME_PRODUCTS_CONTENT,'pID='.$products_ids[$i]['id']);?>"><?php echo $products_ids[$i]['name']; ?></a></b></td>
                          </tr>
                          <?php
                          if ($_GET['pID']) {
                            // display content elements
                            $content_query=xtc_db_query("SELECT
                                                                content_id,
                                                                content_name,
                                                                content_file,
                                                                content_link,
                                                                languages_id,
                                                                file_comment,
                                                                content_read
                                                           FROM ".TABLE_PRODUCTS_CONTENT."
                                                          WHERE products_id='".$_GET['pID']."'
                                                       ORDER BY content_name");
                            $content_array=array();
                            while ($content_data=xtc_db_fetch_array($content_query)) {
                              $content_array[]=array('id'=> $content_data['content_id'],
                                                   'name'=> $content_data['content_name'],
                                                   'file'=> $content_data['content_file'],
                                                   'link'=> $content_data['content_link'],
                                                'comment'=> $content_data['file_comment'],
                                           'languages_id'=> $content_data['languages_id'],
                                                   'read'=> $content_data['content_read']);
                            } // while content data

                            if ($_GET['pID']==$products_ids[$i]['id']){
                              ?>
                              <tr>
                                <td class="dataTableContent" align="left"></td>
                                <td class="dataTableContent" align="left">
                                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                    <tr class="dataTableHeadingRow">
                                      <td class="dataTableHeadingContent hidden-xs" nowrap width="2%" ><?php echo TABLE_HEADING_PRODUCTS_CONTENT_ID; ?></td>
                                      <td class="dataTableHeadingContent hidden-xs" nowrap width="2%" >&nbsp;</td>
                                      <td class="dataTableHeadingContent" nowrap width="5%" ><?php echo TABLE_HEADING_LANGUAGE; ?></td>
                                      <td class="dataTableHeadingContent" nowrap width="15%" ><?php echo TABLE_HEADING_CONTENT_NAME; ?></td>
                                      <td class="dataTableHeadingContent hidden-xs" nowrap width="30%" ><?php echo TABLE_HEADING_CONTENT_FILE; ?></td>
                                      <td class="dataTableHeadingContent hidden-xs" nowrap width="1%" ><?php echo TABLE_HEADING_CONTENT_FILESIZE; ?></td>
                                      <td class="dataTableHeadingContent hidden-xs" nowrap align="middle" width="20%" ><?php echo TABLE_HEADING_CONTENT_LINK; ?></td>
                                      <td class="dataTableHeadingContent hidden-xs" nowrap width="5%" ><?php echo TABLE_HEADING_CONTENT_HITS; ?></td>
                                      <td class="dataTableHeadingContent" nowrap width="20%" ><?php echo TABLE_HEADING_CONTENT_ACTION; ?></td>
                                    </tr>
                                    <?php
                                    for ($ii=0,$nn=sizeof($content_array); $ii<$nn; $ii++) {
                                      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
                                      ?>
                                        <td class="dataTableContent hidden-xs" align="left"><?php echo  $content_array[$ii]['id']; ?> </td>
                                        <td class="dataTableContent hidden-xs" align="left">
                                          <?php
                                            if ($content_array[$ii]['file']!='') {
                                              echo xtc_image('../'. DIR_WS_IMAGES.'icons/icon_'.str_replace('.','',strstr($content_array[$ii]['file'],'.')).'.gif'); //web28 - 2010-09-03 - change path
                                            } else {
                                              echo xtc_image('../'. DIR_WS_IMAGES.'icons/icon_link.gif'); //web28 - 2010-09-03 - change path
                                            }
                                            for ($xx=0,$zz=sizeof($languages); $xx<$zz;$xx++){
                                              if ($languages[$xx]['id']==$content_array[$ii]['languages_id']) {
                                                $lang_dir=$languages[$xx]['directory'];
                                                break;
                                              }
                                            }
                                          ?>
                                        </td>
                                        <td class="dataTableContent" align="left"><?php echo xtc_image(DIR_WS_CATALOG.'lang/'.$lang_dir.'/admin/images/icon.gif'); ?></td>
                                        <td class="dataTableContent" align="left"><?php echo $content_array[$ii]['name']; ?></td>
                                        <td class="dataTableContent hidden-xs" align="left"><?php echo $content_array[$ii]['file']; ?></td>
                                        <td class="dataTableContent hidden-xs" align="left"><?php echo xtc_filesize($content_array[$ii]['file']); ?></td>
                                        <td class="dataTableContent hidden-xs" align="left" align="middle">
                                          <?php
                                            if ($content_array[$ii]['link']!='') {
                                              echo '<a href="'.$content_array[$ii]['link'].'" target="new">'.$content_array[$ii]['link'].'</a>';
                                            }
                                          ?>
                                          &nbsp;
                                        </td>
                                        <td class="dataTableContent hidden-xs" align="left"><?php echo $content_array[$ii]['read']; ?></td>
                                        <td class="dataTableContent" align="left">
                                          <a href="<?php echo xtc_href_link(FILENAME_PRODUCTS_CONTENT,'special=delete_product&coID='.$content_array[$ii]['id']).'&pID='.$products_ids[$i]['id']; ?>" onclick="return confirm('<?php echo CONFIRM_DELETE; ?>')">
                                          <?php
                                            echo xtc_image(DIR_WS_ICONS.'delete.gif', ICON_DELETE,'','','style="cursor:pointer" onclick="return confirm(\''.DELETE_ENTRY.'\')"').'  '.TEXT_DELETE.'</a>&nbsp;&nbsp;';
                                          ?>
                                          <a href="<?php echo xtc_href_link(FILENAME_PRODUCTS_CONTENT,'action=edit_products_content&coID='.$content_array[$ii]['id']); ?>">
                                            <?php
                                            echo xtc_image(DIR_WS_ICONS.'icon_edit.gif', ICON_EDIT,'','','style="cursor:pointer"').'  '.TEXT_EDIT.'</a>';
                                          // display preview button if filetype in array
                                          $allowed_filetypes = array('.gif','.jpg','.png','.html','.htm','.txt','.bmp'); 
                                          if (in_array(substr($content_array[$ii]['file'], 0, strrpos($content_array[$ii]['file'], '.') - 1), $allowed_filetypes)) {
                                            ?>
                                            <a style="cursor:pointer" onclick="javascript:window.open('<?php echo xtc_href_link(FILENAME_CONTENT_PREVIEW,'pID=media&coID='.$content_array[$ii]['id']); ?>', 'popup', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no, width=640, height=600')">
                                              <?php
                                              echo xtc_image(DIR_WS_ICONS.'preview.gif', ICON_PREVIEW,'','',' style="cursor:pointer"').'&nbsp;&nbsp;'.TEXT_PREVIEW.'</a>';
                                          }
                                          ?>
                                        </td>
                                      </tr>
                                      <?php
                                    } // for content_array
                                  echo '    </table>';
                                echo '  </td>';
                              echo '</tr>';
                            }
                          } // for
                        }
                      ?>
                    </table>
                    <a class="btn btn-default" onclick="this.blur();" href="<?php echo xtc_href_link(FILENAME_PRODUCTS_CONTENT,'action=new_products_content'); ?>"><?php echo BUTTON_NEW_CONTENT; ?></a>
                    
                    <?php
                  } // if !$action
                ?>
              </td>
            </tr>
          </table>
        </div>
    </div>
    
    <!-- body_eof //-->
    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
    <?php
    if (USE_WYSIWYG=='true') {
      $query=xtc_db_query("SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".$_SESSION['languages_id']."'");
      $data=xtc_db_fetch_array($query);
      if ($action =='new_products_content')
        echo xtc_wysiwyg('products_content',$data['code']);
      if ($action =='edit_products_content')
        echo xtc_wysiwyg('products_content',$data['code']);
    }
    ?>
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
