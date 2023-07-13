<?php

include_once('check_user.php');

?>
<html>
<body>

<ul id="menu">
	<li><a href="/index.php">Search</a></li>
	<!--li><a href="/page2.html">Page 2</a></li-->
	<li><a href="/browse.php">Browse</a></li>
	<li><a href="/upload.php">Upload</a></li>
	<!--li><a href="/newchat">Chat</a></li-->
	<!--<li><a href="/hiddenlol.html">Hiddenlol</a></li>-->
	<li><a href="/proxy.php">Proxy</a></li>
	<!--Kickass Torrents are down currently, use Pirate bay for now-->
	
	<li class="dropdown">
		<a href="/download.php">Downloads</a>
		<div class="dropdown-content">
			<a href="/programs.php">Programs</a>
			<a href="/virus.php">Malware</a> 
		</div>
	</li>

	<li><a href="/vpn.php">VPN</a></li>
	<!--li><a href="/chat.php">Chat</a></li-->
		<li class="dropdown">
		<a href="/fun.php" class="dropbtn">Random</a>
		<div class="dropdown-content">
			<a href="/ip.php">IP's</a>
			<a href="/virus.php">Viruses</a>
			<a href="/dark_side.php"><img src="/cola/Dark Side.png" alt="Dark Side" height="20" width="70"></a>
			<a href="/lumskesider.php">Lumske Sider</a>
			<a href="/yourip.php"><?php echo $_SERVER["REMOTE_ADDR"]; ?></a>
		</div>
		
	<!--<li><a href="/yourip.php">IP Information</a></li> Tok det bort fordi det lagde lag på nettsiden, så kan jeg heller ta tilbake include menu på yourip.php -->
	<li><a href="/about.php">About</a></li>
	
</ul>
<ul id="vpnlist">
<?php 
if ($user != ''){
	echo '<a href="/myprofile.php?user=' . $user . '"><img align="right" src="/cola/profile_normal.png" alt="My profile" width="140" height="27"></a>';
} else{
	echo '<a href="/login.php"><img align="right" src="/cola/login_normal.png" alt="Register" width="140" height="27"></a>';
}
?>
</ul>

</body>
</html>
