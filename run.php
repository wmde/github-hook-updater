<?php

/**
 * Repositories to add / alter the irc hook for
 */
$hookTargets = array(
	'wmde' => array(
		'DataValuesJavascript',
		'ValueView',
		'WikibaseDataModel',
		'WikibaseDataModelSerialization',
		'WikibaseInternalSerialization',
		'DataTypes',
		'Ask',
		'AskSerialization',
		'Serialization',
		'WikidataBuilder',
		'wikidata-analysis',
		'WikibaseDatabase',
		'WikibaseQuery',
		'Diff',
		'scrumbugz',
		'puppet-wikidata-test',
		'puppet-builder',
		'puppet-composer',
		'github-hook-updater',
	),
	'DataValues' => array(
		'Geo',
		'Number',
		'Common',
		'Interfaces',
		'DataValues',
		'Serialization',
		'Validators',
		'Time',
		'Iri',
	),
);

/**
 * Params for the irc hook
 */
$hook = array(
	'name' => 'irc',
	'active' => true,
	'config' => array(
		'server' => 'chat.freenode.org',
		'port' => '7000',
		'room' => '#wikidata',
		'nick' => 'github-wmde',
		'ssl' => '1',
	),
	'events' => array(
		'push', 'pull_request', 'commit_comment', 'pull_request_review_comment'
	),
);

require_once __DIR__ . '/vendor/autoload.php';

echo "Please generate a personal access token at https://github.com/settings/applications\n";
echo "Github Token:";
$token = stream_get_line(STDIN, 1024, "\n");

$client = new \Github\Client();
$client->authenticate( $token, null, \Github\Client::AUTH_HTTP_TOKEN );

$controller = new \GithubHookController\IrcHookController( $client );

foreach( $hookTargets as $user => $repos ) {
	foreach( $repos as $repo ) {
		$controller->setIrcHook( $hook, $user, $repo );
	}
}
