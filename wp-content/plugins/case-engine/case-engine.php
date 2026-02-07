<?php
/**
 * Plugin Name: Case Engine (AZ Divorce)
 * Description: Intake flow, case lifecycle, and document automation for uncontested Arizona divorces.
 * Version: 1.0.0
 * Author: Legal Divorce Docs
 * Text Domain: case-engine
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'CASE_ENGINE_VERSION', '1.0.0' );
define( 'CASE_ENGINE_DB_VERSION', 2 ); // Bump when adding new tables.
define( 'CASE_ENGINE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CASE_ENGINE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Create all Case Engine tables on activation.
 */
function case_engine_activate() {
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$p   = $wpdb->prefix;
	$ch  = $wpdb->get_charset_collate();

	$tables = array(
		'az_intake_sessions' => "CREATE TABLE {$p}az_intake_sessions (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			session_key varchar(64) NOT NULL,
			user_id bigint(20) unsigned DEFAULT 0,
			status varchar(20) NOT NULL DEFAULT 'in_progress',
			current_screen int(11) NOT NULL DEFAULT 1,
			answers longtext,
			case_id bigint(20) unsigned DEFAULT 0,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY session_key (session_key),
			KEY status (status),
			KEY user_id (user_id),
			KEY case_id (case_id)
		) $ch;",
		'az_cases' => "CREATE TABLE {$p}az_cases (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			intake_session_id bigint(20) unsigned DEFAULT 0,
			county varchar(100) DEFAULT '',
			has_children varchar(10) DEFAULT 'no',
			filing_date date DEFAULT NULL,
			role varchar(50) DEFAULT 'petitioner',
			status varchar(30) NOT NULL DEFAULT 'paid',
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY intake_session_id (intake_session_id),
			KEY status (status)
		) $ch;",
		'az_parties' => "CREATE TABLE {$p}az_parties (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			case_id bigint(20) unsigned NOT NULL,
			party_type varchar(20) NOT NULL DEFAULT 'petitioner',
			full_name varchar(255) DEFAULT '',
			address text,
			phone varchar(50) DEFAULT '',
			email varchar(255) DEFAULT '',
			dob date DEFAULT NULL,
			relationship varchar(100) DEFAULT '',
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY case_id (case_id)
		) $ch;",
		'az_intake_answers' => "CREATE TABLE {$p}az_intake_answers (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			case_id bigint(20) unsigned NOT NULL,
			session_id bigint(20) unsigned NOT NULL DEFAULT 0,
			question_key varchar(100) NOT NULL,
			answer_value text,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY case_id (case_id),
			KEY session_id (session_id),
			KEY question_key (question_key)
		) $ch;",
		'az_document_states' => "CREATE TABLE {$p}az_document_states (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			case_id bigint(20) unsigned NOT NULL,
			document_type varchar(80) NOT NULL DEFAULT '',
			file_path varchar(500) DEFAULT '',
			file_hash varchar(64) DEFAULT '',
			version int(11) NOT NULL DEFAULT 1,
			status varchar(30) DEFAULT 'pending',
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY case_id (case_id),
			KEY document_type (document_type)
		) $ch;",
		'az_payments' => "CREATE TABLE {$p}az_payments (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			case_id bigint(20) unsigned NOT NULL,
			amount decimal(10,2) NOT NULL DEFAULT 0.00,
			currency varchar(3) DEFAULT 'USD',
			status varchar(30) NOT NULL DEFAULT 'completed',
			stripe_payment_id varchar(255) DEFAULT '',
			paid_at datetime DEFAULT NULL,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY case_id (case_id),
			KEY status (status)
		) $ch;",
		'az_audit_logs' => "CREATE TABLE {$p}az_audit_logs (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			action varchar(80) NOT NULL,
			entity_type varchar(30) DEFAULT '',
			entity_id bigint(20) unsigned DEFAULT 0,
			user_id bigint(20) unsigned DEFAULT 0,
			details text,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY action (action),
			KEY entity_type (entity_type),
			KEY entity_id (entity_id)
		) $ch;",
	);

	foreach ( $tables as $name => $sql ) {
		dbDelta( $sql );
	}

	case_engine_ensure_client_dashboard_page();
	update_option( 'case_engine_db_version', CASE_ENGINE_DB_VERSION );

	// RBAC: roles and capabilities.
	require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-case-engine-rbac.php';
	Case_Engine_RBAC::install_roles_caps();
}
register_activation_hook( __FILE__, 'case_engine_activate' );

/**
 * Ensure DB tables exist (e.g. after update without reactivation).
 */
function case_engine_maybe_upgrade_db() {
	if ( get_option( 'case_engine_db_version', 0 ) >= CASE_ENGINE_DB_VERSION ) {
		return;
	}
	case_engine_activate();
}
add_action( 'init', 'case_engine_maybe_upgrade_db', 5 );

/**
 * Create Client Dashboard page if it doesn't exist (so post-intake redirect doesn't 404).
 */
function case_engine_ensure_client_dashboard_page() {
	$slug = 'client-dashboard';
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( ! $page ) {
		wp_insert_post( array(
			'post_title'   => __( 'Client Dashboard', 'case-engine' ),
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '<p>' . __( 'View your cases and, in a later release, documents and payment history.', 'case-engine' ) . '</p>' . "\n\n" . '[az_client_dashboard]',
		) );
		return;
	}
	// Existing page created before shortcode: add shortcode so dashboard shows login/cases list.
	if ( strpos( $page->post_content, '[az_client_dashboard]' ) === false ) {
		$new_content = '<p>' . __( 'View your cases and, in a later release, documents and payment history.', 'case-engine' ) . '</p>' . "\n\n" . '[az_client_dashboard]';
		wp_update_post( array(
			'ID'           => $page->ID,
			'post_content' => $new_content,
		) );
	}
}

