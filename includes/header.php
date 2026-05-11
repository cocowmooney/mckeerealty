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
                    colors: {
                        navy: '#1e3a5f',
                        gold: '#c9a84c',
                        'gold-light': '#dbb95c',
                        'navy-light': '#2a4f7a',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50 text-gray-800 antialiased">
    <!-- Header -->
    <header class="bg-navy text-white shadow sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <a href="/" class="flex items-center">
                    <img src="/assets/images/logo.png" alt="McKee Realty Inc." class="h-12 md:h-14 w-auto">
                </a>
                <button id="menu-toggle" class="md:hidden p-2 hover:bg-navy-light transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
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