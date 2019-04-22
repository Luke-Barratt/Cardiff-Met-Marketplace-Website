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
    <title>CMET Marketplace Logged out</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <script type="text/javascript" src="validation.js"></script>
</head>
<body>

    <!--Header-->
    <div class="row" id="header">
        <div class="col-6">
            <h1>CMET Marketplace</h1>
        </div>
        <div class="col-6">
            <h4> <?php echo $_SESSION["username"]; echo " logged out" ?> </h4>
        </div>
    </div>

    <?php 
        //Kills session as user has logged out 
        session_unset(); 
        session_destroy();
    ?>
    
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
    
    <!--Section one-->
    <div class="row" id="section-one">
        <div class="col-12">
            <h1>CMET Marketplace - A place to buy, sell and trade</h1>
        </div>
    </div>
    
    <!--Section two-->
    <div class="row" id="section-two">
        <div class="col-4" id="left-column">
            <img src="website_imgs/image-placeholder.png" alt="buy-icon">
            <h2>Buy</h2>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iste esse 
                fuga animi maiores dolorum suscipit eius cum. Ex eveniet provident
                 unde illo doloribus officia cum soluta, quo ab. Voluptas, nemo?</p>
        </div>
        <div class="col-4" id="centre-column">
            <img src="website_imgs/image-placeholder.png" alt="sell-icon">
            <h2>Sell</h2>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iste esse 
                    fuga animi maiores dolorum suscipit eius cum. Ex eveniet provident
                     unde illo doloribus officia cum soluta, quo ab. Voluptas, nemo?</p>
        </div>
        <div class="col-4" id="right-column">
            <img src="website_imgs/image-placeholder.png" alt="trade-icon">
            <h2>Trade</h2>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Iste esse 
                    fuga animi maiores dolorum suscipit eius cum. Ex eveniet provident
                     unde illo doloribus officia cum soluta, quo ab. Voluptas, nemo?</p>
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