<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing API Configuration ===\n\n";

// Test Groq
$groqKey = config('services.groq.key');
echo "Groq Key: " . ($groqKey ? substr($groqKey, 0, 15) . "..." : "NOT FOUND") . "\n";

// Test Hugging Face
$hfKey = config('services.huggingface.key');
echo "HF Key: " . ($hfKey ? substr($hfKey, 0, 10) . "..." : "NOT FOUND") . "\n\n";

// Test Groq API
if ($groqKey) {
    echo "Testing Groq API...\n";
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $groqKey,
        'Content-Type' => 'application/json',
    ])->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => 'llama-3.1-70b-versatile',
        'messages' => [
            ['role' => 'user', 'content' => 'Say hello']
        ],
        'max_tokens' => 10,
    ]);
    
    if ($response->successful()) {
        echo "✅ Groq API is working!\n\n";
    } else {
        echo "❌ Groq API failed: " . $response->status() . "\n";
        echo $response->body() . "\n\n";
    }
}

// Test Hugging Face API
if ($hfKey) {
    echo "Testing Hugging Face API...\n";
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'Authorization' => 'Bearer ' . $hfKey,
    ])->get('https://huggingface.co/api/whoami');
    
    if ($response->successful()) {
        echo "✅ Hugging Face API is working!\n";
        $data = $response->json();
        echo "User: " . ($data['name'] ?? 'Unknown') . "\n\n";
    } else {
        echo "❌ Hugging Face API failed: " . $response->status() . "\n";
        echo $response->body() . "\n\n";
    }
}

// Test filesystem
echo "Testing Filesystem...\n";
echo "FILESYSTEM_DISK: " . config('filesystems.default') . "\n";
echo "Storage path: " . storage_path('app/public') . "\n";

if (config('filesystems.default') === 'public') {
    echo "✅ Filesystem configured correctly!\n";
} else {
    echo "❌ Filesystem should be 'public' but is: " . config('filesystems.default') . "\n";
}