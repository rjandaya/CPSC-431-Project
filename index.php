<?php
require_once('drive_fns.php');
session_start();
do_html_header('mini drive');


display_site_info(); 
if (isset($_SESSION['valid_user']))  { 
    check_valid_user();
    display_search_form();
    display_user_files($_SESSION['valid_user']);
    display_user_menu();
} else {
    display_login_form();
}

do_html_footer();
?>