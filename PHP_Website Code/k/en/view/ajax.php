<?php
    // Start the output buffer.
    ob_start();

    include_once "../../php/libs/database.php";
    include_once "../../plugins/private_signup_plugin.php";

    // Start the session.
    if (!isset($_SESSION)) {
        session_start();
    }

    if(empty($_POST["hash"]) || empty($_POST["id"])) {
        header("Location: ../../index.php");
        exit;
    } else {
        // Database object.
        $db = new Db();

        // Quote and escape values.
        $hash =  $db -> quote($_POST["hash"]);
        $id =  $db -> quote($_POST["id"]);

        // Get the corresponding values.
        $torrent = $db -> select("SELECT * FROM `torrents` WHERE `hash`=".$hash." AND `userid`=".$id."");
        // Change this to the torrent 404 page.
        if(count($torrent) == 0) { 
            header("Location: ../../en/status/404.php"); exit;
        }
        $uploader = $db -> select("SELECT `username` FROM `users` WHERE `user_id`=".$id."");
    }
?>



    <?php
        function formatBytes($bytes, $precision = 2) { 
            $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

            $bytes = max($bytes, 0); 
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
            $pow = min($pow, count($units) - 1); 
            
            $bytes /= (1 << (10 * $pow)); 

            return round($bytes, $precision) . ' ' . $units[$pow]; 
        }
        if($_POST['action'] == 'call_this') {
            $new = $torrent[0]['votes'];
            $db ->select("UPDATE `torrents` SET `votes` = $new + 1 WHERE `hash`=".$hash." AND `userid`= ".$id."");
        }
    ?>