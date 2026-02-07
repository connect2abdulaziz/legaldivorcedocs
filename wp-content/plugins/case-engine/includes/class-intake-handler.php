<?php
/**
 * Intake AJAX: save progress, evaluate gates, create case shell.
 *
 * @package Case_Engine
 */

defined( 'ABSPATH' ) || exit;

class Case_Engine_Intake_Handler {

	/**
	 * Register AJAX actions.
	 */
	public static function register() {
		add_action( 'wp_ajax_az_intake_save', array( __CLASS__, 'ajax_save' ) );
		add_action( 'wp_ajax_nopriv_az_intake_save', array( __CLASS__, 'ajax_save' ) );
		add_action( 'wp_ajax_az_intake_gate', array( __CLASS__, 'ajax_gate' ) );
		add_action( 'wp_ajax_nopriv_az_intake_gate', array( __CLASS__, 'ajax_gate' ) );
		add_action( 'wp_ajax_az_intake_payment', array( __CLASS__, 'ajax_payment' ) );
		add_action( 'wp_ajax_nopriv_az_intake_payment', array( __CLASS__, 'ajax_payment' ) );
	}

	/**
	 * Get or create session key (cookie or transient).
	 */
	private static function get_session_key() {
		if ( isset( $_COOKIE['az_intake_session'] ) && preg_match( '/^[a-z0-9]{32}$/', $_COOKIE['az_intake_session'] ) ) {
			return sanitize_text_field( $_COOKIE['az_intake_session'] );
		}
		return wp_generate_password( 32, false );
	}

