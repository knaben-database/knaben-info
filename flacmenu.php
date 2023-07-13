<html>
<body>


	<ul id="flacmenu">
	<div id="menudiv">
	<div style="width:75%;margin-right: auto;margin-left: auto;display: flex;justify-content: space-between;">
		<a href="/"><img style="position: fixed; left:40px; height: 90px;" src="/content/Knaben List.png?v=<?php echo filemtime('content/Knaben List.png'); ?>"></a>
		<!--<li id="flacmenuli"><a href="/">Search</a></li>
		<li id="flacmenuli"><a href="/browse/">Browse</a></li>
        <li id="flacmenuli"><a href="/top/">Top</a></li>
		<li id="flacmenuli"><a href="/new/">New</a></li>
		<li id="flacmenuli"><a href="/about/">About</a></li>
		<li id="flacmenuli"><a href="/support/">Support</a></li>-->
        <?php
            if (isset($_COOKIE['id'])){
                echo '<li id="flacmenuli"><a href="/me/?id='.$_COOKIE['id'].'">My Profile</a></li>';
            }else {
                echo '<li id="flacmenuli"></li> <!-- teuirtieurytst -->';
            }
        ?>

		</div>
		</div>
	</ul>


</body>
</html>
