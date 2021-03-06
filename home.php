<?php
// Authors: RJ Andaya, Adrian Lawson
// CPSC 431-01

// ini_set('display_errors', '1');

// include function files for this application
require_once('drive_fns.php');
session_start();

if (!isset($_SESSION['valid_user']))  { 

    //create short variable names
    if (!isset($_POST['username']))  {
        //if not isset -> set with dummy value 
        $_POST['username'] = " "; 
    }
    $username = $_POST['username'];
    preventInjection($username);
    if (!isset($_POST['passwd']))  {
        //if not isset -> set with dummy value 
        $_POST['passwd'] = " "; 
    }
    $passwd = $_POST['passwd'];
    preventInjection($passwd);

    if ($username && $passwd) {
    // they have just tried logging in
        try  {
            login($username, $passwd);
            // if they are in the database register the user id
            $_SESSION['valid_user'] = $username;
        } catch(Exception $e)  {
            // unsuccessful login
            do_html_header('Problem:');
            echo 'You could not be logged in.<br>
                You must be logged in to view this page.';
            do_html_url('index.php', 'Login');
            do_html_footer();
            exit;
        }
    }
}
do_html_header('Home');
check_valid_user();
display_search_form();
display_user_files($_SESSION['valid_user']);
display_user_menu();
do_html_footer();
/** THIS SHOULD BE THE NEW END OF FILE **/

/** MOVE HTML DISPLAY FUNCTIONS TO out_fns.php **/
$target_dir = "/home/titan0/cs431s/cs431s39/homepage/project/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$submit_ok = 0;

$filename=basename($_FILES["fileToUpload"]["name"]);

?>


<!DOCTYPE html>
<html>
<head>
    <title>Photo Gallery</title>
    <link href="styles.css" type="text/css" rel="stylesheet">
</head>
<body>
<header>
    <!-- <img src="rjandaya.png" alt="RJA Logo" height="70" width="70"> -->
    <!-- <h1>RJ and Adrian's Photo Upload Gallery</h1> -->
    <!-- menu -->
    <!-- <nav>
        <div class="menuitem">
        <a href="index.html">
        <span class="menutext">Home</span></a>
        </div>

        <div class="menuitem">
            <a href="gallery.php">
            <span class="menutext">Gallery</span></a>
        </div>
        <div class="menuitem">
            <a href="upload.php">
            <span class="menutext">Upload</span></a>
        </div> -->
            <!-- Dropdown using select -->
        <!-- <select class="select" name="selection" id="selection" onchange="location = this.value;">
            <option value="">Sort by:</option>
            <option value="gallery.php">Date Added</a></option>
            <option value="gallery.php?sort=name">Name</a></option>
            <option value="gallery.php?sort=datetaken">Date</a></option>
            <option value="gallery.php?sort=location">Location</a></option>
            <option value="gallery.php?sort=photographer">Photographer</a></option>
        </select><br/>
    </nav> -->
</header>
    <!-- <h2>Submission Results</h2> -->

    <?php

    // Connect to database
	try {
    @$db = new mysqli('mariadb', 'cs431s39', 'xooM8she', 'cs431s39');

    if (mysqli_connect_errno()) {
        echo "<p>Error: Could not connect to the database.<br />
                Please try again.</p>";
			throw new Exception(mysqli_connect_error());
    }
	} catch (Exception $e) {
		echo "<p>If the issue persists, contact the website owner.</p>";
		exit;
	}


    // Check if submit button was pressed
    if (isset($_POST['submit'])) {

        // Form data validation
        if ($name == '' || $datetaken == '' || $location == '' || $photographer == '') {
            echo "<p>Problem: You have not entered all the required details.<br />
                    Please go back and try again</p>";
        } else {
            // Disallowed entry checks
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $datetaken)) {
                $submit_ok = 1;
            } else {
                echo 'Invalid date';
                $submit_ok = 0;
            }
        }

        // Check for valid upload. If not, skip upload attempt
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
            // Does the file have the right MIME type?
            if ($_FILES['fileToUpload']['type'] != 'image/png' 
                && $_FILES['fileToUpload']['type'] != 'image/jpeg' 
                && $_FILES['fileToUpload']['type'] != 'image/gif') {
                echo 'Problem: File is not a PNG/JPEG/GIF image. ('.$_FILES['fileToUpload']['type'].')';
                $submit_ok = 0;
            } else {
                // Attempt to upload file to server if previous checks are OK
                if (is_uploaded_file($_FILES['fileToUpload']['tmp_name']) && $submit_ok == 1) {
                    try{
                    if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
                        throw new Exception("Could not move file to destination");
                    } else {
                        echo 'File uploaded successfully.';}
                        // Add metadata to database
                        $query = "INSERT INTO images (Filename, Name, DateTaken, Location, Photographer) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('sssss', $filename, $name, $datetaken, $location, $photographer);
                        $stmt->execute();

                        if ($stmt->affected_rows > 0) {
                            echo "<p>Image metadata inserted into the database.</p>";
                        } else {
                            echo "<p>An Error has occurred.<br />
                                    The item was not added.</p>";
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage(), '<br />';
                        //exit;
                        
                    }
                        
                
                } else {
                    echo 'Problem: Possible file upload attack. Filename: ';
                    echo $_FILES['fileToUpload']['name'];
                }
            }
        }
    }
    
    /* ISSUE IS IN THIS BLOCK
    // View Gallery by default sort (date added)
    if (!isset($selection)) {
        $query = "SELECT Filename, Name, DateTaken, Location, Photographer FROM images";
    } else {
           // Sort
        //    echo ($_GET['sort']);
           switch ($_GET['sort']) {
               case 'name':
               case 'datetaken':
               case 'location':
               case 'photographer':
                    $selection = $_GET['sort'];
                    break;
               default:
                   echo '<h1>WARNING: Invalid??input??value??specified.</h1>';
           }
        $query = "SELECT Filename, Name, DateTaken, Location, Photographer FROM images ORDER BY ".$selection;
    }
    
		try{
    $stmt = $db->prepare($query);
		if(!$stmt) {
			throw new Exception("Problem with query");
		}
		} catch (Exception $e) {
			echo "Error: there was a problem when querying database";
			exit;
}    

	 $stmt->execute();
    $stmt->store_result();
	

    $stmt->bind_result($filename, $name, $datetaken, $location, $photographer);
    echo "<p>Number of images found: ".$stmt->num_rows."</p>";

		
    while ($stmt->fetch()) {
        ?>
        <ul class="image">
        <img src="uploads/<?php echo $filename; ?>">
        <!-- <li><?php echo $filename;?></li> -->
        <li><?php echo $name;?></li>
        <li><?php echo $datetaken;?></li>
        <li><?php echo $location;?></li>
        <li><?php echo $photographer;?></li>
        </ul>

        <?php
    }

    $db->close();

    /** END ISSUE BLOCK **/
    ?>
</body>
</html>