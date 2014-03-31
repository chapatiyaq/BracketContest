<?php
/*include ('global.php');
include ('Connection.php');
include ('Controller.php');
include ('Contest.php');
include ('ContestTable.php');
include ('Participant.php');
include ('ParticipantTable.php');*/

class SpecialBracketContest extends SpecialPage {
	protected $controller;

	function __construct() {
		parent::__construct( 'BracketContest' );

		$this->controller = new Controller();
	}

	function execute( $par ) {
		$request = $this->getRequest();
		$out = $this->getOutput();
		$this->setHeaders();
		
		if ( $request->getVal('module') == 'ranking' && is_numeric($request->getVal('id'))) {
			self::executeRanking();
		} else if ( $request->getVal('module') == 'user' ) {
			self::executeUser();
		} else {
			self::executeBase();
		}
	}

	function executeBase() {
		$out = $this->getOutput();

		$out->addModules( 'ext.bracketContest.BasePage' );
		$out->setPageTitle(wfMessage('bracketcontest-base-page-title'));

		$contests = $this->controller->getContests(array('startdate' => 'DESC', 'id' => 'DESC'));

		$lastThreeContests = array_pad( array_slice($contests, 0, 3), 3, null);
		self::addLastThreeContentsBanners( $lastThreeContests );

		self::addContestsTable( $contests );
	}

	function executeRanking() {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$out->addModules( 'ext.bracketContest.RankingPage' );
		$out->setSubtitle(Linker::link($this->getTitle(), 'All contests'));

		$id = $request->getVal('id');
		$contest = $this->controller->getContest( $id );
		if ( $contest->id === null ) {
			$out->addHTML(wfMessage('bracketcontest-contest-not-found'));
			return;
		}

		$out->setPageTitle($contest->title);

		// Pager 1
		$this->addPager();

		// Table
		$rankingsTable = array(
			'header' => array( 
				array( 'html' => '#', 'attributes' => array('class' => 'filter-false') ),
				array( 'html' => 'Name', 'attributes' => array('width' => '160') ),
				array( 'html' => 'Points', 'attributes' => array('class' => 'filter-false') ),
				array( 'html' => 'Submission', 'attributes' => array('class' => 'filter-false') )
			),
			'rows' => array(),
			'attributes' => array('id' => 'ranking',
				'style' => 'width:350px;'
			)
		);
		$participants = $this->controller->getParticipants($id);
		$ranking = 0;
		$index = 0;
		$points = null;
		foreach ($participants as $participant) {
			$index++;
			if ($participant->points != $points) {
				$ranking = $index;
				$points = $participant->points;
			}

			$rankingsTable['rows'][] = array(
				array( 'html' => $ranking ),
				array( 'html' => Linker::link( $this->getTitle(), $participant->name, array(), array( 'module' => 'user', 'name' => $participant->name )) ),
				array( 'html' => $participant->points ),
				array( 'html' => self::getSubmissionLinkFromURL($participant->link) )
			);
		}

		$out->addHTML(self::buildTable($rankingsTable));

		// Pager 2
		$this->addPager();
	}

	function executeUser() {
		$request = $this->getRequest();
		$out = $this->getOutput();

		$out->addModules( 'ext.bracketContest.UserPage' );

		$name = $request->getVal('name');
		$participant = $this->controller->getParticipantByName( $name );
		if ( $participant->id === null ) {
			$out->addHTML(wfMessage('bracketcontest-participant-not-found'));
			return;
		}

		$out->setPageTitle('User submissions');

		$submissions = $this->controller->getSubmissions( $participant->id );

		$nt = Title::makeTitleSafe( NS_USER, $name );
		$out->setSubtitle('For ' . Linker::link($nt, $name) . ' &bull; ' . Linker::link($this->getTitle(), 'All contests'));

		// Table
		$submissionsTable = array(
			'header' => array(
				array( 'html' => 'Title' , 'attributes' => array( 'width' => '300') ),
				array( 'html' => 'Game' ),
				array( 'html' => 'Points' ),
				array( 'html' => 'Submission' )
			),
			'rows' => array(),
			'attributes' => array('id' => 'submissions',
				'style' => 'width:600px;'
			)
		);
		foreach ($submissions as $submission) {
			$submissionsTable['rows'][] = array(
				array( 'html' => Linker::link( $this->getTitle(), $submission['title'], array(), array( 'module' => 'ranking', 'id' => $submission['id'] )) ),
				array( 'html' => $submission['game'] ),
				array( 'html' => $submission['points'] ),
				array( 'html' => self::getSubmissionLinkFromURL($submission['link']) )
			);
		}

		$out->addHTML(self::buildTable($submissionsTable));
	}

