<?php
require_once('drive_fns.php');

function do_html_header($title) {
  // print an HTML header
?>
<!doctype html>
  <html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <style>
      html { 
          background-color: whitesmoke;
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      }
      body { font-size: 13px }
      li, td { font-size: 13px }
      hr { color: #3333cc; }
      a { color: #000 }
      h1 {
          color: teal;
          text-align:center;
          position: fixed; 
          /* top: -10%;  */
          left: 50%; 
          -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
      }
      div.formblock { 
          background-color: white; 
          width: 300px; 
          padding: 10px; 
          border-radius: 8px;
          position: fixed; 
          top: 50%; 
          left: 50%; 
          -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
        }
        p.formblock { text-align: center }
        button.formblock { 
            background-color: teal;
            border: none;
            border-radius: 4px;
            font-weight: bolder;
            color: white;
            padding: 5px 68px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        button.formblock:hover {
            background-color: #007575;
            color: white;
        }
        img.gallery {
          display: block;
          margin-left: auto;
          margin-right: auto;
        }
        div.form-group { text-align: center }
        .centered {
          margin: auto;
          width: 60%;
          padding: 10px;
        }
    </style>
  </head>
  <body>
  <div>
    <a href="index.php"><img src="rjandaya.png" alt="RJ Andaya logo" height="55" width="57" style="float: left; padding-right: 6px;" /></a><br/>
      <h1>mini drive</h1>
  </div>
  <!-- <hr /> -->
<?php
  if($title) {
    do_html_heading($title);
  }
}

function do_html_footer() {
  // print an HTML footer
?>
  </body>
  </html>
<?php
}

function do_html_heading($heading) {
  // print heading
?>
  <!-- <h2><?php echo '<br>'.$heading;?></h2> -->
<?php
}

function do_html_URL($url, $name) {
  // output URL as link and br
?>
  <br><a href="<?php echo $url;?>"><?php echo $name;?></a><br>
<?php
}

function display_site_info() {
  // display some marketing info
?>
  <!-- <ul>
  <li>Store your bookmarks online with us!</li>
  <li>See what other users use!</li>
  <li>Share your favorite links with others!</li>
  </ul> -->
<?php
}

function display_login_form() {
?>
  <form method="post" action="home.php">
  <div class="formblock">

    <p class="formblock"><br/>
    <input type="text" name="username" id="username" placeholder="Username" /></p>

    <p class="formblock">
    <input type="password" name="passwd" id="passwd" placeholder="Password"/></p>

    <p class="formblock"><button class="formblock" type="submit">Log In</button></p>

    <p class="formblock"><a href="forgot_form.php">Forgot your password?</a></p>

    <p class="formblock"><a href="register_form.php">Not a member?</a></p>
  </div>

 </form>
<?php
}

function display_registration_form() {
?>
 <form method="post" action="register_new.php">

 <div class="formblock">
    <h2>Register Now</h2>

    <p><label for="email">Email Address:</label><br/>
    <input type="email" name="email" id="email" 
      size="30" maxlength="100" required /></p>

    <p><label for="username">Preferred Username <br>(max 16 chars):</label><br/>
    <input type="text" name="username" id="username" 
      size="16" maxlength="16" required /></p>

    <p><label for="passwd">Password <br>(between 6 and 16 chars):</label><br/>
    <input type="password" name="passwd" id="passwd" 
      size="16" maxlength="16" required /></p>

    <p><label for="passwd2">Confirm Password:</label><br/>
    <input type="password" name="passwd2" id="passwd2" 
      size="16" maxlength="16" required /></p>


    <button type="submit">Register</button>

   </div>

  </form>
<?php

}

function display_search_form() {
  // Display the search bar
  ?>

  <form name="search" action="search_file.php" method="post" enctype="multipart/form-data">
  <div><select class="select" name="selection" id="selection">
    <!-- <option value="Default">Search by:</option> -->
    <option value="FName">File Name</option>
    <option value="FileType">Type</option>
    <option value="DateAdded">Date Added</option>
    <option value="Size">Size</option>
    </select><br/>
  <input type="text" name="search_query" id="search_query" placeholder="Search Files"/>
  <button type="submit">Search</button>
  </form>
  <?php
}


function display_upload_form() {
  // Display the form for people to upload a new file
  ?>
  <form action="upload_file.php" method="post" enctype="multipart/form-data">
      Upload File(s):
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload File(s)" name="submit">
    </form>

    <?php
}

function display_user_files($username) {
  // Display the gallery to preview the user's files
  ?>
    <form name="file_gallery" action="delete_file.php" method="post">
    <!-- <div class="centered"> -->

  <?php

  global $file_gallery;
  $file_gallery = true;

  $search_query = $_POST['search_query'];
  $selection = $_POST['selection'];
  // echo $search_query.$selection;
  preventInjection($search_query);
  
  if ($search_query != '' && $selection != '') {
    $stmt = search_file($username, $search_query, $selection);
  } else {
    $stmt = get_user_files($username);
  }

  $stmt->bind_result($filename, $filetype, $dateadded, $filesize);

  // echo 'number of files: '.$stmt->num_rows;
  ?>

    <br />
    <br />

  <?php

  while ($stmt->fetch()) {
    ?>
    <ul class="image"
    style=" display: inline-block;
    list-style: none;
    width: 250px;
    height: 250px;
    align: center;
    ">

   
    <?php

    if ($filetype == 'png' || $filetype == 'jpeg' || $filetype == 'jpg') {
      echo '<img class="gallery" style="max-width: 250px; max-height: 300px;" src="uploads/'.$filename.'">';
    } else {
        echo '<img class="gallery" style="max-width: 150px; max-height: 150px;" src="fileicons/'.$filetype.'.png">';
    }
    ?>
    
    <li style="list-style: none; margin-top: -5%;"><?php echo "<p><strong>".substr($filename,0,25); if (strlen($filename) > 25) echo "..."; "</strong>"; ?>
    <input type="checkbox" name="del_me[]" value="<?php echo $filename; ?>">
    </li>
    <li style="list-style: none; margin-top: -10%;"><?php echo "<br />Type: ".$filetype; ?>                 </li>
    <li style="list-style: none; margin-top: -5%;"><?php echo "<br />Date Added: ".$dateadded; ?>          </li>
    <li style="list-style: none; margin-top: -5%;"><?php echo "<br />Size: ".$filesize; ?> bytes           </li>
    <li style="list-style: none; margin-top: 2%;"><a href="download_file.php?path=uploads/<?php echo "$filename"; ?>">Download File</a></li>
    </ul>
    <!-- </div> -->
    </form>

    <?php
  }

}

function display_user_menu() {
  // display the menu options on this page
?>
<hr>
<a href="upload_file_form.php">Upload File</a> &nbsp;|&nbsp;
<?php
  // only offer the delete option if bookmark table is on this page
  global $file_gallery;
  if ($file_gallery == true) {
    echo "<a href=\"#\" onClick=\"file_gallery.submit();\">Delete File(s)</a> &nbsp;|&nbsp;";
  } else {
    echo "<span style=\"color: #cccccc\">Delete File(s)</span> &nbsp;|&nbsp;";
  }
?>
<a href="change_password_form.php">Change password</a><br>
<!-- <a href="recommend.php">Recommend URLs to me</a> &nbsp;|&nbsp; -->
<a href="logout.php">Logout</a>
<hr>

<?php
}

function display_password_form() {
  // display html change password form
?>
   <br>
   <form action="change_password.php" method="post">

 <div class="formblock">
    <h2>Change Password</h2>

    <p><label for="old_passwd">Old Password:</label><br/>
    <input type="password" name="old_passwd" id="old_passwd" 
      size="16" maxlength="16" required /></p>

    <p><label for="passwd2">New Password:</label><br/>
    <input type="password" name="new_passwd" id="new_passwd" 
      size="16" maxlength="16" required /></p>

    <p><label for="passwd2">Repeat New Password:</label><br/>
    <input type="password" name="new_passwd2" id="new_passwd2" 
      size="16" maxlength="16" required /></p>


    <button type="submit">Change Password</button>

   </div>
   <br>
<?php
}

function display_forgot_form() {
  // display HTML form to reset and email password
?>
   <br>
   <form action="forgot_passwd.php" method="post">
 <div class="formblock">
    <h2>Forgot Your Password?</h2>

    <p class="formblock"><label for="username">Enter Your Email:</label><br/>
    <input type="text" name="email" id="username" 
      size="25" maxlength="50" placeholder="Email" required /></p>

    <p class="formblock"><button type="submit">Change Password</button></p>

   </div>
   <br>
<?php
}

?>
