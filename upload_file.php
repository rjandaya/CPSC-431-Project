<?php
require_once('drive_fns.php');
session_start();

// Create short variable name
// $new_file = $_POST['fileToUpload'];

do_html_header('Uploading file(s)');


try {
    check_valid_user();
    display_search_form();
    if (!filled_out($_POST)) {
        throw new Exception('Form not completely filled out.');
    }

    // Check file is valid
    if ($_FILES['fileToUpload']['error'] > 0) {
        $submit_ok = 0;
        echo 'Problem: ';
        switch ($_FILES['fileToUpload']['error']) {
            case 1:
                echo 'File exceeded upload_max_filesize.';
                break;
            case 2:
                echo 'File exceeded max_file_size.';
                break;
            case 3:
                echo 'File only partially uploaded.'; 
                break;
            case 4:
                echo 'No file selected.';
                break;
            case 6:
                echo 'Cannot upload file: No temp directory specified.';
                break;
            case 7:
                echo 'Upload failed: Cannot write to disk.';
                break;
        }
    } else {
        upload_file($target_file);
        // echo 'File Uploaded';
    }

    

} catch (Excpetion $e) {
    echo $e->getMessage();
}
// Get the files this user has uploaded
display_user_files($_SESSION['valid_user']);
display_user_menu();
do_html_footer();
?>