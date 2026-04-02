<?php

namespace MediaWiki\Skin\HappySkin;

use Html;
use MediaWiki\MediaWikiServices;
use OutputPage;
use SkinTemplate;
use Title;

class HappySkin extends SkinTemplate {
	public $skinname = 'HappySkin';
	public $stylename = 'HappySkin';
	public $template = '\MediaWiki\Skin\HappySkin\HappySkinTemplate';
	public $useHeadElement = true;

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );
		$out->addModuleStyles( 'skins.happyskin' );
		$out->addModules( 'skins.happyskin.js' );

		// Add theme color
		$themeColor = MediaWikiServices::getInstance()->getMainConfig()->get( 'HappySkinThemeColor' );
		$out->addJsConfigVars( 'wgHappySkinThemeColor', $themeColor );
	}

	/**
	 * @return array
	 */
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		$modules['styles']['skin'][] = 'skins.happyskin';
		return $modules;
	}
}
