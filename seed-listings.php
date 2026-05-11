<?php
// Seed sample listings for demo/testing
// Run: php seed-listings.php
require_once __DIR__ . '/includes/db.php';

// Get agent IDs
$agents = $db->query("SELECT id, name FROM agents")->fetchAll(PDO::FETCH_KEY_PAIR);

if (count($agents) == 0) {
    echo "Run php seed.php first to populate agents.\n";
    exit;
}

$listings = [
    [
        'title' => '3011 Windwood Circle',
        'address' => '3011 Windwood Cir',
        'city' => 'Flowood', 'price' => 295000,
        'beds' => 3, 'baths' => 2, 'sqft' => 1834,
        'description' => 'Beautiful 3-bedroom home in the heart of Flowood. Open floor plan with updated kitchen, granite countertops, and hardwood floors throughout. Large master suite with walk-in closet. Fenced backyard perfect for entertaining. Close to shopping, dining, and top-rated schools.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 1,
        'agent_id' => $agents['Billy McKee'] ?? key($agents)
    ],
    [
        'title' => '1004 Sapphire Crossing',
        'address' => '1004 Sapphire Xing',
        'city' => 'Flowood', 'price' => 529900,
        'beds' => 4, 'baths' => 3, 'sqft' => 2648,
        'description' => 'Stunning 4-bedroom home in a premier Flowood neighborhood. Gourmet kitchen with stainless steel appliances, quartz countertops, and custom cabinetry. Spacious living room with gas fireplace. Primary suite features spa-like bathroom. Covered patio overlooking landscaped yard.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 1,
        'agent_id' => $agents['Christy Ingram'] ?? key($agents)
    ],
    [
        'title' => '100 Indian Creek Boulevard',
        'address' => '100 Indian Creek Blvd',
        'city' => 'Brandon', 'price' => 652000,
        'beds' => 4, 'baths' => 4, 'sqft' => 3520,
        'description' => 'Exceptional 4-bedroom, 4-bathroom home in the coveted Indian Creek community. Two-story foyer, formal dining room, and a chef\'s kitchen with premium appliances. Master suite on main level with luxury bath. Three-car garage. Community amenities include pool and clubhouse.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 1,
        'agent_id' => $agents['Julie White'] ?? key($agents)
    ],
    [
        'title' => '609 Big Valley Loop',
        'address' => '609 Big Valley Loop',
        'city' => 'Flowood', 'price' => 748000,
        'beds' => 4, 'baths' => 3, 'sqft' => 2968,
        'description' => 'Magnificent 4-bedroom home in the sought-after Big Valley community. Soaring ceilings, exquisite millwork, and an open concept design. Gourmet kitchen with professional-grade appliances. Resort-style backyard with covered lanai. Located in the award-winning Rankin County school district.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 1,
        'agent_id' => $agents['Susannah Fielder'] ?? key($agents)
    ],
    [
        'title' => '340 Sherborne Place',
        'address' => '340 Sherborne Pl',
        'city' => 'Madison', 'price' => 684000,
        'beds' => 4, 'baths' => 4, 'sqft' => 3969,
        'description' => 'Elegant 4-bedroom home in the prestigious Sherborne Place subdivision. Grand foyer with curved staircase, private study, formal living and dining rooms. Chef\'s kitchen opens to great room. Luxurious master suite with sitting area. Professionally landscaped lot with sprinkler system.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['Barbara Mooney'] ?? key($agents)
    ],
    [
        'title' => '202 Hand Drive',
        'address' => '202 Hand Dr',
        'city' => 'Flowood', 'price' => 355000,
        'beds' => 4, 'baths' => 3, 'sqft' => 2910,
        'description' => 'Well-maintained 4-bedroom home in a quiet Flowood neighborhood. Updated kitchen with new countertops and backsplash. Open living area with natural light. Large bedrooms with ample closet space. Private backyard with mature trees. Convenient access to airport and medical centers.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['April McKee'] ?? key($agents)
    ],
    [
        'title' => '188 Webb Lane',
        'address' => '188 Webb Ln',
        'city' => 'Flowood', 'price' => 799900,
        'beds' => 5, 'baths' => 4, 'sqft' => 5248,
        'description' => 'Immaculate 5-bedroom estate on a generous lot in Flowood. Two-story great room with wall of windows. Gourmet island kitchen, butler\'s pantry. Main-level master suite with spa bath. Media room, game room, and home office. Resort-style pool and outdoor kitchen. Must see to appreciate!',
        'property_type' => 'House', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['Billy McKee'] ?? key($agents)
    ],
    [
        'title' => '625 Big Valley Loop',
        'address' => '625 Big Valley Loop',
        'city' => 'Flowood', 'price' => 764900,
        'beds' => 4, 'baths' => 4, 'sqft' => 2930,
        'description' => 'Stunning 4-bedroom home in Big Valley. High-end finishes throughout including hardwood floors, custom cabinetry, and premium countertops. Open concept living with fireplace. Covered outdoor living area. Community amenities include walking trails and green space.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['Jordan Stigall'] ?? key($agents)
    ],
    [
        'title' => '705 Queens Court',
        'address' => '705 Queens Ct',
        'city' => 'Flowood', 'price' => 410000,
        'beds' => 4, 'baths' => 4, 'sqft' => 2800,
        'description' => 'Charming 4-bedroom home on a quiet cul-de-sac in Flowood. Recently updated with fresh paint, new flooring, and modern fixtures. Split bedroom plan with spacious master suite. Large backyard with patio. Great location near restaurants, shopping, and Rankin County schools.',
        'property_type' => 'House', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['Debbie Walker'] ?? key($agents)
    ],
    [
        'title' => '407 Watertone Drive',
        'address' => '407 Watertone Dr',
        'city' => 'Flowood', 'price' => 92000,
        'beds' => 0, 'baths' => 0, 'sqft' => 0,
        'description' => 'Beautiful building lot in the Watertone community. Water view lot perfect for building your dream home. Flat, buildable lot with utilities available. Located in a desirable Flowood neighborhood close to everything. Bring your builder!',
        'property_type' => 'Land', 'status' => 'active', 'featured' => 0,
        'agent_id' => $agents['Andy Walker'] ?? key($agents)
    ],
];

$count = 0;
$stmt = $db->prepare("INSERT INTO listings (title, address, city, state, zip, price, beds, baths, sqft, description, property_type, status, featured, agent_id) VALUES (?, ?, ?, 'MS', '39232', ?, ?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($listings as $l) {
    $stmt->execute([$l['title'], $l['address'], $l['city'], $l['price'], $l['beds'], $l['baths'], $l['sqft'], $l['description'], $l['property_type'], $l['status'], $l['featured'], $l['agent_id']]);
    $count++;
}

echo "Seeded $count sample listings.\n";