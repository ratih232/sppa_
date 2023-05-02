<?php

$mysqli = new mysqli("localhost", "root", "", "bansos");

if (!$mysqli) {
    echo "Koneksi bermasalah !";
}
