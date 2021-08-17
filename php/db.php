<?php

// Development
$mysqli = new mysqli("localhost", "root", "", "degen-ranking");

// Production
// $mysqli = new mysqli("localhost","angelosweb","tigersaurus500","angelosweb");

if ($mysqli->connect_errno) {
    echo "failed to Connect to Database " . $mysqli->connect_error;
}
