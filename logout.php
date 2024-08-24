<?php

session_start();

# unset($_SESSION['user']);
session_destroy();
Header("Location: index.php");
