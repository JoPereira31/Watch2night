<?php
session_unset();
session_destroy();
header('location: accueil');
exit;