<?php

setcookie('sid', '', -1); //définit un cookie qui sera envoyé avec le reste des en-têtes HTTP. 

header("Location: index.php");
exit();

