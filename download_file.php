<?php
    /** MAKE THIS LOOK LIKE delete_file.php (delete_bms.php in Chapter 27)
    require_once('drive_fns.php');
    session_start();

    // Create short variable names
    $dow_me = $_POST['dow_me'];
    $valid_user = $_SESSION['valid_user'];

    do_html_header('Download File(s)');
    check_valid_user();

    if (!filled_out($_POST)) {
        echo '<p>You have not chosen any files to download.<br>
        Please try again.</p>';
        display_user_menu();
        do_html_footer();
        exit();
    } else {
        if (count($dow_me) > 0) {
            foreach($dow_me as $file) {
                if (download_file($valid_user, $file)) {
                    echo 'Downloaded '.htmlspecialchars($url).'<br>';
                } else {
                    echo 'Could not download '.htmlspecialchars($file).'<br>';
                }
            }
        } else {
            echo 'No files selected for download';
        }
    }
    // Get the files this user has uploaded
    display_user_files($_SESSION['valid_user']);
    display_user_menu();
    do_html_footer();
    **/
    if(isset($_GET['path'])) {
        //Read the filename
        $filename = $_GET['path'];
        //Check the file exists or not
        if(file_exists($filename)) {

        //Define header information
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));
        header('Pragma: public');

        // Clear system output buffer
        flush();

        // Read the size of the file
        readfile($filename);

        // Terminate from the script
        die();
        } else{
            echo "File does not exist.";
        }
    } else
        echo "Filename is not defined."
?>