<?php
// One-time seed script to populate agents
// Run this once: php seed.php
require_once __DIR__ . '/includes/db.php';

$agents = [
    ['Billy McKee', 'Broker/Owner', 'mckeerealty@att.net', '601-992-8141', '601-260-2256'],
    ['Aron Albright', 'Realtor', 'asalbright01@yahoo.com', '601-992-8141', '601-278-8625'],
    ['Susannah Fielder', 'Realtor', 'susannahfielder1968@gmail.com', '601-992-8141', '601-624-7159'],
    ['Jose G. Freyre', 'Realtor', 'freyrejose@gmail.com', '601-992-8141', '318-418-8997'],
    ['Christy Ingram', 'Realtor', 'christyingram3@gmail.com', '601-992-8141', '601-906-8936'],
    ['April McKee', 'Realtor', 'amckee7@yahoo.com', '601-992-8141', '601-624-5274'],
    ['Barbara Mooney', 'Realtor', 'btmooney@yahoo.com', '601-992-8141', '662-386-2923'],
    ['Jennifer Robertson', 'Realtor', 'jennifer.miller.robertson@gmail.com', '601-992-8141', '601-573-5451'],
    ['Suzi Smith', 'Realtor', 'suzinneth13@gmail.com', '601-992-8141', '601-951-9003'],
    ['Jordan Stigall', 'Realtor', 'jordan.stigall16@gmail.com', '601-992-8141', '601-813-4282'],
    ['Debbie Walker', 'Broker Associate', 'thc.debbie@gmail.com', '601-992-8141', '601-562-9373'],
    ['Andy Walker', 'Realtor', 'awalkersells@gmail.com', '601-992-8141', '601-940-1091'],
    ['Amy Weeks', 'Realtor', 'amygweeks@bellsouth.net', '601-992-8141', '601-942-4532'],
    ['Julie White', 'Realtor', 'realtorjuliewhite@gmail.com', '601-992-8141', '601-906-3779'],
    ['Stephanie Williams', 'Realtor', 'stephaniesellsms@gmail.com', '601-992-8141', '601-941-5589'],
];

$count = 0;
$existing = $db->query("SELECT COUNT(*) FROM agents")->fetchColumn();

if ($existing == 0) {
    $stmt = $db->prepare("INSERT INTO agents (name, role, email, phone, phone2) VALUES (?, ?, ?, ?, ?)");
    foreach ($agents as $a) {
        $stmt->execute($a);
        $count++;
    }
    echo "Seeded $count agents.\n";
} else {
    echo "Agents table already has $existing entries. No seeding needed.\n";
}