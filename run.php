<?php

/**
 * Repositories to add / alter the irc hook for
 */
$hookTargets = array(
	'wmde' => array(
		'Ask',
		'AskSerialization',
		'DataTypes',
		'DataValuesJavascript',
		'Diff',
		'github-hook-updater',
		'puppet-wikidata-test',
		'puppet-builder',
		'puppet-composer',
		'scrumbugz',
		'Serialization',
		'ValueView',
		'WikibaseApiJavaScript',
		'WikibaseDatabase',
		'WikibaseDataModel',
		'WikibaseDataModelSerialization',
		'WikibaseDataModelJavascript',
		'WikibaseInternalSerialization',
		'WikibaseSerializationJavaScript',
		'WikibaseQuery',
		'Wikiba.se',
		'WikidataBuilder',
		'WikidataBuildResources',
		'WikidataBrowserTests',
		'wikidata-analysis',
		'Wikidata.org',
		'WikimediaBadges',
	),
	'Wikidata' => array(
		'easyrdf_lite',
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

require_once __DIR__ . '/vendor/autoload.php';

echo "Please generate a personal access token at https://github.com/settings/applications\n";
echo "Github Token:";
$token = stream_get_line(STDIN, 1024, "\n");

$client = new \Github\Client();
$client->authenticate( $token, null, \Github\Client::AUTH_HTTP_TOKEN );

$controller = new \GithubHookController\IrcHookController( $client );

foreach( $hookTargets as $user => $repos ) {
	foreach( $repos as $repo ) {

		$hook = array(
			'name' => 'irc',
			'active' => true,
			'config' => array(
				'server' => 'chat.freenode.org',
				'port' => '7000',
				'room' => '#wikidata',
				'nick' => 'gh-' . strtolower( $user ),
				'ssl' => '1',
			),
			'events' => array(
				'push', 'pull_request', 'commit_comment', 'pull_request_review_comment'
			),
		);

		$controller->setIrcHook( $hook, $user, $repo );
	}
}
