<?php
// Test Paystack Configuration
require_once 'vendor/autoload.php';

use Yabacon\Paystack;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$secretKey = $_ENV['PAYSTACK_SECRET_KEY'] ?? 'not-set';
$publicKey = $_ENV['PAYSTACK_PUBLIC_KEY'] ?? 'not-set';

echo "Testing Paystack Configuration\n";
echo "==============================\n";
echo "Secret Key: " . (strlen($secretKey) > 10 ? substr($secretKey, 0, 10) . '...' : $secretKey) . "\n";
echo "Public Key: " . (strlen($publicKey) > 10 ? substr($publicKey, 0, 10) . '...' : $publicKey) . "\n\n";

try {
    $paystack = new Paystack($secretKey);
    
    // Test API connection by fetching banks (doesn't require any special permissions)
    echo "Testing API connection...\n";
    $banks = $paystack->bank->getList(['country' => 'kenya']);
    
    if ($banks->status) {
        echo "✅ API connection successful!\n";
        echo "Number of banks found: " . count($banks->data) . "\n";
    } else {
        echo "❌ API connection failed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "This usually means:\n";
    echo "- Invalid API keys\n";
    echo "- Network connection issues\n";
    echo "- Paystack API is down\n";
}