<?php

require_once 'vendor/autoload.php';

$sentinels = array(
    'tcp://127.0.0.1:26379?timeout=0.100',
    'tcp://127.0.0.1:26380?timeout=0.100',
    'tcp://127.0.0.1:26381?timeout=0.100',
);
$client = new Predis\Client($sentinels, array(
    'replication' => 'sentinel',
    'service' => 'mymaster',
));

// Read operation.
$exists = $client->exists('foo') ? 'yes' : 'no';

$current = $client->getConnection()->getCurrent()->getParameters();

echo "Does 'foo' exist on {$current->alias}? $exists.", PHP_EOL;

// Write operation.
$client->set('foo', 'bar');

$current = $client->getConnection()->getCurrent()->getParameters();

echo "Now 'foo' has been set to 'bar' on {$current->alias}!", PHP_EOL;

// Read operation.
$bar = $client->get('foo');

$current = $client->getConnection()->getCurrent()->getParameters();

echo "We fetched 'foo' from {$current->alias} and its value is '$bar'.", PHP_EOL;