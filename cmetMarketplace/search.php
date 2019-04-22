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
    <title>Search Results</title>
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Raleway" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/grid.css">
    <link rel="stylesheet" href="stylesheets/header.css">
    <link rel="stylesheet" href="stylesheets/nav.css">
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/footer.css">
    <link rel="stylesheet" href="stylesheets/userlistingstable.css">
</head>
<body>
<?php
// Connect to the database using MySQLi object
// Make connection to database
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "cmetmarketplace";

//Using MySQLi connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

//check connection
if ($conn->connect_error) {
    echo "Error when connecting the database.<br>";
}
else {
    // Prepare the SQL statement to search
    $sql = "SELECT * FROM userlistings WHERE item_description like '%" . $_GET['keyword'] . "%' 
            AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION["userid"]);
    $stmt->execute();

    $result = $stmt->get_result();
        
    if ($result->num_rows <= 0 || empty($_GET['keyword'])) {
?>
        <div class="row">
            <div class="col-12">
                <div class="error">
                    <p style="color: #ffffff; 
                        background-color: #011933; 
                        padding: 20px 0px 20px 10px;
                        margin-top: 0px;
                        margin-bottom: 0px;"> No data available for user with keyword... <?php echo $_GET["keyword"]; ?> <p>
                </div>
            </div>
        </div>
<?php
    }// num rows if
    else {
?>
    <div class="row">
        <div class="col-12">
            <table style="width: 100%; 
                    background-color: #011933; 
                    color: #ffffff;
                    text-align: left;
                    padding: 10px 10px 10px 10px;">
                <tr> 
                    <th> Item ID </th> 
                    <th> Item Name </th>
                    <th> Item Description </th>
                    <th> Item Price </th>
                    <th> Item Images </th>
                </tr>
<?php 
        // output data of each row
        while($row = $result->fetch_assoc()) {
?>
                <tr>
                    <td> <?php echo $row["item_id"]; ?> </td>
                    <td> <?php echo $row["item_name"]; ?> </td>
                    <td> <?php echo $row["item_description"]; ?> </td>
                    <td> <?php echo $row["item_price"]; ?> </td>
                    <td> <?php echo $row["item_images"]; ?> </td>
                </tr>     
<?php
        }// end of while
?>
            </table>
        </div>
    </div>
<?php
    }//end of rows else

}// end of connection OK else.
?>
</body>
</html>