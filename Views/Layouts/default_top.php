<?= start_html(GAME_NAME); ?>

<?php
    

    if ($Auth->isConnected()) {
         include('Views/Elements/menu.php');
         echo '<br/><br/>';
    }
?>