<?php
if(!defined('base_url')) define('base_url','https://pbrc.pcbics.net/web/main/resources/');
if(!defined('foundation_url')) define('foundation_url',base_url.'foundation/');
if(!defined('foundation_resources_url')) define('foundation_resources_url',base_url.'foundation/resources/');
if(!defined('plugins_url')) define('plugins_url',base_url.'web/resources/plugins/');
if(!defined('resource_url')) define('resource_url',base_url.'web/resources/');
if(!defined('user_url')) define('user_url',base_url.'web/main/user/');
if(!defined('admin_url')) define('admin_url',base_url.'web/main/admin/');
if(!defined('common_url')) define('common_url',base_url.'web/main/common/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );

// hostinger
if(!defined('DB_SERVER')) define('DB_SERVER',"127.0.0.1:3306");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"u293681336_pbrc");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"Pbrc1234");
if(!defined('DB_NAME')) define('DB_NAME',"u293681336_pbrc");

// local
// if(!defined('DB_SERVER')) define('DB_SERVER',"localhost:3307");
// if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
// if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
// if(!defined('DB_NAME')) define('DB_NAME',"pbrc_db");

$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');
if(!defined('dev_data')) define('dev_data',$dev_data);
?>