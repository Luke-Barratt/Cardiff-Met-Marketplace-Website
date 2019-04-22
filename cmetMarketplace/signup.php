<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Luke Barratt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMET Marketplace Sign up</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/signup.css">
    <script type="text/javascript" src="validation.js"></script>
</head>
<body>
    <!---PHP code to obtain the form data submitted by the user--->
<?php
    // declare variables and set to empty values.
    $forename = $surname = $username = $password = 
    $repeatPassword = $email = $repeatEmail = "";
    $profilePic = "";

    // declare variables to hold error messages for each field.
    $forenameError = $surnameError = $usernameError = 
    $passwordError = $repeatPasswordError = $emailError = $repeatEmailError = "";
    $foundErrors = false;
    $profilePicError = "";

    // if the form has been submitted, AND the method is POST.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // validate forename
        if(empty($_POST["forename"])){
            $forenameError = "Forename is required";
            $foundErrors = true;
        }
        else {
            
            if(!preg_match("/^[a-zA-Z ]*$/",$_POST["forename"])) { 
                $forenameError = "Only letters and white space allowed";
                $foundErrors = true;
            }
            else  {
                $forename = clearUserInputs($_POST["forename"]);
            }
        }
        
        // validate surname
        if(empty($_POST["surname"])){
            $surnameError = "Surname is required";
            $foundErrors = true;
        }
        else {
            
            if(!preg_match("/^[a-zA-Z ]*$/",$_POST["surname"])) { 
                $surnameError = "Only letters and white space allowed";
                $foundErrors = true;
            }
            else  {
                $surname = clearUserInputs($_POST["surname"]);
            }
        }
        
        // validate username
        if(empty($_POST["username"])) {
            $usernameError = "Username is required";
            $foundErrors = true;
        }
        else {
            
            if(strlen($_POST["username"]) <= '5') {
                $usernameError = "Username must be at least 6 characters";
                $foundErrors = true;
            }
            elseif (!preg_match("/^[a-zA-Z0-9_-]*$/",$_POST["username"])) {
                $usernameError = "Only a-z, A-Z, 0-9, - and _ allowed in Usernames";
                $foundErrors = true;
            }
            else {
                $username = clearUserInputs($_POST["username"]);
            }
        }
        
        // validate password
        if(empty($_POST["password"])){
            $passwordError = "Password is required";
            $foundErrors = true;
        }
        else {
            
            if(strlen($_POST["password"]) <= '7') {
                $passwordError = "Password must contain min 8 characters";
                $foundErrors = true;
            }
            elseif (!preg_match("/[0-9]+/",$_POST["password"]) || (!preg_match("/[A-Z]+/",$_POST["password"]))) {
                $passwordError = "Password must contain at least one number and one upper case letter";
                $foundErrors = true;
            }
            else {
                $password = clearUserInputs($_POST["password"]);
            }
        }
        
        // validate email
        if(empty($_POST["email"])){
            $emailError = "Email is required";
            $foundErrors = true;
        }
        else {
            
            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $email = clearUserInputs($_POST["email"]);
            }
            else {
                $emailError = "Invalid email address";
                $foundErrors = true;
            }
        }

        // validate repeat password
        if(empty($_POST["repeatpassword"])) {
            $repeatPasswordError = "Repeat Password is required";
        }
        else {
            if($password !== $repeatPassword) {
                $repeatPasswordError = "Passwords do not match";
            }
            else {
                $repeatPassword = clearUserInputs($_POST["repeatpassword"]);
            }
        }

        // validate repeat email
        if(empty($_POST["repeatemail"])) {
            $repeatEmailError = "Repeat Email is required";
        }
        else {
            if($email !== $repeatEmail) {
                $repeatEmailError = "Emails do not match";
            }
            else {
                $repeatEmail = clearUserInputs($_POST["repeatemail"]);
            }
        }
    


        // input file details
        $imageok = false;

        //extract selected file details: 
        $file_name = $_FILES['profilepic']['name'];
        $file_size = $_FILES['profilepic']['size'];
        $file_tmp = $_FILES['profilepic']['tmp_name'];
        $file_type = $_FILES['profilepic']['type'];
        $basename_file = basename($_FILES['profilepic']['name']);
        $file_ext = strtolower(pathinfo($basename_file, PATHINFO_EXTENSION));
        
        $allowed_extensions= array("jpeg", "jpg", "png");

        //validate image upload
        if ($file_size > 500000) {
            $profilePicError .= "Sorry, your file is too large. <br>";
            $imageok = false;
        } 
        else if(in_array($file_ext, $allowed_extensions) === false) {
            $profilePicError .= "Only JPEG, PNG and JPG files are allowed. <br>";
            $imageok = false;
        } 
        else {
            $imageok = true;
        }

        // if no errors are found make a connection to the database
        if($foundErrors == false) {
            $servername = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $databasename = "cmetmarketplace";

            // PHP code to save form data to the MySQL database
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$databasename", 
                                    $dbusername, $dbpassword);
                
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully <br>";

                // insert user data into mySQLi database
                $sql = "INSERT INTO users
                (username,
                password,
                forename,
                surname,
                email,
                profil_pic)
                values(?, ?, ?, ?, ?, ?)";
            
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username, $password, $forename, $surname, $email, $profilePic]);

                echo "New user added to the database: ";


                // code to handle the image upload
                // code to handle updating users table with image location
                $last_user_id = $conn->lastInsertId();
                echo "New user added with ID: " .$last_user_id;

                if (!$imageok) {
                    $profilePicError .= "Selected image is valid <br>";
                }
                else {
                    $target_dir = "images\\".$last_user_id."\\profilepics\\";
                    if (file_exists($target_dir)) {
                        echo "<br>The folder $target_dir exists <br>";
                    } 
                    else {
                        echo "<br>The folder $target_dir does not exists <br>";
                        if (!mkdir($target_dir, 0775, true)) {
                            die('Failed to create folders...');
                        }
                    }


                    $unique_name = uniqid()."-".$basename_file;

                    $target_file = $target_dir . $unique_name;
                    if (move_uploaded_file($file_tmp, $target_file)) {
                        $fileuploadok = true;
                        echo "The file ".$basename_file. " has been uploaded.";
                    } else {
                        $fileuploadok = false;
                        $profilePicError .= "Sorry, there was an error uploading your file. <br>";
                    }
            
                    $sql = "UPDATE `users` SET `profil_pic` = ? WHERE `user_id` = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$target_dir, $last_user_id]);
                }
            }

            catch (PDOException $e) {
                
                $errorkey = "Integrity constraint violation: 1062 Duplicate entry";

                if (strpos($e->getMessage(), $errorkey) > 0) {
                    $usernameError = "Username already exists";
                }
                else {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
        }    
    }

    //function to clear userinputs.
    function clearUserInputs($data){
        // strips whitespace from the beginning and end of string.
        $data = trim($data);
        // un-quotes a quoted string.
        $data = stripslashes($data);
        // Converts special character to HTML entities.
        $data = htmlspecialchars($data);
        return $data;
    }
