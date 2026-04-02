( function () {
	'use strict';

	const STORAGE_KEY = 'happyskin-dark-mode';
	const THEME_COLOR_KEY = 'wgHappySkinThemeColor';

	function initThemeColor() {
		const themeColor = mw.config.get( THEME_COLOR_KEY );
		if ( themeColor ) {
			document.documentElement.style.setProperty( '--happyskin-theme-color', 'oklch(' + themeColor + ')' );
		}
	}

	function initDarkMode() {
		const stored = localStorage.getItem( STORAGE_KEY );
		let isDark;

		if ( stored !== null ) {
			isDark = stored === 'true';
		} else {
			isDark = window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches;
		}

		if ( isDark ) {
			document.documentElement.classList.add( 'happyskin-dark-mode' );
		}
	}

	function toggleDarkMode() {
		const isDark = document.documentElement.classList.toggle( 'happyskin-dark-mode' );
		localStorage.setItem( STORAGE_KEY, isDark.toString() );
	}

	function initMobileMenu() {
		const menuToggle = document.getElementById( 'happyskin-menu-toggle' );
		const sidebar = document.getElementById( 'happyskin-sidebar' );

		if ( !menuToggle || !sidebar ) {
			return;
		}

		menuToggle.addEventListener( 'click', function () {
			sidebar.classList.toggle( 'happyskin-sidebar-open' );
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( !sidebar.contains( e.target ) && !menuToggle.contains( e.target ) ) {
				sidebar.classList.remove( 'happyskin-sidebar-open' );
			}
		} );
	}

	function addDarkModeToggle() {
		const personalNav = document.getElementById( 'happyskin-personal' );
		if ( !personalNav ) {
			return;
		}

		const toggleBtn = document.createElement( 'button' );
		toggleBtn.className = 'happyskin-dark-mode-toggle';
		toggleBtn.setAttribute( 'aria-label', mw.msg( 'happyskin-toggle-dark-mode' ) );
		toggleBtn.title = mw.msg( 'happyskin-toggle-dark-mode' );
		toggleBtn.innerHTML = '🌓';

		toggleBtn.addEventListener( 'click', toggleDarkMode );

		personalNav.insertBefore( toggleBtn, personalNav.firstChild );
	}

	mw.hook( 'wikipage.content' ).add( function () {
		initThemeColor();
		initDarkMode();
		initMobileMenu();
		addDarkModeToggle();
	} );

	if ( document.readyState === 'complete' || document.readyState === 'interactive' ) {
		initThemeColor();
		initDarkMode();
		initMobileMenu();
		addDarkModeToggle();
	} else {
		document.addEventListener( 'DOMContentLoaded', function () {
			initThemeColor();
			initDarkMode();
			initMobileMenu();
			addDarkModeToggle();
		} );
	}

}() );
