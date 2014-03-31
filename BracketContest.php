<?php
# Alert the user that this is not a valid entry point to MediaWiki if they try to access the special pages file directly.
if (!defined('MEDIAWIKI')) {
  echo <<<EOT
To install the Bracket Manager, put the following line in LocalSettings.php:
require_once( "$IP/extensions/BracketContest/BracketContest.php" );
EOT;
  exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'BracketContest',
	'author' => '[http://wiki.teamliquid.net/starcraft2/User:PhiLtheFisH PhiLtheFisH] & [http://wiki.teamliquid.net/starcraft2/User:ChapatiyaqPTSM Chapatiyaq] ',
	'url' => 'http://wiki.teamliquid.net/starcraft2/',
	'descriptionmsg' => 'bracketcontest-desc',
	'version' => '0.1.1',
);

$dir = dirname(__FILE__) . '/';
$dirAPI = dirname(__FILE__) . '/api/';

$wgExtensionMessagesFiles['BracketContest'] = $dir . 'BracketContest.i18n.php';
$wgExtensionMessagesFiles['BracketContestAlias'] = $dir . 'BracketContest.alias.php';

$wgAutoloadClasses['SpecialBracketContest'] = $dir . 'SpecialBracketContest.php';

$wgSpecialPages['BracketContest'] = 'SpecialBracketContest';
$wgSpecialPageGroups['BracketContest'] = 'liquipedia';

$wgAutoloadClasses += array(
	'Connection'       => $dirAPI . 'Connection.php',
	'Contest'          => $dirAPI . 'Contest.php',
	'ContestTable'     => $dirAPI . 'ContestTable.php',
	'Controller'       => $dirAPI . 'Controller.php',
	'Participant'      => $dirAPI . 'Participant.php',
	'ParticipantTable' => $dirAPI . 'ParticipantTable.php'
);

$bracketContestTpl = array(
    'localBasePath' => dirname( __FILE__ ) . '/modules',
    'remoteExtPath' => 'BracketContest/modules',
    'group' => 'ext.bracketContest'
);
$wgResourceModules += array(
	'ext.bracketContest.BasePage' => $bracketContestTpl + array(
		'scripts' => array( 'jquery.tablesorter.min.js',
			'ext.bracketContest.BasePage.js'
		),
		'styles' => array(
			'ext.bracketContest.BasePage.css',
			'ext.bracketContest.theme.default.css'
		)
	),
	'ext.bracketContest.RankingPage' => $bracketContestTpl + array(
		'scripts' => array( 'jquery.tablesorter.min.js',
			'jquery.tablesorter.pager.min.js',
			'jquery.tablesorter.widgets.min.js',
			'jquery.tablesorter.widgets-filter-formatter.min.js',
			'ext.bracketContest.RankingPage.js'
		),
		'styles' => array( 'ext.bracketContest.RankingPage.css',
			'ext.bracketContest.theme.default.css',
			'jquery.tablesorter.pager.min.css'
		)
	),
	'ext.bracketContest.UserPage' => $bracketContestTpl + array(
		'scripts' => array( 'jquery.tablesorter.min.js',
			'ext.bracketContest.UserPage.js'
		),
		'styles' => 'ext.bracketContest.theme.default.css'
	),
);
