<?php

namespace Relaxed\ReplicationDemo;

require "../vendor/autoload.php";

use Doctrine\CouchDB\CouchDBClient;
use Relaxed\Replicator\ReplicationTask;
use Relaxed\Replicator\Replicator;


$sourceClient = CouchDBClient::create(array('dbname'=>'sourcedb'));
$targetClient = CouchDBClient::create(array('dbname'=>'targetdb1'));

// Add docs to the source db.
$id ='id';
$docs = array(
    array('_id' => $id . '1', 'foo' => 'bar1', '_rev' => '1-abc'),
    array('_id' => $id . '2', 'foo' => 'bar2', '_rev' => '1-bcd'),
    array('_id' => $id . '3', 'foo' => 'bar3', '_rev' => '1-cde')
);
$updater = $sourceClient->createBulkUpdater();
$updater->updateDocuments($docs);
// Set newedits to false to use the supplied _rev instead of assigning
// new ones.
$updater->setNewEdits(false);
$response = $updater->execute();

// Create the replication task.
$task = new ReplicationTask();
// Enable target creation.
$task->setCreateTarget(true);

// Create the replicator.
$replicator = new Replicator($sourceClient,$targetClient,$task);
// Get the replication report as an array.
var_dump($replicator->startReplication(false, true));
/*
 * array(3) {
  ["multipartResponse"]=>
  array(0) {
  }
  ["bulkResponse"]=>
  array(3) {
    ["id1"]=>
    array(1) {
      [0]=>
      int(201)
    }
    ["id2"]=>
    array(1) {
      [0]=>
      int(201)
    }
    ["id3"]=>
    array(1) {
      [0]=>
      int(201)
    }
  }
  ["errorResponse"]=>
  array(0) {
  }
}
 */