<?php
  require_once("drive_fns.php");
  do_html_header("Resetting password");

  // creating short variable name
  $email = $_POST['email'];

  try {
    $password = reset_password($email);
    notify_password($email, $password);
  }
  catch (Exception $e) {
    echo 'Your password could not be reset - please try again later.';
  }
  do_html_url('index.php', 'Login');
  do_html_footer();
?>
