<?php
 require_once "conn.php";

 if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
          // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Set default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    
    // Redirect the user back to the same page if url has "code" parameter in query string
    if(isset($_GET['code'])){
        header('Location: ./');
    }
    
    // Getting user facebook profile info
    try {
        $profileRequest = $fb->get('/me?fields=id,name,first_name,last_name,email,link,gender,locale,picture');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if ($conn->connect_error) {
            die("connection failed : ".$conn -> connect_error);
        }else{
            //variable FB Data
            $fb_id = $fbUserProfile["id"];
            $fb_fname = $fbUserProfile["first_name"];
            $fb_lname = $fbUserProfile["last_name"];
            // $fb_email = $fbUserProfile["email"];
            $fb_link = $fbUserProfile["link"];
            $fb_gender = $fbUserProfile["gender"];
            $fb_local = $fbUserProfile["locale"];


            $sql = "SELECT * FROM u13570188 WHERE id_user = ".$fb_id."";
            $result = $conn->query($sql);

            if ($result -> num_rows == 0){
                $sql_insert = "INSERT INTO u13570188(id_user, first_name, last_name, link, gender, locale, fb_createtime) VALUES ('".$fb_id."', '".$fb_fname."', '".$fb_lname."', '".$fb_link."', '".$fb_gender."', '".$fb_local."',Now())";

                $conn->query($sql_insert);
                echo "INSERT SUCCESS";
            }else{
                echo "<h3>Facebook Login</h3>";
                echo "<br>";
                echo "<ul>";

                    while ($row = $result -> fetch_assoc()) {
                        echo "<li><b>User ID: </b>".$row['id_user']."</li>";
                        echo "<li><b>First name: </b>".$row['first_name']."</li>";
                        echo "<li><b>Last name: </b>".$row['last_name']."</li>";
                        echo "<li><b>Email: </b>pj_spacher@hotmail.com</li>";
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
    $fbloginUrl = $helper->getLoginUrl($fbRedirectURL, $fbPermissions);
    echo '<a class="btn btn-block btn-social btn-lg btn-facebook" href="'.$fbloginUrl.'"><span class="fa fa-facebook"></span> Sign in with Facebook</a>';

}


?>
