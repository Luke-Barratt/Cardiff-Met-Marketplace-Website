<?php
session_start();
if(!isset($_SESSION["userid"])) {
    header('location: user.php');
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Luke Barratt">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMET Marketplace Edit listing</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/signup.css">
    <link rel="stylesheet" href="stylesheets/listitem.css">
    <script type="text/javascript" src="validation.js"></script>
</head>
<body>

    <!--Header-->
    <div class="row" id="header">
        <div class="col-6">
            <h1>CMET Marketplace</h1>
        </div>
        <div class="col-6">
            <h4> Logged in as <?php echo $_SESSION["username"]; ?> <a href="logout.php"> Logout </a> </h4>
        </div>
    </div>
    
    <!--Nav Bar-->
    <div class="row" id="nav">
        <div class="col-12">
            <ul>
                <li><a href="userhome.php">Home</a></li>
                <li><a href="listitem.php">List Item</a></li>
                <li><a href="userlistings.php">My Listings</a></li>
                <li><a href="searchitems.php">Search Listings</a></li>
            </ul>
        </div>
    </div>
    
    <?php

        //declare variable to hold error messages for each field.
        $itemnameError = $itemdescriptionError = $itempriceError = "";

        // Get item details from database
        $servername = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "cmetmarketplace";

        // Using MySQLi connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
    ?>
            <div class="row">
                <div class="col-12">
                    <div class="error">
                            <h4 style="color: red; 
                            background-color: #022348; 
                            padding: 20px 0px 20px 10px;
                            margin-top: 0px;
                            margin-bottom: 0px">Error while connecting to MySQLi database</h4>
                    </div>
                </div>
            </div>
        
    <?php
    
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM userlistings where item_id=?");

            if($_SERVER["REQUEST_METHOD"] == "GET") {
                $item_id = $_GET["item_id"];
            }

            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $item_id = $row["item_id"];
            $user_id = $row["user_id"];
            $item_name = $row["item_name"];
            $item_description = $row["item_description"];
            $item_price = $row["item_price"];

        }
    ?>

    <?php


        if($_SERVER["REQUEST_METHOD"] == "POST") {

            $item_id = $_POST["item_id"];
            $user_id = $_POST["user_id"];
            $item_name = $_POST["item_name"];
            $item_description = $_POST["item_description"];
            $item_price = $_POST["item_price"];
        
            //validate item name
            if(empty($_POST["item_name"])){
                $itemnameError = "Item name is required";
            }
            else {
                $item_name = clearUserInputs($_POST["item_name"]);
            }

            //validate item description
            if(empty($_POST["item_description"])){
                $itemdescriptionError = "Description is required";
            }
            else {
                
                if(strlen($_POST["item_description"]) > '249') {
                    $itemdescriptionError = "Maximum 250 characters";
                }
                else {
                    $item_description = clearUserInputs($_POST["item_description"]);
                }
            }

            //validate item price
            if(empty($_POST["item_price"])){
                $itempriceError = "Price is required";
            }
            else {
                
                if(!preg_match("/^[0-9.]*$/",$_POST["item_price"])) {
                    $itempriceError = "Only 0-9 and decimal point(.) allowed in price";
                }
                elseif (strlen($_POST["item_price"]) >= 16) {
                    $itempriceError = "Enter a valid price";
                }
                else {
                    $item_price = clearUserInputs($_POST["item_price"]);
                }
            }
        
            if ($conn->connect_error) {
    ?>
                <div class="row">
                    <div class="col-12">
                        <div class="error">
                            <h4 style="color: red; 
                                background-color: #022348; 
                                padding: 20px 0px 20px 10px;
                                margin-top: 0px;
                                margin-bottom: 0px">Error while connecting to MySQLi database</h4>
                        </div>
                    </div>
                </div>
    <?php
            }
            else {

                $updatestmt = $conn->prepare("UPDATE userlistings SET item_name=?,
                item_description=?, item_price=? where item_id=?");

                $updatestmt->bind_param("ssdi", $item_name, $item_description, $item_price, $item_id);

                $update_result = $updatestmt->execute();
                if($update_result && $updatestmt->affected_rows > 0) {
    ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="error">
                                <h4 style="color: #ffffff; 
                                    background-color: #022348; 
                                    padding: 20px 0px 20px 10px;
                                    margin-top: 0px;
                                    margin-bottom: 0px">Item update successful</h4>
                            </div>
                        </div>
                    </div>
    <?php
                }
                else {
    ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="error">
                                <h4 style="color: red; 
                                    background-color: #022348; 
                                    padding: 20px 0px 20px 10px;
                                    margin-top: 0px;
                                    margin-bottom: 0px">Item update unsuccessful</h4>
                            </div>
                        </div>
                    </div>
    <?php
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
    
    <!--List item form-->
    <div class="row">
        <div class="col-12" id="list_item_form_div">
            <table border="0" cellpadding="2" cellspacing="5" bgcolor="#011933">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off" 
                enctype="multipart/form-data">
                    <tr>
                        <td>User id</td>
                        <td><input type="text" maxlength="32"
                        name="user_id" value="<?php echo $user_id ?>" readonly/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Item id</td>
                        <td><input type="text" maxlength="32"
                        name="item_id" value="<?php echo $item_id ?>" readonly/></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Item Name</td>
                        <td><input type="text" maxlength="50" name="item_name" 
                            style="width: 200px;" value="<?php echo $item_name; ?>"/></td>
                        <td><span class="error"> * <?php echo $itemnameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Item Description</td>
                        <td><textarea type="text" maxlength="300" name="item_description" 
                            style="max-width: 300px; min-width: 300px; max-height: 100px; min-height: 100px;" 
                            placeholder="maximum 250 characters" value="<?php echo $item_description; ?>"></textarea></td>
                        <td><span class="error"> * <?php echo $itemdescriptionError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Item Price</td>
                        <td><input type="text" maxlength="20" name="item_price" style="width: 60px;" placeholder="00.00" value="<?php echo $item_price; ?>"/></td>
                        <td><span class="error"> * <?php echo $itempriceError; ?> </span></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="submit_btn" value="Update">
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