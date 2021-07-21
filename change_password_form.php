<?php
 require_once('drive_fns.php');
 session_start();
 do_html_header("Change user password");
//  check_admin_user();

 display_password_form();

 do_html_url("home.php", "Back to dashboard");
 do_html_footer();
?>
