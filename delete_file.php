<?php
    /** MAKE THIS LOOK LIKE delete_bms.php in Chapter 27 **/
    require_once('drive_fns.php');
    session_start();

    // Create short variable names
    $del_me = $_POST['del_me'];
    $valid_user = $_SESSION['valid_user'];

    do_html_header('Delete File(s)');
    check_valid_user();
    display_search_form();

    if (!filled_out($_POST)) {
        echo '<p>You have not chosen any files to delete.<br>
        Please try again.</p>';
        display_user_menu();
        do_html_footer();
        exit();
    } else {
        if (count($del_me) > 0) {
            foreach($del_me as $file) {
                if (delete_file($valid_user, $file)) {
                    echo 'Deleted '.htmlspecialchars($file).'<br>';
                } else {
                    echo 'Could not delete '.htmlspecialchars($file).'<br>';
                }
            }
        } else {
            echo 'No files selected for deletion...';
        }
    }
    // Get the files this user has uploaded
    display_user_files($_SESSION['valid_user']);
    display_user_menu();
    do_html_footer();
?>
 
