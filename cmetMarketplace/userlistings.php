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
    <title>CMET Marketplace User listings</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/userlistingstable.css">
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

    <!--User listings table-->
    <div class="row">
        <div class="col-12">
            <h4 style="color: #ffffff; 
            background-color: #011933; 
            padding: 20px 0px 20px 10px;
            margin-top: 0px;
            margin-bottom: 0px;">Listed items for: <?php echo $_SESSION["username"];?></h4>
        </div>
    </div>

    <?php
        // Make connection to database
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

            $stmt = $conn->prepare("SELECT * FROM userlistings where user_id=?");
            $stmt->bind_param("i", $_SESSION["userid"]);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows <= 0) {

    ?>
    <div class="row">
        <div class="col-12">
            <div class="error">
                <h4 style="color: red; 
                background-color: #022348; 
                padding: 20px 0px 20px 10px;
                margin-top: 0px;
                margin-bottom: 0px">User has no current listings</h4>
            </div>
        </div>
    </div>

    <?php
            }// end num rows if
            else {
    ?>          
        <div class="row">
            <div class="col-12">
                <table class="userlistings_table" style="width: 100%; 
                    background-color: #011933; 
                    color: #ffffff;
                    text-align: left;
                    padding: 0px 10px 10px 10px;">
                    <tr> 
                        <th> Item ID </th> 
                        <th> Item Name </th>
                        <th> Item Description </th>
                        <th> Item Price </th>
                        <th> Item Image </th>
                    </tr>
    <?php
        //output data of each row
        while($row = $result->fetch_assoc()) {
    ?>
                    <tr>
                        <td> <?php echo $row["item_id"]; ?> </td>
                        <td> <?php echo $row["item_name"]; ?> </td>
                        <td> <?php echo $row["item_description"]; ?> </td>
                        <td> <?php echo $row["item_price"]; ?> </td>
                        <td> <?php echo $row["item_images"]; ?> </td>
                        <td> <a href="edititem.php?item_id=<?php echo $row['item_id'] ?>"> Edit </a> </td>
                        <td> <a href="deleteitem.php?item_id=<?php echo $row['item_id'] ?>"> Delete </a> </td>
                    </tr>

    <?php
        }//endwhile
    ?>
                </table>
            </div>
        </div>
    <?php
                }//end of rows else
            }// end of connection else
            $conn->close();
    ?> 

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