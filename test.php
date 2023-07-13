<?php

$dbc = mysqli_connect("127.0.0.1", "root", "TODO: PASSWORD", "knabeninfo");

function mysql_q($query){
    global $dbc;
    return mysqli_fetch_array(mysqli_query($dbc, $query));
}

$content = mysqli_query($dbc, "SELECT * FROM websites ORDER BY clicks DESC");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="height:100%;" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>TESTESTEST</title>

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
            margin: 20%;
            padding: 0;
	    font-size:300%;
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
            margin-top:8%;
            display:table;
            margin-left:auto;
            margin-right:auto;

        }

    </style>
</head>
<?php

while($line = mysqli_fetch_array($content)) {
    echo $line[1] . '<br>';
}

?>
<h2>OK SO THIS IS A TEST</h2>
</html>
