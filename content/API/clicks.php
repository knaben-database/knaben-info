<?php
echo 'Before code<br>';
require_once($_SERVER['DOCUMENT_ROOT'] . '/list/content/mysqli_connect.php');

function updateWebsiteClicks(){
    global $id, $dbc;

    $ok = "UPDATE proxylist SET clicks24 = clicks24+1, clicks = clicks+1 WHERE id = '" . $id . "'";
    if ( mysqli_query($dbc, $ok) ) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $ok . "<br>" . mysqli_error($dbc);
    }
    $ok = "UPDATE websites SET clicks24 = clicks24+1, clicks = clicks+1 WHERE id = (SELECT refer FROM proxylist WHERE id='".$id."')";
    if ( mysqli_query($dbc, $ok) ) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $ok . "<br>" . mysqli_error($dbc);
    }
}

if (isset($_GET['c'])) {
    $id = $_GET[ 'c' ];
    $ip = $_SERVER['REMOTE_ADDR'];
    $clicks24 = mysqli_fetch_array(mysqli_query($dbc, "SELECT clicks24 FROM visitors WHERE ip='" . $ip . "'"))[0];
    if ($clicks24 == ''){
        $ok = "UPDATE visitors SET clicks24 = '" . $id . "' WHERE ip = '" . $ip . "'";
        updateWebsiteClicks();
    }else if (strpos($clicks24, ',') !== false) {
        $already = false;
        foreach (explode(",",$clicks24) as $click){
            if ($click == $id)  // If already in clicks24
                $already = true;

            }
        if ($already == false) {
            $ok = "UPDATE visitors SET clicks24 = '" . $clicks24 . "," . $id . "' WHERE ip = '" . $ip . "'";
            updateWebsiteClicks();

        }
    }else{
        if ($clicks24 != $id) {
            $ok = "UPDATE visitors SET clicks24 = '" . $clicks24 . "," . $id . "' WHERE ip = '" . $ip . "'";
            updateWebsiteClicks();
        }
    }
    if ( mysqli_query($dbc, $ok) ) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $ok . "<br>" . mysqli_error($dbc);
    }
}

error_log($id . ' YES. AJAX WORKS FOR SOME FUCKING REASON!!');
echo '<br>After code';
?>