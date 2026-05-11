<?php
// Cleanup script - run via cron on Linode to delete contacts older than 21 days
// Add to crontab: 0 3 * * 0 php /path/to/cleanup.php

require_once __DIR__ . '/includes/db.php';

$stmt = $db->prepare("DELETE FROM contacts WHERE created_at < datetime('now', '-21 days')");
$stmt->execute();
$deleted = $stmt->rowCount();

echo "[" . date('Y-m-d H:i:s') . "] Cleaned up $deleted old contact submissions.\n";