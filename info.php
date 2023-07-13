<?php

require_once('content/mysqli_connect.php');

$GODMODE = false;
if (isset($_COOKIE['user'])) {
    $cookie = $_COOKIE[ 'user' ];
    if ($cookie == '***REMOVED***113398866'){
        $GODMODE = true;
    }
}

function submitProxy($proxy, $id){
    global $dbc;
    if( mysqli_fetch_array(mysqli_query($dbc, "SELECT count(*) FROM knabeninfo.proxylist WHERE url='".$proxy."'"))[0] == 0) {


        $ok = "INSERT INTO proxylist (url, refer, time, ping)VALUES ('" . $proxy . "', '" . $id . "', '" . date("Y-m-d") . "', '0.00')";

        if ( mysqli_query($dbc, $ok) ) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $ok . "<br>" . mysqli_error($dbc);
        }
    }else
        echo 'IT\'S A DUPLICATE! RUN RUUUN!!!!!';


}

if (isset($_POST['proxy'], $_POST['website']) && $GODMODE == true){
    $id = $_POST['website'];
    $website = $_POST['website'];
    $proxy = $_POST['proxy'];
    if (strpos($proxy, "\n") !== false) {
        foreach (explode("\n", $proxy) as $line){
            //if (substr($string, -1) == '/')
            //    submitProxy(substr($line, 0, -1), $id);
            //else
                submitProxy($line, $id);
        }
    }else{
        //if (substr($string, -1) == ',')
        //    submitProxy(substr_replace($proxy, "", -1), $id);
        //else
            submitProxy($proxy, $id);
    }


}
if (isset($_GET['id'])){
    $id = $_GET['id'];
}

$website = mysqli_fetch_array(mysqli_query($dbc, "SELECT name FROM websites WHERE id='" . $id . "'"))[0];
$proxies_query = mysqli_query($dbc, "SELECT * FROM proxylist WHERE refer='" . $id . "' ORDER BY clicks DESC");

function mysql_q($query){
    global $dbc;
    return mysqli_fetch_array(mysqli_query($dbc, $query));
}

$proxies = array();
while($proxy = mysqli_fetch_array($proxies_query)) {
    $proxies[] = $proxy;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php echo $website; ?> - Knaben's Proxy List</title>

    <!--link rel="stylesheet" href="newchat/main.css"-->
    <link rel="icon" href="/favicon.ico">
    <meta name="description" content="Proxy for a various of websites, including our own. Feel free to submit a proxy/website if you want">
    <link rel="stylesheet" href="/content/style.css?v=<?php echo filemtime('content/style.css'); ?>">
    <style>
        h1{
            /*font-size:700%;*/

            margin-bottom:0px;
            text-align: center;
        }
        h2{
            text-align: center;
            margin: 0 0 40px 0;
            padding: 0;
        }
        input:focus, select:focus {
            outline-width: 0;
        }
        #tagdiv x{
            background-color: #35585d;
            color: #45ada6;
            margin: 0 10px 0 0px;
            display: inline-flex;
        }
        #tagdiv{
            transition-duration: .5s;
        }
        #listbox center{
            font-size:150%;
            padding: 10px;
            display:block;
        }
        #listbox{
            padding:20px;
            margin-bottom:8%;
            display:table;
            margin-left:auto;
            margin-right:auto;
            min-width:50%;

        }

        select{
            background: rgba(25,30,34,0.67);
            color: white;
            font-size: 130%;
            border-width: 4px;
            padding: 10px;
            float:left;
            cursor: pointer;
            border-style: solid;
            border-color: #5f4661;
            width:30%;
        }
        textarea{
            height: 128px;
            background: rgba(25,30,34,0.67);
            color: white;
            font-size: 130%;
            /* word-wrap: normal; */
            /* overflow: unset; */
            word-wrap: break-word;
            white-space: pre-wrap;
            border-width: 4px;
            border-style: solid;
            border-color: #5f4661;
            float:left;
            width:60%;
            max-width:65%;
        }
        input{
            background: rgba(25,30,34,0.67);
            color: white;
            font-size: 130%;
            border-width: 4px;
            padding: 10px;
            float:left;
            cursor: pointer;
            border-style: solid;
            border-color: #5f4661;
            width:30%;
        }

    </style>
</head>
<script type="text/javascript">
    function handleLink(id) {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
                if (xmlhttp.status == 200) {
                    //document.getElementById("myDiv").innerHTML = xmlhttp.responseText;
                }
                else if (xmlhttp.status == 400) {
                    //alert('There was an error 400');
                }
                else {
                    //alert('something else other than 200 was returned');
                }
            }
        };

        xmlhttp.open("GET", "content/API/clicks.php?c="+id, true);
        xmlhttp.send();
    }
</script>
<?php

include_once('flacmenu.php');

?>
<body>
<div id="listbox">
<h1><?php echo $website; ?></h1>
<h2>List of all available proxies</h2>
    <div id="listbox" style="display:block;"><div id="listbox" style="display:block;"><center><h2>ONLINE PROXIES:</h2></center>
    <?php

                foreach ($proxies as $proxy) {
                    if ($proxy[4] == 1) {

                        echo '<center><a onclick="handleLink('.$proxy[0].')" href="' . $proxy[1] . '">' . $proxy[1] . '</a></center>';

                    }


                }

                //echo '</div>';

            echo '</div><center><h2>OFFLINE PROXIES:</h2></center>';
                foreach ($proxies as $proxy) {
                    //echo $proxy[1];
                    if ($proxy[4] == 0) {

                        echo '<center><a onclick="handleLink('.$proxy[0].')" href="' . $proxy[1] . '">' . $proxy[1] . '</a></center>';

                    }


                }
                echo '</div>';



    ?>
    </div>

    <?php
    if ($GODMODE == true):
    ?>
    <br><br><br>
        <center>
            <div id="listbox">
                <form action="/info.php" method="post">


                        <textarea name="proxy"></textarea>
                    <div>
                    <select name="website">
                        <?php
                        $godmode_proxy = mysqli_query($dbc, "SELECT * FROM proxylist ORDER BY clicks DESC");
                        $godmode_proxys = array();
                        while($proxy = mysqli_fetch_array($godmode_proxy)) {
                            $godmode_proxys[] = $proxy;
                        }
                        $websites = mysqli_query($dbc, "SELECT * FROM websites");
                        while($website = mysqli_fetch_array($websites)) {
                            $count = 0;
                            foreach ($godmode_proxys as $proxy) {

                                if ($proxy[5] == $website[0]){
                                    $count++;
                                }
                            }
                            if ($website[0] == $id)
                                echo '<option selected="selected" value="'.$website[0].'">('. $count .') '.$website[1] .'</option>';
                            else
                                echo '<option value="'.$website[0].'">('. $count .') '.$website[1] .'</option>';
                        }
                        ?>
                    </select>
                        <input type="submit" value="NICE PROXY YAAA??">
                    </div>

                </form>
            </div>
        </center>
    <?php
    endif;

    mysqli_close($dbc);
    ?>

</body>
<?php

include_once('footer.php');
?>
</html>
