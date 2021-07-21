<?php
    require_once('drive_fns.php');
    session_start();

    do_html_header('Search Files');
    check_valid_user();
    display_search_form();
    
    // Get the files this user has uploaded
    display_user_files($_SESSION['valid_user']);
    display_user_menu();
    do_html_footer();


?>