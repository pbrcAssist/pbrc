<?php
session_start();

// Hostinger
$GLOBALS['login_dir'] = '/web/main/admin/login.php';
$GLOBALS['admin_dir'] = '/web/main/admin/index.php';
$GLOBALS['page_login'] = 'https://pbrc.pcbics.net/web/main/admin/login.php';
$GLOBALS['page_admin'] = 'https://pbrc.pcbics.net/web/main/admin/index.php';

// DEV
// $GLOBALS['login_dir'] = '/pbrc/web/main/admin/login.php';
// $GLOBALS['admin_dir'] = '/pbrc/web/main/admin/index.php';
// $GLOBALS['page_login'] = 'http://localhost/pbrc/web/main/admin/login.php';
// $GLOBALS['page_admin'] = 'http://localhost/pbrc/web/main/admin/index.php';

function redirectHeader($url) {
    header('Location: '.$url);
    exit();
}

function isLoginPage(){
    return $_SERVER['PHP_SELF'] == $GLOBALS['login_dir'];
}

function isAdminDirectory(){
    return $_SERVER['PHP_SELF'] == $GLOBALS['admin_dir'];
}

function hasLoggedInUser(){
    return isset($_SESSION['admin_detail']);
}

function redirectUser(){
    // print_r($_SERVER);
    if(isAdminDirectory()){
        if(!hasLoggedInUser()){
            redirectHeader($GLOBALS['page_login']);
        }   
    } else if(isLoginPage()) {
        if(hasLoggedInUser()){
            redirectHeader($GLOBALS['page_admin']);
        }   
    }
}

function adminRedirection(){
    if(hasLoggedInUser()){
        redirectHeader($GLOBALS['page_admin']);
    } else {
        redirectHeader($GLOBALS['page_login']);
    }
}


redirectUser();
?>