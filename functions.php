<?php

/**
 * Radio F.R.E.I. functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Radio F.R.E.I.
 * @since Radio F.R.E.I. 1.0
 */

/**
 * Register block styles.
 */
function radiofrei_block_styles()
{

	register_block_style(
		'core/details',
		array(
			'name'         => 'arrow-icon-details',
			'label'        => __('Arrow icon', 'radiofrei'),
			/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
			'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
		)
	);
	register_block_style(
		'core/post-terms',
		array(
			'name'         => 'pill',
			'label'        => __('Pill', 'radiofrei'),
			/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
			'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
		)
	);
	register_block_style(
		'core/list',
		array(
			'name'         => 'checkmark-list',
			'label'        => __('Checkmark', 'radiofrei'),
			/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
			'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
		)
	);
	register_block_style(
		'core/navigation-link',
		array(
			'name'         => 'arrow-link',
			'label'        => __('With arrow', 'radiofrei'),
			/*
				 * Styles for the custom arrow nav link block style
				 */
			'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
		)
	);
	register_block_style(
		'core/heading',
		array(
			'name'         => 'asterisk',
			'label'        => __('With asterisk', 'radiofrei'),
			'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
		)
	);
}
add_action('init', 'radiofrei_block_styles');


/**
 * Enqueue custom block stylesheets
 *
 * @since Radio F.R.E.I. 1.0
 * @return void
 */
function radiofrei_block_stylesheets()
{
	/**
	 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
	 * for a specific block. These will only get loaded when the block is rendered
	 * (both in the editor and on the front end), improving performance
	 * and reducing the amount of data requested by visitors.
	 *
	 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
	 */
	wp_enqueue_block_style(
		'core/button',
		array(
			'handle' => 'radiofrei-button-style-outline',
			'src'    => get_parent_theme_file_uri('assets/css/button-outline.css'),
			'ver'    => wp_get_theme(get_template())->get('Version'),
			'path'   => get_parent_theme_file_path('assets/css/button-outline.css'),
		)
	);
}
add_action('init', 'radiofrei_block_stylesheets');


/**
 * Register pattern categories
 *
 * @since Radio F.R.E.I. 1.0
 * @return void
 */
function radiofrei_pattern_categories()
{

	register_block_pattern_category(
		'page',
		array(
			'label'       => _x('Pages', 'Block pattern category'),
			'description' => __('A collection of full page layouts.'),
		)
	);
}
add_action('init', 'radiofrei_pattern_categories');


/**
 * unterstützung style.css
 *
 * @return void
 */
function radiofrei_support()
{
	// Enqueue editor styles.
	add_editor_style('style.css');
}
add_action('after_setup_theme', 'radiofrei_support');


/**
 * Radio F.R.E.I. enqueue scripts and styles
 * scripte und styles auf allen seiten laden, da sonst wegen ajax auch body bereiche außer main getauscht werden müssten
 *
 * @return void
 */

function radiofrei_scripts_styles()
{
	// Register theme stylesheet.
	wp_register_style('radiofrei-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get('Version'));
	// Enqueue theme stylesheet.
	wp_enqueue_style('radiofrei-style');

	wp_enqueue_script('rf-audioplayer', get_theme_file_uri('assets/js/rf-audioplayer.js'), array(), '1.0', true);
	// put theme uri to audioplayer script
	wp_localize_script('rf-audioplayer', 'URLS', array('theme' => get_theme_file_uri()));

	wp_enqueue_script('topbar.min.js', get_theme_file_uri('/assets/js/topbar.min.js'), array(), '2.0.0', true);
	wp_enqueue_script('radiofrei-ajax', get_theme_file_uri('/assets/js/rf-ajax.js'), array(), '1.0', true);

	wp_enqueue_script('rf-filter-sendereihe', get_theme_file_uri('assets/js/rf-filter-sendereihe.js'), array(), '1.0', true);

	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.1/themes/base/jquery-ui.css');
	wp_enqueue_script('rf-datepicker', get_theme_file_uri('assets/js/rf-datepicker.js'), array('jquery', 'jquery-ui-datepicker'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'radiofrei_scripts_styles');


/*
 * Radio F.R.E.I. includes
 * Reihenfolge beachten!
 */

// taxonomy box für sendereihen
include('lib/taxonomy_single_term/class.taxonomy-single-term.php');

// include hilfsfunktionen
include('includes/rf-helpers.php');

// include backend funktionen
include('includes/rf-backend.php');

// include funktionen für das ersetzen von audio tags durch audio buttons
include('includes/rf-audio-buttons.php');
