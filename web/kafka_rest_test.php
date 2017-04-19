<?php

use GuzzleHttp\Client;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client([
	'base_uri' => 'http://localhost:8082/',
	//'base_uri' => '192.168.0.188:9092',
	'headers' => [
		'Content-Type' => 'application/vnd.kafka.json.v2+json'
	]
]);

$totalMessages = 10000;
$totalTime = 0;
$timeToFirstMessage = null;
for ($i = 0; $i < $totalMessages; $i++) {
	$start = microtime(true);
	$response = $client->post(
		'topics/topic',
		[
			'body' => '{"records":[{"value":{"name": "testUser", "id": 42}}]}',
			/*'on_stats' => function (\GuzzleHttp\TransferStats $stats) {
				echo $stats->getEffectiveUri() . "\n";
				echo $stats->getTransferTime() . "\n";
			}*/
		]
	);
	$elapsed = microtime(true) - $start;
	//print 'Request ' . $i . ' took '. $elapsed .' microseconds.' . "\n";
	$totalTime += $elapsed;

	if ($timeToFirstMessage === null) {
		$timeToFirstMessage = $elapsed;
	}
}

print PHP_EOL . PHP_EOL . 'Time to first message was ' . $timeToFirstMessage . "." . PHP_EOL;
print 'Average message time for ' . $totalMessages . ' messages was ' . ($totalTime / $totalMessages) . "." . PHP_EOL;

//var_dump($response);