?>
    <!--Header-->
    <div class="row" id="header">
        <div class="col-12">
            <h1>CMET Marketplace</h1>
        </div>
    </div>
    
    <!--Nav Bar-->
    <div class="row" id="nav">
        <div class="col-12">
            <ul>
                <li><a href="index.htm">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign up</a></li>
            </ul>
        </div>
    </div>
    
    <!--Sign up form-->
    <div class="row")>
        <div class="col-12" id="signup_form_div">
            <table border="0" cellpadding="2" cellspacing="5" bgcolor="#011933">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off" 
                    onsubmit="return validate(this)" enctype="multipart/form-data">
                    <tr>
                        <td>Forename</td>
                        <td><input type="text" maxlength="32" name="forename" value="<?php echo $forename ?>"/></td>
                        <td><span class="error"> * <?php echo $forenameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Surname</td>
                        <td><input type="text" maxlength="32" name="surname" value="<?php echo $surname ?>"/></td>
                        <td><span class="error"> * <?php echo $surnameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><input type="text" maxlength="16" name="username" value="<?php echo $username ?>"/></td>
                        <td><span class="error"> * <?php echo $usernameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" maxlength="16" name="password" id="Password" value="<?php echo $password ?>"/></td>
                        <td><span class="error"> * <?php echo $passwordError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Repeat Password</td>
                        <td><input type="password" maxlength="16" name="repeatpassword" id="repeatPassword" value="<?php echo $repeatPassword ?>"/></td>
                        <td><span class="error"> * <?php echo $repeatPasswordError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="text" maxlength="64" name="email" id="Email" value="<?php echo $email ?>"/></td>
                        <td><span class="error"> * <?php echo $emailError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Repeat Email</td>
                        <td><input type="text" maxlength="64" name="repeatemail" id="repeatEmail" value="<?php echo $repeatEmail ?>"/></td>
                        <td><span class="error"> * <?php echo $repeatEmailError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Profile picture</td>
                        <td><input type="file" maxlength="64" name="profilepic" id="profilepic" ></td>
                        <td><span class="error"> * <?php echo $profilePicError ?> </span></td> 
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="reset" name="reset_btn" value="Reset">
                            <input type="submit" name="submit_btn" value="Sign up">
                        </td>
                    </tr>
                </form> 
            </table>
        </div>
    </div>
    
    <!--Footer-->
    <div class="footer">
        <div class="row">
            <div class="col-12" style="padding-left: 10px;">
                <h3>Contact us</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-3" style="padding-left: 10px;">
                <p>
                    <b>Address</b><br>
                    Llandaff Campus,<br>
                    Western Avenue,<br>
                    Cardiff,<br>
                    CF5 2YB
                </p>
            </div>
            <div class="col-6" style="padding-left: 10px;">
                <p>
                    <b>Telephone</b><br>
                    02920416070
                </p>
            </div>
            <div class="col-3">
                <h3>Follow us on social media</h3>
                <a href="https://www.facebook.com/cardiff.metropolitan.university/">
                    <img src="website_imgs/facebook-icon.png" alt="facebook-icon">
                </a>
                <a href="https://twitter.com/cardiffmet?lang=en-gb">
                    <img src="website_imgs/twitter-icon.png" alt="twitter-icon">
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>CMET Marketplace © 2019</p>
            </div>
        </div>
    </div>

</body>
</html>
