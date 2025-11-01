<?php


function isMaintenanceActive($db) {
    $db->query("SELECT value FROM settings WHERE name = 'maintenance'");
    $result = $db->resultArray();

    return $result[0]['value'];
}

