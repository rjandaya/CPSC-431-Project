<?php

function filled_out($form_vars) {

  // test that each variable has a value
  foreach ($form_vars as $key => $value) {
     if ((!isset($key)) || ($value == '')) {
        return false;
     }
  }
  return true;
}

function valid_email($address) {
  // check an email address is possibly valid
  if (preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/', $address)) {
    return true;
  } else {
    return false;
  }
}

function preventInjection($string){

  if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $string)) {
    echo "Please only use alphanumeric characters! We see you trying to inject!";
    die();
  }
}

?>
