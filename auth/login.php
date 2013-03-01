<?php 
   session_start();
   $subdomain = explode(".",$_SERVER['HTTP_HOST']);
   $app_id = "340879852685280";
   $app_secret = "711927c306a77b9bbe1277927e08e102";
   $my_url = "http://".$subdomain[0].".confessionspage.com/auth/login.php";

   $code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=manage_pages,publish_stream";

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

   if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     // state variable matches
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $_SESSION['access_token'] = $params['access_token'];

     $graph_url = "https://graph.facebook.com/me/accounts?access_token=" 
       . $params['access_token'];

     $accounts = json_decode(file_get_contents($graph_url));
     $pages = $accounts->data;
     foreach($pages as $p) {
       if ($p->id == $pageId) {
         setToken($p->id,$p->access_token);
       }
     }
     echo("at: " . $params['access_token']);
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }
 ?>
