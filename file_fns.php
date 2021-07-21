<?php
require_once('db_fns.php');
$target_dir = "/home/titan0/cs431s/cs431s39/homepage/project/uploads/";
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);

function get_user_files($username) {
    // Extract from the database all the files this user has stored

    $conn = db_connect();

    $query = "select FName, FileType, DateAdded, Size from file where Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    return $stmt;

}

function upload_file($target_file) {
    // Add new file to the database
    
    // Create short variable names
    $filename=basename($_FILES["fileToUpload"]["name"]);
    $filetype = end((explode(".", $filename)));
    $filesize = $_FILES["fileToUpload"]["size"];
    $valid_user = $_SESSION['valid_user'];
    date_default_timezone_set("America/Los Angeles");
    $dateadded = date("Y-m-d");

    // Attempt to upload file to server if previous checks are OK  
    $conn = db_connect();

    if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
        try {
            if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                throw new Exception("Could not move file to destination");
            } else {
                echo 'File uploaded successfully.';
            }
            
            // Add metadata to database
            if (!$conn->query("insert into file values ('".$valid_user."', '".$filename."', '".$filetype."', '".$dateadded."', ".$filesize.")")) {
                throw new Exception('<br>File could not be inserted');
            }
        } catch (Exception $e) {
            echo $e->getMessage(), '<br />';          
        }
               
    } else {
        echo 'Problem: Possible file upload attack. Filename: ';
        echo $_FILES['fileToUpload']['name'];
    }
}

function delete_file($user, $file) {
    // Delete one file from the database
    $conn = db_connect();

    // Delete the file
    if (!$conn->query("delete from file where
                        Username='".$user."'
                        and FName='".$file."'")) {
        throw new Exception ('File could not be deleted');
    }
    return true;
}

function download_file($user, $file) {
    // Download one file from the database
}

function search_file($username, $searchquery, $selection) {
    // Search file
    $conn = db_connect();
    $searchquery = '%'.$searchquery.'%';
    // echo $username.$searchquery.$selection.'<br>';
    
    $query = "select FName, FileType, DateAdded, Size from file where Username = ? and $selection like ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $username, $searchquery);
    $stmt->execute();
    $stmt->store_result();

    return $stmt;
}
?>