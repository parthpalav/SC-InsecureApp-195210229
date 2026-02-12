<?php
/*
 * VULNERABILITY: NO SESSION TIMEOUT
 * - Sessions never expire
 * - No timeout mechanism
 * - No regeneration of session ID
 */

session_start();

// Simple logout - no security measures
session_destroy();

header("Location: index.php");
exit();
?>
