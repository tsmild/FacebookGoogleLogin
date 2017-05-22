<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-social.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
     <div class="jumbotron vertical-center">
      <div class="container">
<?php
require_once "conn.php";
include_once 'google-config.php';

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
}
if (isset($_SESSION['token'])) {
    $gClient->setAccessToken($_SESSION['token']);
}
if ($gClient->getAccessToken()) {
    $gUserProfile = $google_oauthV2->userinfo->get();
    if ($conn->connect_error) {
            die("connection failed : ".$conn -> connect_error);
        }else{
            //variable FB Data
           	$gg_id = $gUserProfile["id"];
            $gg_fname = $gUserProfile["given_name"];
            $gg_lname = $gUserProfile["family_name"];
            $gg_email = $gUserProfile["email"];
            $gg_link = $gUserProfile["link"];
            $gg_gender = $gUserProfile["gender"];
            $gg_local = $gUserProfile["locale"];


            $sql = "SELECT * FROM u13570188 WHERE id_user = ".$gg_id."";
            $result = $conn->query($sql);

            if ($result -> num_rows == 0){
                $sql_insert = "INSERT INTO u13570188(id_user, first_name, last_name, email, link, gender, locale, fb_createtime) VALUES ('".$gg_id."', '".$gg_fname."', '".$gg_lname."', '".$gg_email."', '".$gg_link."', '".$gg_gender."', '".$gg_local."',Now())";

                $conn->query($sql_insert);
                header('Location:'.filter_var($gRedirectURL,FILTER_SANITIZE_URL));
            }else{
                echo "<h3>Google Login</h3>";
                echo "<br>";
                echo "<ul>";

                    while ($row = $result -> fetch_assoc()) {
                        echo "<li><b>User ID: </b>".$row['id_user']."</li>";
                        echo "<li><b>First name: </b>".$row['first_name']."</li>";
                        echo "<li><b>Last name: </b>".$row['last_name']."</li>";
                        echo "<li><b>Email: </b>".$row['email']."</li>";
                        echo "<li><b>Link: </b>".$row['link']."</li>";
                        echo "<li><b>Gender: </b>".$row['gender']."</li>";
                        echo "<li><b>Locale: </b>".$row['locale']."</li>";
                        echo "<li><b>Time: </b>".$row['fb_createtime']."</li>";
                        echo "<br>";
                        echo '<li><a class="btn btn-block btn-danger btn-lg" href="logout.php" style="width: 30%;margin: 0 auto;background-color: #999;border: none;margin-top:0;">Log out</a></li>';
                    }
                echo "</ul>";
                $conn->close();
        }
    // ข้อมูลมาแล้วทำตรงนี้
    }
}else{
    echo '<a class="btn btn-block btn-social btn-lg btn-google" href="'.$gloginUrl.'" style="margin:0 auto; width:40%;"><span class="fa fa-google"></span> Sign in with Google</a>';
    
}



?>
</div>
     </div>
</body>
</html>