<?php 
require_once('../config/app.php');
require_once('../config/domain.php');

   session_start();
   $domain = new Domain();
   $my_url = "http://".$domain->domain.".confessionspage.com/auth/login.php";

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
       if ($p->id == $domain->pageid) {
         $domain->setPageToken($p->access_token);
       }
     }
     echo("<script> top.location.href='/'; </script>");
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }
 ?>
