<?php
require_once __DIR__ . '/db.php';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'McKee Realty Inc.' ?> | McKee Realty</title>
    <meta name="description" content="<?= $page_desc ?? 'Flowood, MS real estate - McKee Realty Inc. Your trusted partner for buying and selling homes in Central Mississippi.' ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'sans': ['Inter', 'sans-serif'] },
                    colors: { navy: '#1e3a5f', gold: '#c9a84c', 'gold-light': '#dbb95c', 'navy-light': '#2a4f7a' }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50 text-gray-800 antialiased">
    <!-- Contact Info Bar (legally required) -->
    <div class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600">
        <div class="container mx-auto px-4 py-1.5 flex flex-wrap items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    300 Belle Meade Pt. Ste. B, Flowood, MS 39232
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    (601) 992-8141
                </span>
            </div>
            <a href="/contact.php" class="text-gold hover:underline hidden sm:inline">Contact Us</a>
        </div>
    </div>
    <header class="bg-navy text-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <a href="/" class="flex items-center"><img src="/assets/images/logo.png" alt="McKee Realty Inc." class="h-12 md:h-14 w-auto"></a>
                <button id="menu-toggle" class="md:hidden p-2 hover:bg-navy-light transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="/" class="px-3 py-2 text-sm font-medium hover:bg-navy-light transition <?= basename($_SERVER['SCRIPT_NAME']) == 'index.php' ? 'text-gold' : 'text-gray-200' ?>">Home</a>
                    <a href="/listings.php" class="px-3 py-2 text-sm font-medium hover:bg-navy-light transition <?= basename($_SERVER['SCRIPT_NAME']) == 'listings.php' ? 'text-gold' : 'text-gray-200' ?>">Listings</a>
                    <a href="/associates.php" class="px-3 py-2 text-sm font-medium hover:bg-navy-light transition <?= basename($_SERVER['SCRIPT_NAME']) == 'associates.php' ? 'text-gold' : 'text-gray-200' ?>">Agents</a>
                    <a href="/contact.php" class="px-3 py-2 text-sm font-medium hover:bg-navy-light transition <?= basename($_SERVER['SCRIPT_NAME']) == 'contact.php' ? 'text-gold' : 'text-gray-200' ?>">Contact</a>
                </nav>
            </div>
            <nav id="mobile-menu" class="hidden md:hidden pb-4 space-y-1">
                <a href="/" class="block px-3 py-2 text-sm text-gray-200 hover:bg-navy-light transition">Home</a>
                <a href="/listings.php" class="block px-3 py-2 text-sm text-gray-200 hover:bg-navy-light transition">Listings</a>
                <a href="/associates.php" class="block px-3 py-2 text-sm text-gray-200 hover:bg-navy-light transition">Agents</a>
                <a href="/contact.php" class="block px-3 py-2 text-sm text-gray-200 hover:bg-navy-light transition">Contact</a>
            </nav>
        </div>
    </header>
    <main>