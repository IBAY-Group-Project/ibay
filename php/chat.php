<?php
header('Content-Type: application/json');
include('../config.php');
include('../connection.php');

$input   = json_decode(file_get_contents('php://input'), true);
$message = isset($input['message']) ? trim($input['message']) : '';

if (!$message) {
    echo json_encode(['reply' => 'Please type a message.']);
    exit;
}

// Find relevant listings based on keywords in the message
$words = array_filter(explode(' ', $message), fn($w) => strlen($w) > 2);
$listingsContext = '';

if (!empty($words)) {
    $escaped    = array_map(fn($w) => mysqli_real_escape_string($conn, $w), $words);
    $conditions = array_map(fn($w) => "title LIKE '%$w%'", $escaped);
    $sql = "SELECT title, category, `condition`, price, postage
            FROM iBayItems
            WHERE sold = 0 AND (" . implode(' OR ', $conditions) . ")
            LIMIT 5";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $listingsContext = "\n\nRelevant listings currently on iBay:\n";
        while ($row = mysqli_fetch_assoc($result)) {
            $listingsContext .= "- {$row['title']} ({$row['category']}, {$row['condition']}) £{$row['price']}, postage: {$row['postage']}\n";
        }
    }
}

$systemPrompt = "You are iBay Assistant, a helpful shopping assistant for iBay — a student-run online marketplace similar to eBay.
Help users find items, answer questions about how the site works, and give buying advice.
Keep responses concise (2-3 sentences max) and friendly.
iBay features: browse by category, search bar, sell items, basket, checkout, account page.
Categories available: Technology, Clothing, Trading Cards, Gardening, Home, Collectables, Sports, Books.
If the user asks about specific items, refer to the listings context if provided. If no listings match, suggest they use the search bar." . $listingsContext;

$payload = [
    'contents' => [
        ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\n\nUser: " . $message]]]
    ],
    'generationConfig' => ['maxOutputTokens' => 300]
];

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . GEMINI_API_KEY;

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
]);

$response = curl_exec($ch);
curl_close($ch);

$data  = json_decode($response, true);
$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (!$reply) {
    echo json_encode(['reply' => 'Error: ' . $response]);
    exit;
}

echo json_encode(['reply' => $reply]);