	/**
	 * Get intake session row.
	 */
	private static function get_session( $session_key ) {
		global $wpdb;
		$table = $wpdb->prefix . 'az_intake_sessions';
		return $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $table WHERE session_key = %s",
			$session_key
		), ARRAY_A );
	}

	/**
	 * Save or update session.
	 */
	private static function save_session( $session_key, $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'az_intake_sessions';
		$existing = self::get_session( $session_key );
		$answers = isset( $data['answers'] ) ? $data['answers'] : array();
		$answers_json = wp_json_encode( $answers );
		$user_id = get_current_user_id();
		$status = isset( $data['status'] ) ? $data['status'] : 'in_progress';
		$current_screen = isset( $data['current_screen'] ) ? (int) $data['current_screen'] : 1;

		if ( $existing ) {
			$wpdb->update(
				$table,
				array(
					'user_id'        => $user_id,
					'status'         => $status,
					'current_screen' => $current_screen,
					'answers'        => $answers_json,
					'updated_at'     => current_time( 'mysql' ),
				),
				array( 'session_key' => $session_key ),
				array( '%d', '%s', '%d', '%s', '%s' ),
				array( '%s' )
			);
		} else {
			$wpdb->insert(
				$table,
				array(
					'session_key'    => $session_key,
					'user_id'        => $user_id,
					'status'         => $status,
					'current_screen' => $current_screen,
					'answers'        => $answers_json,
				),
				array( '%s', '%d', '%s', '%d', '%s' )
			);
		}
	}

	/**
	 * Recursively sanitize answers so nested arrays (e.g. children) are preserved.
	 */
	private static function sanitize_answers_deep( $arr ) {
		$out = array();
		foreach ( $arr as $k => $v ) {
			$key = is_numeric( $k ) ? $k : sanitize_key( $k );
			if ( is_array( $v ) ) {
				$out[ $key ] = self::sanitize_answers_deep( $v );
			} else {
				$out[ $key ] = sanitize_text_field( (string) $v );
			}
		}
		return $out;
	}

	/**
	 * AJAX: save current screen answers and optionally advance.
	 */
	public static function ajax_save() {
		check_ajax_referer( 'az_intake', 'nonce' );
		$session_key = isset( $_POST['session_key'] ) ? sanitize_text_field( wp_unslash( $_POST['session_key'] ) ) : '';
		// Empty or invalid key = new session (e.g. user reloaded intake page). Generate so we always create a new row.
		if ( ! $session_key || ! preg_match( '/^[a-zA-Z0-9]{32}$/', $session_key ) ) {
			$session_key = wp_generate_password( 32, false );
		}
		$current = isset( $_POST['current_screen'] ) ? (int) $_POST['current_screen'] : 1;
		$answers_raw = isset( $_POST['answers'] ) && is_array( $_POST['answers'] ) ? wp_unslash( $_POST['answers'] ) : array();
		$answers = self::sanitize_answers_deep( $answers_raw );

		$existing = self::get_session( $session_key );
		$existing_answers = array();
		if ( $existing && ! empty( $existing['answers'] ) ) {
			$existing_answers = json_decode( $existing['answers'], true );
			if ( ! is_array( $existing_answers ) ) {
				$existing_answers = array();
			}
		}
		$merged = array_merge( $existing_answers, $answers );

		// Screen 5: don't use stale children_agreement from a previous attempt when the current form didn't send it (e.g. no children or user didn't select).
		if ( $current === 5 && ! array_key_exists( 'children_agreement', $answers ) ) {
			unset( $merged['children_agreement'] );
		}

		// If gate fails, mark session as stopped and do not advance.
		$gate_result = Case_Engine_Intake_Flow::evaluate_gate( $current, $merged );
		if ( ! empty( $gate_result['can_proceed'] ) === false ) {
			self::save_session( $session_key, array(
				'answers'        => $merged,
				'status'         => 'stopped',
				'current_screen' => $current,
			) );
			wp_send_json_success( array(
				'can_proceed'  => false,
				'stop_message' => $gate_result['stop_message'] ?? __( 'This service only supports uncontested divorces.', 'case-engine' ),
				'session_key'  => $session_key,
			) );
		}

		$next = $current + 1;
		self::save_session( $session_key, array(
			'answers'        => $merged,
			'status'         => 'in_progress',
			'current_screen' => $next,
		) );

		// When user completes Screen 10 (Review & Confirmation), create case in structured tables immediately.
		// Data is stored in cases, parties, intake_answers; case status = pending_payment. Payment step then marks it paid.
		if ( $current === 10 ) {
			Case_Engine_Case_Factory::create_from_session( $session_key, array( 'case_status' => 'pending_payment' ) );
		}

		wp_send_json_success( array(
			'can_proceed' => true,
			'next_screen' => $next,
			'session_key' => $session_key,
		) );
	}

	/**
	 * AJAX: evaluate gate for current screen (client can call before attempting next).
	 */
	public static function ajax_gate() {
		check_ajax_referer( 'az_intake', 'nonce' );
		$current = isset( $_POST['current_screen'] ) ? (int) $_POST['current_screen'] : 1;
		$answers_raw = isset( $_POST['answers'] ) && is_array( $_POST['answers'] ) ? wp_unslash( $_POST['answers'] ) : array();
		$answers = array();
		foreach ( $answers_raw as $k => $v ) {
			$answers[ sanitize_key( $k ) ] = is_scalar( $v ) ? sanitize_text_field( $v ) : array_map( 'sanitize_text_field', (array) $v );
		}
		$result = Case_Engine_Intake_Flow::evaluate_gate( $current, $answers );
		wp_send_json_success( $result );
	}

	/**
	 * AJAX: payment complete. If case already exists (created on Screen 10), mark it paid and add payment. Else create case and mark paid.
	 */
	public static function ajax_payment() {
		check_ajax_referer( 'az_intake', 'nonce' );
		$session_key = isset( $_POST['session_key'] ) ? sanitize_text_field( $_POST['session_key'] ) : '';
		if ( ! $session_key ) {
			wp_send_json_error( array( 'message' => __( 'Invalid session.', 'case-engine' ) ) );
		}

		$session = self::get_session( $session_key );
		if ( ! $session ) {
			wp_send_json_error( array( 'message' => __( 'Session not found.', 'case-engine' ) ) );
		}

		$case_id = ! empty( $session['case_id'] ) ? (int) $session['case_id'] : 0;
		if ( $case_id ) {
			// Case was already created when they completed Screen 10; just mark paid and update session.
			Case_Engine_Case_Factory::mark_case_paid( $case_id, (int) $session['id'] );
		} else {
			// Legacy or missed step: create case and payment in one go.
			$case_id = Case_Engine_Case_Factory::create_from_session( $session_key );
			if ( ! $case_id ) {
				wp_send_json_error( array( 'message' => __( 'Could not create case from intake. Please try again.', 'case-engine' ) ) );
			}
		}

		wp_send_json_success( array(
			'redirect' => home_url( '/client-dashboard/' ),
			'case_id'  => $case_id,
		) );
	}
}
