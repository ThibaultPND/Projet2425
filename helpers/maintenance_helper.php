<?php


function isMaintenanceActive($db) {
    $db->query("SELECT value FROM settings WHERE name = 'maintenance'");
    $result = $db->single();
    return $result->value;
}

