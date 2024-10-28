<?php

include('vendor/autoload.php');

use ElephantIO\Client as Elephant;

$elephant = new Elephant('http://localhost:3000', 'socket.io', 1, false, true, true);
new Elephant();

?>
