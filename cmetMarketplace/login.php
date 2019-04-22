<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Luke Barratt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMET Marketplace Login</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/login.css">
    <script type="text/javascript" src="validation.js"></script>
</head>
<body>
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

    <?php
        $username = $password = "";
        $usernameError = $passwordError = $dbConnectionError = $credentialError = "";

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            // validate username and password
            if(empty($_POST["username"])) {
                $usernameError = "Username is required";
            }
            else {
                $username = clearUserInputs($_POST["username"]);
            }

            if(empty($_POST["password"])) {
                $passwordError = "Password is required";
            }   
            else {
                $password = clearUserInputs($_POST["password"]);
            }
            
            // make connection to database
            $servername = "localhost";
            $dbusername = "root";
            $dbpassword = "";
            $dbname = "cmetmarketplace";

            // Using MySQLi connection
            $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

            if ($conn->connect_error) {
                $dbConnectionError = "Error in connecting to database";
            }
            else {
                $stmt = $conn->prepare("SELECT * FROM users where username=? and password=?");
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    $credentialError = "Invalid username or password";
                }
                else {
                    echo "Rows: " . $result->num_rows;
                    $row = $result->fetch_assoc();
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["userid"] = $row["user_id"];
                    header('location: userhome.php');
                }
            }
        }

        // function to clear userinputs
        function clearUserInputs($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>
    
    <!--Login form-->
    <div class="row">
        <div class="col-12" id="login_form_div">
            <table border="0" cellpadding="2" cell spacing="5" bgcolor="#011933">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <tr>
                        <td>Username</td>
                        <td><input type="text" maxlength="16" name="username" value=""></td>
                        <td><span class="error"> * <?php echo $usernameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" maxlength="16" name="password" value=""></td>
                        <td><span class="error"> * <?php echo $passwordError; ?> </span></td> 
                    </tr>
                    <tr>
                        <td><span class="error" style="background-color: yello; color: red; font-size: 0.8em;"> <?php echo $dbConnectionError; ?> </span></td>
                        <td><span class="error" style="background-color: yello; color: red; font-size: 0.8em;"> <?php echo $credentialError; ?> </span></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="submit_btn" value="Login">
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