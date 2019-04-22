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
    <title>CMET Marketplace Home</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/userlistingstable.css">
    <script>
    function searchItems() {  
        var keyword = document.getElementById("keyword").value;

        var xhttp;
        if(window.XMLHttpRequest){
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xhttp.open("GET", "search.php?keyword="+keyword, true);
        xhttp.send();
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200){
                document.getElementById("searchResult").innerHTML = this.responseText;
            }
        };
    }
    </script>
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

    <!--Keyword Search results-->
    <div class="row">
        <div class="col-12">
            <h4 style="color: #ffffff; 
                background-color: #011933; 
                padding: 20px 0px 20px 10px;
                margin-top: 0px;
                margin-bottom: 0px;"> Item search results for: <?php echo $_SESSION["username"]; ?> </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12" style="width: 100%;
                color: #ffffff; 
                background-color: #011933; 
                padding: 20px 0px 20px 10px;
                margin-top: 0px;
                margin-bottom: 0px;">
            Search item by keyword: <input style="font-family: 'Raleway', sans-serif;" type="text" id="keyword" name="keyword" size="30"
                    onkeyup="searchItems();" /> <br>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="searchResult" id="searchResult">
                <p style="width: 100%;
                color: #ffffff; 
                background-color: #011933; 
                padding: 20px 0px 20px 10px;
                margin-top: 0px;
                margin-bottom: 0px;"> Search results are displayed here.. </p>
            </div>
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