/**
 * Load RBAC, intake flow, and case factory.
 */
require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-case-engine-rbac.php';
require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-intake-flow.php';
require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-intake-handler.php';
require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-case-factory.php';
require_once CASE_ENGINE_PLUGIN_DIR . 'includes/class-client-dashboard.php';

/**
 * Init plugin.
 */
function case_engine_init() {
	Case_Engine_Intake_Flow::register();
	Case_Engine_Intake_Handler::register();
	Case_Engine_Client_Dashboard::register();
	// Ensure Client Dashboard page exists (fixes 404 after intake completion).
	if ( get_option( 'case_engine_dashboard_page_checked' ) !== CASE_ENGINE_VERSION ) {
		case_engine_ensure_client_dashboard_page();
		update_option( 'case_engine_dashboard_page_checked', CASE_ENGINE_VERSION );
	}
	// One-time: add [az_client_dashboard] to existing Client Dashboard page if missing.
	if ( get_option( 'case_engine_dashboard_shortcode_added' ) !== CASE_ENGINE_VERSION ) {
		$page = get_page_by_path( 'client-dashboard', OBJECT, 'page' );
		if ( $page && strpos( $page->post_content, '[az_client_dashboard]' ) === false ) {
			$new_content = '<p>' . __( 'View your cases and, in a later release, documents and payment history.', 'case-engine' ) . '</p>' . "\n\n" . '[az_client_dashboard]';
			wp_update_post( array( 'ID' => $page->ID, 'post_content' => $new_content ) );
		}
		update_option( 'case_engine_dashboard_shortcode_added', CASE_ENGINE_VERSION );
	}
	// Ensure roles/caps exist (e.g. after update without reactivation).
	if ( get_option( 'case_engine_rbac_version' ) !== CASE_ENGINE_VERSION ) {
		Case_Engine_RBAC::install_roles_caps();
		update_option( 'case_engine_rbac_version', CASE_ENGINE_VERSION );
	}
}
add_action( 'init', 'case_engine_init' );

/**
 * Enqueue Client Dashboard styles on the dashboard page.
 */
function case_engine_enqueue_dashboard_assets() {
	if ( ! is_page( 'client-dashboard' ) ) {
		return;
	}
	wp_enqueue_style(
		'case-engine-dashboard',
		CASE_ENGINE_PLUGIN_URL . 'assets/dashboard.css',
		array(),
		CASE_ENGINE_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'case_engine_enqueue_dashboard_assets' );

/**
 * Admin menu (visible to users who can view sessions).
 */
function case_engine_admin_menu() {
	add_menu_page(
		__( 'Case Engine', 'case-engine' ),
		__( 'Case Engine', 'case-engine' ),
		Case_Engine_RBAC::VIEW_SESSIONS,
		'case-engine',
		'case_engine_admin_page',
		'dashicons-portfolio',
		30
	);
}
add_action( 'admin_menu', 'case_engine_admin_menu' );

function case_engine_admin_page() {
	if ( ! current_user_can( Case_Engine_RBAC::VIEW_SESSIONS ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'case-engine' ), 403 );
	}

	// Allow creating a case from an existing session (e.g. session that reached screen 12 but case_id stayed 0)
	$create_from = isset( $_GET['create_case_from_session'] ) ? (int) $_GET['create_case_from_session'] : 0;
	if ( $create_from && current_user_can( Case_Engine_RBAC::CREATE_CASE_FROM_SESSION ) ) {
		$case_id = Case_Engine_Case_Factory::create_from_session( $create_from );
		if ( $case_id ) {
			wp_safe_redirect( add_query_arg( 'case_created', $case_id, remove_query_arg( 'create_case_from_session' ) ) );
			exit;
		}
	}

	echo '<div class="wrap"><h1>' . esc_html__( 'Case Engine', 'case-engine' ) . '</h1>';
	if ( isset( $_GET['case_created'] ) ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Case created successfully.', 'case-engine' ) . ' ID: ' . (int) $_GET['case_created'] . '</p></div>';
	}
	echo '<p>' . esc_html__( 'Cases and intake sessions will be listed here.', 'case-engine' ) . '</p>';

	global $wpdb;
	$sessions_table = $wpdb->prefix . 'az_intake_sessions';
	$sessions = $wpdb->get_results( "SELECT id, session_key, status, case_id, current_screen, created_at FROM $sessions_table ORDER BY id DESC LIMIT 20", ARRAY_A );
	if ( $sessions ) {
		echo '<h2>' . esc_html__( 'Recent intake sessions', 'case-engine' ) . '</h2>';
		echo '<table class="widefat striped"><thead><tr><th>ID</th><th>Status</th><th>Screen</th><th>Case ID</th><th>Created</th><th>Action</th></tr></thead><tbody>';
		foreach ( $sessions as $s ) {
			$action = '—';
			if ( empty( $s['case_id'] ) && ! empty( $s['id'] ) && current_user_can( Case_Engine_RBAC::CREATE_CASE_FROM_SESSION ) ) {
				$action = '<a href="' . esc_url( add_query_arg( 'create_case_from_session', $s['id'] ) ) . '">' . esc_html__( 'Create case', 'case-engine' ) . '</a>';
			}
			echo '<tr><td>' . (int) $s['id'] . '</td><td>' . esc_html( $s['status'] ) . '</td><td>' . (int) $s['current_screen'] . '</td><td>' . ( (int) $s['case_id'] ? $s['case_id'] : '—' ) . '</td><td>' . esc_html( $s['created_at'] ) . '</td><td>' . $action . '</td></tr>';
		}
		echo '</tbody></table>';
	}
	echo '</div>';
}
