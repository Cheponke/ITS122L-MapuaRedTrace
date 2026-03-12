<?php
session_start();      // start the session
session_destroy();    // remove all session data
header("Location: login.html"); // redirect to login page
exit();
?>