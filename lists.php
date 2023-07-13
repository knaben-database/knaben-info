<div id="list">
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
require_once('content/mysqli_connect.php');
$websites = mysqli_query($dbc, "SELECT * FROM websites INNER JOIN cats ON cats.id=websites.cat ORDER BY clicks DESC;");
$proxies_query = mysqli_query($dbc, "SELECT * FROM proxylist ORDER BY 'clicks' DESC");
$proxies = array();
while($proxy = mysqli_fetch_array($proxies_query)) {
    $proxies[] = $proxy;
}
function sortByOrder($a, $b) {
    return $b[2] - $a[2];
}

while($website = mysqli_fetch_array($websites)) {
    $currentproxy = [];
    $limit = 0;
    foreach ($proxies as $proxy) {
        if ($proxy[5] == $website[0] && $proxy[4] == 1)
            $currentproxy[] = $proxy;
        if ($limit == 6){
            continue;
        }
        $limit++;
    }
    usort($currentproxy, 'sortByOrder');
    $count = 0;
    echo '<div id="listbox"><div style="display:grid;background-color:rgba(25,30,34,0.51);"><p style="padding:2px;float:left;color:white;font-size:medium;">'.ucwords($website[9]).'</p><h2>' . $website[1] . '</h2></div>
          <p id="popularity">'.$website[5].' Clicks ('.$website[6].' Today)</p><table><tr><th>Link</th><th>Clicks</th><th>Ping</th></tr>';
    //$handle = fopen('content/proxies/' . $file, "r");
    //if ( $handle ) {
    foreach ($currentproxy as $proxy) {
            //echo $proxy;

        if ( $count == 6 ) {
            echo '</table><button onclick="location.href=\'info.php?id=' . $website[ 0 ] . '\'">More Links...</button>';
            break;
        }else if ($proxy == end($currentproxy)){
            echo '<tr><td><a onclick="handleLink('.$proxy[0].')" href="' . $proxy[ 1 ] . '">' . $proxy[ 1 ] . '</a></td>
                  <td>'.$proxy[2].'</td>
                  <td>'.$proxy[8].'</td></tr></table>';
        }else{
            echo '<tr><td><a onclick="handleLink('.$proxy[0].')" href="' . $proxy[ 1 ] . '">' . $proxy[ 1 ] . '</a></td>
                  <td>'.$proxy[2].'</td>
                  <td>'.$proxy[8].'</td></tr>';
        }

        $count++;


    }
    if ($count < 5)
        echo '<br><br><br>';
    echo '</div>';
}
?>
</div>