	function addLastThreeContentsBanners( $lastThreeContests ) {
		$out = $this->getOutput();

		$output = '<div class="bct-last-three-contests">';

		foreach ($lastThreeContests as $contest) {
			$link = '';
			
			if ($contest !== null) {
				$imageTitle = Title::makeTitleSafe(NS_FILE, 'BC' . $contest->id . '.jpg');
				$imageFile = wfFindFile( $imageTitle );
				if (is_object( $imageFile ) && $imageFile->exists()) {
					$link = Linker::makeImageLink2( $imageTitle,
						$imageFile,
						array('link-url' => $this->getTitle()->getFullURL('module=ranking&id=' . $contest->id), 'title' => $contest->title),
						array('width' => 267, 'height' => 178)
					);
				} else {
					$link = '<div>' . Linker::link( $this->getTitle(), $contest->title, array('title' => $contest->title), array( 'module' => 'ranking', 'id' => $contest->id )) . '</div>';
				}
			}

			$output .= '<div class="bct-banner">' . $link . '</div>';
		}

		$output .= '</div>';

		$out->addHTML($output);
	}

	function addContestsTable( $contests ) {
		$out = $this->getOutput();

		$contestsTable = array(
			'header' => array(
				array( 'html' => 'Title' , 'attributes' => array( 'width' => '250') ),
				array( 'html' => 'Game' ),
				array( 'html' => 'Start' ),
				array( 'html' => 'Submissions before' ),
				array( 'html' => 'End' ),
			),
			'rows' => array(),
			'attributes' => array('id' => 'contest',
				'style' => 'width:850px;'
			)
		);

		foreach ($contests as $contest) {
			$imageTitle = Title::makeTitleSafe(NS_FILE, 'BC' . $contest->id . '_small.png');
			$imageFile = wfFindFile( $imageTitle );
			if (!is_object( $imageFile ) || !$imageFile->exists()) {
				$image = Html::rawElement('a',
					array('href' => $this->getTitle()->getFullURL('module=ranking&id=' . $contest->id)),
					Html::element('div', array('class' => 'small-icon-filler'))
				);
			} else {
				$image = Linker::makeImageLink2( $imageTitle,
					$imageFile,
					array('link-url' => $this->getTitle()->getFullURL('module=ranking&id=' . $contest->id)),
					array('width' => 25, 'height' => 25)
				);
			}
			$contestsTable['rows'][] = array(
				array(
					'html' => $image . '&nbsp;&nbsp;' . Linker::link( $this->getTitle(), $contest->title, array('title' => $contest->title), array( 'module' => 'ranking', 'id' => $contest->id ))
				),
				array(
					'html' => $contest->game,
					'attributes' => array( 'class' => strtolower( str_replace( array(':', ' '), array('-', '-'), $contest->game ) ) )
				),
				array(
					'html' => $contest->startdate ? date_format(date_create($contest->startdate), 'Y-m-d H:i') : ''
				),
				array(
					'html' => $contest->submissiondate ? date_format(date_create($contest->submissiondate), 'Y-m-d H:i') : ''
				),
				array(
					'html' => $contest->enddate ? date_format(date_create($contest->enddate), 'Y-m-d H:i') : ''
				)
			);
		}

		$out->addHTML(self::buildTable($contestsTable));
	}

	function getSubmissionLinkFromURL( $url ) {
		$url = str_replace('&oldid=', '?oldid=', $url);
		return Html::element('a', array('href' => $url, 'title' => 'link'), 'link');
	}

	function addPager() {
		$out = $this->getOutput();

		$html = <<<EOT
	<div id="pager" class="pager">
		<form>
		<img class="first"/>
		<img class="prev"/>
		<span class="pagedisplay"></span>
		<img class="next"/>
		<img class="last"/>
		<select class="pagesize">
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option selected="selected" value="50">50</option>
			<option value="70">70</option>
			<option value="100">100</option>
		</select>
		</form>
	</div>

EOT;
		$out->addHTML($html);
	}

	function buildTable( $variables ) {
		$header = $variables['header'];
		$rows = $variables['rows'];
		$attributes = $variables['attributes'];

		$innerTableHTML = "\n";

		// Header
		if (count($header)) {
			$innerTableHTML .= "  <thead>\n";
			$innerTableHTML .= "    <tr>\n";
			foreach ($header as $cell) {
				$cellAttributes = isset( $cell['attributes'] ) ? $cell['attributes'] : null;
				$innerTableHTML .= '      ' . Html::element( 'th', $cellAttributes, $cell['html'] ) . "\n";
			}
			$innerTableHTML .= "    </tr>\n";
			$innerTableHTML .= "  </thead>\n";
		}

		// Rows
    	$innerTableHTML .= "  <tbody>\n";
		if (count($rows)) {
    		foreach($rows as $row) {
				$innerTableHTML .= "    <tr>\n";
				foreach ($row as $cell) {
					$cellAttributes = isset( $cell['attributes'] ) ? $cell['attributes'] : null;
					$innerTableHTML .= '      ' . Html::rawElement( 'td', $cellAttributes, $cell['html'] ) . "\n";
				}
				$innerTableHTML .= "    </tr>\n";
			}
    	}
    	$innerTableHTML .= "  </tbody>\n";

    	// Table
		$output = Html::rawElement( 'table',
			$attributes,
			$innerTableHTML
		);

		return $output;
	}
}
