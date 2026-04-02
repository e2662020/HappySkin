<?php

namespace MediaWiki\Skin\HappySkin;

use Html;
use Linker;
use MediaWiki\MediaWikiServices;
use QuickTemplate;
use Sanitizer;
use Xml;

class HappySkinTemplate extends QuickTemplate {

	/**
	 * Output the entire HTML page
	 */
	public function execute() {
		$skin = $this->getSkin();
		$out = $skin->getOutput();
		$title = $out->getTitle();
		$isMainPage = $title->isMainPage();

		// Start HTML
		$this->html( 'headelement' );
		?>

		<div id="happyskin-wrapper" class="happyskin-wrapper">
			<header id="happyskin-header" class="happyskin-header">
				<div class="happyskin-header-inner">
					<button id="happyskin-menu-toggle" class="happyskin-menu-toggle" aria-label="<?php $this->msg( 'navigation' ) ?>">
						<span class="happyskin-menu-toggle-icon"></span>
					</button>
					
					<div class="happyskin-logo">
						<a href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>"
						   <?php echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) ?>>
							<?php $this->html( 'sitename' ) ?>
						</a>
					</div>

					<div class="happyskin-search">
						<?php $this->renderSearch(); ?>
					</div>

					<nav id="happyskin-personal" class="happyskin-personal">
						<?php $this->renderPersonalTools(); ?>
					</nav>
				</div>
			</header>

			<div id="happyskin-content-wrapper" class="happyskin-content-wrapper">
				<aside id="happyskin-sidebar" class="happyskin-sidebar">
					<?php $this->renderSidebar(); ?>
				</aside>

				<main id="happyskin-content" class="happyskin-content" role="main">
					<?php if ( !$isMainPage ): ?>
						<h1 id="firstHeading" class="firstHeading"><?php $this->html( 'title' ) ?></h1>
					<?php endif; ?>

					<div id="bodyContent" class="mw-body-content">
						<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
						<div id="contentSub"><?php $this->html( 'subtitle' ) ?></div>
						<?php if ( $this->data['undelete'] ) { ?>
							<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
						<?php } ?>
						<?php if ( $this->data['newtalk'] ) { ?>
							<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
						<?php } ?>
						<div id="jump-to-nav" class="mw-jump">
							<?php $this->msg( 'jumpto' ) ?>
							<a href="#column-one"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?>
							<a href="#searchInput"><?php $this->msg( 'jumptosearch' ) ?></a>
						</div>

						<?php $this->html( 'bodytext' ) ?>
						<?php if ( $this->data['catlinks'] ) { ?>
							<?php $this->html( 'catlinks' ); ?>
						<?php } ?>
						<?php $this->html( 'dataAfterContent' ) ?>
					</div>
				</main>

				<aside id="happyskin-toc" class="happyskin-toc">
					<?php $this->renderToc(); ?>
				</aside>
			</div>

			<footer id="happyskin-footer" class="happyskin-footer">
				<?php $this->renderFooter(); ?>
			</footer>
		</div>

