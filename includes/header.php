<?php
// Database connection - include this in every page that needs DB
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
                        'gold-light': '#e0c56a',
                        'navy-light': '#2a4f7a',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="bg-navy text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16 md:h-20">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gold rounded-lg flex items-center justify-center">
                        <span class="text-navy font-bold text-lg">M</span>
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-bold text-lg tracking-tight">McKee Realty</span>
                        <span class="block text-xs text-gold -mt-1">Flowood, MS</span>
                    </div>
                </a>
                <!-- Mobile menu button -->
                <button id="menu-toggle" class="md:hidden p-2 hover:bg-navy-light rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <!-- Desktop nav -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="/" class="px-4 py-2 rounded-lg hover:bg-navy-light transition font-medium <?= basename($_SERVER['SCRIPT_NAME']) == 'index.php' ? 'text-gold' : '' ?>">Home</a>
                    <a href="/listings.php" class="px-4 py-2 rounded-lg hover:bg-navy-light transition font-medium <?= basename($_SERVER['SCRIPT_NAME']) == 'listings.php' ? 'text-gold' : '' ?>">Listings</a>
                    <a href="/associates.php" class="px-4 py-2 rounded-lg hover:bg-navy-light transition font-medium <?= basename($_SERVER['SCRIPT_NAME']) == 'associates.php' ? 'text-gold' : '' ?>">Agents</a>
                    <a href="/contact.php" class="px-4 py-2 rounded-lg hover:bg-navy-light transition font-medium <?= basename($_SERVER['SCRIPT_NAME']) == 'contact.php' ? 'text-gold' : '' ?>">Contact</a>
                </nav>
            </div>
            <!-- Mobile nav -->
            <nav id="mobile-menu" class="hidden md:hidden pb-4 space-y-1">
                <a href="/" class="block px-4 py-2 rounded-lg hover:bg-navy-light transition">Home</a>
                <a href="/listings.php" class="block px-4 py-2 rounded-lg hover:bg-navy-light transition">Listings</a>
                <a href="/associates.php" class="block px-4 py-2 rounded-lg hover:bg-navy-light transition">Agents</a>
                <a href="/contact.php" class="block px-4 py-2 rounded-lg hover:bg-navy-light transition">Contact</a>
            </nav>
        </div>
    </header>
    <main>