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
    <title>CMET Marketplace List item</title>
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

<?php
    //declare variables and set to empty values.
    $itemname = $itemdescription = $itemprice = "";
    $itemimages = "";

    //declare variable to hold error messages for each field.
    $itemnameError = $itemdescriptionError = $itempriceError = "";
    $itemimagesError = "";
    $foundErrors = false;

    //if the form has been submitted, AND the method is POST.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //validate item name
        if(empty($_POST["item_name"])){
            $itemnameError = "Item name is required";
            $foundErrors = true;
        }
        else {
            $itemname = clearUserInputs($_POST["item_name"]);
        }

        //validate item description
        if(empty($_POST["item_description"])){
            $itemdescriptionError = "Description is required";
            $foundErrors = true;
        }
        else {
            
            if(strlen($_POST["item_description"]) > '249') {
                $itemdescriptionError = "Maximum 250 characters";
                $foundErrors = true;
            }
            else {
                $itemdescription = clearUserInputs($_POST["item_description"]);
            }
        }

        //validate item price
        if(empty($_POST["item_price"])){
            $itempriceError = "Price is required";
            $foundErrors = true;
        }
        else {
            
            if(!preg_match("/^[0-9.]*$/",$_POST["item_price"])) {
                $itempriceError = "Only 0-9 and decimal point(.) allowed in price";
                $foundErrors = true;
            }
            elseif (strlen($_POST["item_price"]) >= 20) {
                $itempriceError = "Enter a valid price";
                $foundErrors = true;
            }
            else {
                $itemprice = clearUserInputs($_POST["item_price"]);
            }
        }

        //validate image

        // input file details
        $imageok = false;

        //extract selected file details: 
        $file_name = $_FILES['item_images']['name'];
        $file_size = $_FILES['item_images']['size'];
        $file_tmp = $_FILES['item_images']['tmp_name'];
        $file_type = $_FILES['item_images']['type'];
        $basename_file = basename($_FILES['item_images']['name']);
        $file_ext = strtolower(pathinfo($basename_file, PATHINFO_EXTENSION));
        
        $allowed_extensions = array("jpeg", "jpg", "png");

        if ($file_size > 500000) {
            $itemimagesError .= "Sorry, your file is too large. <br>";
            $imageok = false;
        } 
        else if(in_array($file_ext, $allowed_extensions) === false) {
            $itemimagesError .= "Only JPEG, PNG and JPG files are allowed. <br>";
            $imageok = false;
        } 
        else {
            $imageok = true;
        }
        
        //insert into userlistings table

        // variable to store current users user_id
        $current_user_id = $_SESSION["userid"];
        
        if($foundErrors === false) {
            
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
                echo "Connection successful <br>";
                $mysqliconnection = "Connection successful";
                
                $sql = "INSERT INTO userlistings
                (user_id,
                item_name,
                item_description,
                item_price,
                item_images)
                values(?, ?, ?, ?, ?)";
            
                $stmt = $conn->prepare($sql);
                $stmt->execute([$current_user_id, $itemname, $itemdescription, $itemprice, $itemimages]);

                echo "New item added to the database: ";
                $last_inserted_item_id = $conn->lastInsertId();

                if (!$imageok) {
                    $itemimagesError .= "Selected image is invalid <br>";
                }
                else {
                    $target_dir = "images\\".$current_user_id."\\$last_inserted_item_id\\";
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
                        $itemimagesError .= "Sorry, there was an error uploading your file. <br>";
                    }
            
                    $sql = "UPDATE `userlistings` SET `item_images` = ? WHERE `item_id` = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$target_dir, $last_inserted_item_id]);
                }
            }

            catch (PDOException $e) {

                echo $e->getMessage();
                
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

    <!--List item form-->
    <div class="row">
        <div class="col-12" id="list_item_form_div">
            <table border="0" cellpadding="2" cellspacing="5" bgcolor="#011933">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off" 
                enctype="multipart/form-data">
                    <tr>
                        <td>Item Name</td>
                        <td><input type="text" maxlength="50" name="item_name" 
                            style="width: 200px;" value="<?php echo $itemname; ?>"/></td>
                        <td><span class="error"> * <?php echo $itemnameError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Item Description</td>
                        <td><textarea type="text" maxlength="300" name="item_description" 
                            style="max-width: 300px; min-width: 300px; max-height: 100px; min-height: 100px;" 
                            placeholder="maximum 250 characters" value="<?php echo $itemdescription; ?>"></textarea></td>
                        <td><span class="error"> * <?php echo $itemdescriptionError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Item Price</td>
                        <td><input type="text" maxlength="20" name="item_price" style="width: 60px;" placeholder="00.00" value="<?php echo $itemprice; ?>"/></td>
                        <td><span class="error"> * <?php echo $itempriceError; ?> </span></td>
                    </tr>
                    <tr>
                        <td>Item Images</td>
                        <td><input type="file" maxlength="64" name="item_images" value=""/></td>
                        <td><span class="error"> * <?php echo $itemimagesError; ?> </span></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="reset" name="reset_btn" value="Reset">
                            <input type="submit" name="submit_btn" value="List">
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