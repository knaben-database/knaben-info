<?php

require_once('content/mysqli_connect.php');

$ip = $_SERVER['REMOTE_ADDR'];

$exists = mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) FROM visitors WHERE ip = '".$ip."'"))[0];
if ($exists == 1){
    $update = mysqli_fetch_array(mysqli_query($dbc, "SELECT last_seen FROM visitors WHERE ip='" . $ip . "'"))[0];
    if ($update != date("Y-m-d")) {
        $ok = "UPDATE visitors SET last_seen = '" . date("Y-m-d") . "', clicks24 = NULL WHERE ip = '" . $ip . "'";

        if ( mysqli_query($dbc, $ok) ) {
        } else
            error_log("Error: " . $ok . "<br>" . mysqli_error($dbc));
    }

}else{
    $ok = "INSERT INTO visitors (ip, last_seen)VALUES ('" . $ip . "', '" . date("Y-m-d") . "')";

    if ( mysqli_query($dbc, $ok) ) {} else
        error_log("Error: " . $ok . "<br>" . mysqli_error($dbc));

}

?>