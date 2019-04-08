<?php
global $current_user;
?>

<form action="" method="post">
    <h3 class="entry_title">Sent Messages</h2>
    
    
    <div class="messages_table" style="margin:0 0 20px 0;">    
<?php

	show_messages('sent',10);
?>
</div>
   
</form>