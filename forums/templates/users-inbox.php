<?php
global $current_user;
?>

<h3 class="entry_title">Received Messages</h2>

<div class="messages_table" style="margin:0 0 20px 0;"> 

<form action="" method="post">   
<?php

	show_messages('inbox',10);
?>
</form>
</div>
    
    