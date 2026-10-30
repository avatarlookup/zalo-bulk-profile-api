<?php
$key = getenv('AVATARLOOKUP_API_KEY');
if (!$key) {
    throw new Exception('Set AVATARLOOKUP_API_KEY');
}
$base = rtrim(getenv('API_BASE_URL') ?: 'https://avatarlookup.com', '/');
$curl = curl_init($base.'/api/v1/bulk-tasks');
curl_setopt_array($curl, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['X-API-Key: '.$key],
    CURLOPT_POSTFIELDS => [
        'service_type' => 'zalo_profile_batch',
        'country' => 'US',
        'file' => new CURLFile(getenv('FILE') ?: 'examples/numbers.txt'),
    ],
]);
$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($response === false || $status >= 300) {
    throw new Exception($response ?: curl_error($curl));
}
echo $response, PHP_EOL;
