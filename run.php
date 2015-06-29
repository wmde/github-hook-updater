<?php

/**
 * Repositories to add / alter the irc hook for
 */
$hookTargets = array(
	'#wikidata-feed' => array(
		'wmde' => array(
			'Ask',
			'AskSerialization',
			'AskWikitextSerialization',
			'DataTypes',
			'DataValuesJavascript',
			'Diff',
			'puppet-wikidata-test',
			'puppet-builder',
			'puppet-composer',
			'Serialization',
			'ValueView',
			'WikibaseDatabase',
			'WikibaseDataModel',
			'WikibaseDataModelServices',
			'WikibaseDataModelSerialization',
			'WikibaseDataModelJavascript',
			'WikibaseEntityStore',
			'WikibaseInternalSerialization',
			'WikibaseSerializationJavaScript',
			'WikibaseQuery',
			'WikibaseQueryEngine',
			'Wikiba.se',
			'WikidataBuilder',
			'WikidataBuildResources',
			'WikidataBrowserTests',
			'WikidataBrowserTestsRSpec',
			'wikidata-analysis',
			'Wikidata.org',
			'WikimediaBadges',
		),
		'Wikidata' => array(
			'easyrdf_lite',
		),
		'Wikidata-lib' => array(
			'PropertySuggester',
			'PropertySuggester-Python',
			'Wikidata.lib',
			'PubSubHubbubSubscriber',
			'ConnectionFinder',
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
	),
	'#wikimedia-de-tech' => array(
		'wmde' => array(
			'github-hook-updater',
			'phragile',
			'Lizenzverweisgenerator',
			'DeepCat-Gadget',
			'catgraph-client-python',
			'catgraph-client-php',
			'catgraph-jsonp',
			'catgraph-service',
			'catgraph-web',
			'graphserv',
			'graphcore',
			'graphcare',
		),
	),
);

require_once __DIR__ . '/vendor/autoload.php';

echo "Please generate a personal access token at https://github.com/settings/tokens\n";
echo "The token can be deleted after using run.php\n";
echo "It is probably best to give the token ALL permissions\n";
echo "Github Token:";
$token = stream_get_line(STDIN, 1024, "\n");

$client = new \Github\Client();
$client->authenticate( $token, null, \Github\Client::AUTH_HTTP_TOKEN );

$controller = new \GithubHookController\IrcHookController( $client );

echo "Adding and Updating hooks!\n";
foreach( $hookTargets as $channel => $userRepos ) {
	echo "\n$channel\n";
	foreach ( $userRepos as $user => $repos ) {
		foreach ( $repos as $repo ) {

			$hook = array(
				'name' => 'irc',
				'active' => true,
				'config' => array(
					'server' => 'chat.freenode.org',
					'port' => '7000',
					'room' => $channel,
					'nick' => 'gh-' . strtolower( $user ),
					'ssl' => '1',
				),
				'events' => array(
					'push', 'pull_request', 'commit_comment', 'pull_request_review_comment'
				),
			);
			try {
				$controller->setIrcHook( $hook, $user, $repo );
				echo( "Done " . $user . '/' . $repo . "\n" );
			}
			catch( Github\Exception\RuntimeException $e ) {
				echo "Error " . $user . '/' . $repo . ': ' . $e->getCode() . ' ' . $e->getMessage();
				if( $e->getCode() == "404" ) {
					echo " (this could be due to lack of permissions on the repo)";
				}
				echo "\n";
			}
		}
	}
}