		<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
	}

	/**
	 * Render the search box
	 */
	protected function renderSearch() {
		?>
		<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform" class="mw-search">
			<div>
				<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
				<?php
				echo $this->makeSearchInput( [ 'id' => 'searchInput', 'placeholder' => $this->getMsg( 'search' )->text() ] );
				echo $this->makeSearchButton( 'go', [ 'id' => 'searchGoButton', 'class' => 'searchButton' ] );
				?>
			</div>
		</form>
		<?php
	}

	/**
	 * Render personal tools
	 */
	protected function renderPersonalTools() {
		$personalTools = $this->getPersonalTools();
		if ( empty( $personalTools ) ) {
			return;
		}

		$echoKey = $this->getSkin()->getUser()->isRegistered() ? 'notifications-alert' : null;
		if ( $echoKey && isset( $personalTools[$echoKey] ) ) {
			// Move Echo notifications to front
			$echoItem = $personalTools[$echoKey];
			unset( $personalTools[$echoKey] );
			$personalTools = [ $echoKey => $echoItem ] + $personalTools;
		}

		foreach ( $personalTools as $key => $item ) {
			echo $this->makeListItem( $key, $item, [ 'tag' => 'span' ] );
		}
	}

	/**
	 * Render sidebar
	 */
	protected function renderSidebar() {
		$sidebar = $this->data['sidebar'];
		if ( !isset( $sidebar['SEARCH'] ) ) {
			$sidebar['SEARCH'] = true;
		}
		if ( !isset( $sidebar['TOOLBOX'] ) ) {
			$sidebar['TOOLBOX'] = true;
		}
		if ( !isset( $sidebar['LANGUAGES'] ) ) {
			$sidebar['LANGUAGES'] = true;
		}

		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}

			if ( $boxName == 'SEARCH' ) {
				continue;
			} elseif ( $boxName == 'TOOLBOX' ) {
				?>
				<section class="happyskin-sidebar-section">
					<h2><?php $this->msg( 'toolbox' ) ?></h2>
					<ul>
						<?php
						foreach ( $this->getToolbox() as $key => $tbItem ) {
							echo $this->makeListItem( $key, $tbItem );
						}
						?>
					</ul>
				</section>
				<?php
			} elseif ( $boxName == 'LANGUAGES' ) {
				if ( $this->data['language_urls'] ) {
					?>
					<section class="happyskin-sidebar-section">
						<h2><?php $this->msg( 'otherlanguages' ) ?></h2>
						<ul>
							<?php
							foreach ( $this->data['language_urls'] as $key => $langLink ) {
								echo $this->makeListItem( $key, $langLink );
							}
							?>
						</ul>
					</section>
					<?php
				}
			} else {
				?>
				<section class="happyskin-sidebar-section">
					<h2><?php $this->msg( $boxName ) ?></h2>
					<ul>
						<?php
						foreach ( $content as $key => $link ) {
							echo $this->makeListItem( $key, $link );
						}
						?>
					</ul>
				</section>
				<?php
			}
		}
	}

	/**
	 * Render table of contents
	 */
	protected function renderToc() {
		?>
		<div id="page-toc-wrapper" class="page-toc-wrapper">
			<nav id="p-toc" class="p-toc" role="navigation">
				<?php if ( $this->data['toc'] ) { ?>
					<div id="toc" class="toc">
						<?php $this->html( 'toc' ) ?>
					</div>
				<?php } ?>
			</nav>
		</div>
		<?php
	}

	/**
	 * Render footer
	 */
	protected function renderFooter() {
		$validFooterLinks = [];
		$validFooterIcons = [];
		$footerlinks = $this->get( 'footerlinks' );

		foreach ( $footerlinks as $category => $links ) {
			if ( $category == 'places' ) {
				$validFooterLinks[$category] = [];
				foreach ( $links as $link ) {
					if ( $this->data[$link] ) {
						$validFooterLinks[$category][$link] = $this->data[$link];
					}
				}
			} else {
				$validFooterIcons[$category] = [];
				foreach ( $links as $link ) {
					if ( $this->data[$link] ) {
						$validFooterIcons[$category][$link] = $this->data[$link];
					}
				}
			}
		}

		if ( count( $validFooterIcons ) || count( $validFooterLinks ) ) {
			?>
			<div class="happyskin-footer-inner">
				<?php
				if ( count( $validFooterIcons ) ) {
					?>
					<ul class="happyskin-footer-icons">
						<?php
						foreach ( $validFooterIcons as $blockName => $footerIcons ) {
							?>
							<li class="happyskin-footer-block">
								<ul id="<?php echo Sanitizer::escapeIdForAttribute( "footer-$blockName" ) ?>">
									<?php
									foreach ( $footerIcons as $icon ) {
										echo $this->getSkin()->makeFooterIcon( $icon );
									}
									?>
								</ul>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				}

				if ( count( $validFooterLinks ) ) {
					?>
					<ul class="happyskin-footer-links">
						<?php
						foreach ( $validFooterLinks as $category => $links ) {
							foreach ( $links as $key => $link ) {
								?>
								<li id="<?php echo Sanitizer::escapeIdForAttribute( "footer-$key" ) ?>">
									<?php $this->html( $key ) ?>
								</li>
								<?php
							}
						}
						?>
					</ul>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}
