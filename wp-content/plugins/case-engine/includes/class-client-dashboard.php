<?php
/**
 * Client Dashboard — shortcode and "own cases" listing (RBAC: case_engine_view_own_cases).
 *
 * @package Case_Engine
 */

defined( 'ABSPATH' ) || exit;

class Case_Engine_Client_Dashboard {

	/**
	 * Register shortcode [az_client_dashboard].
	 */
	public static function register() {
		add_shortcode( 'az_client_dashboard', array( __CLASS__, 'render' ) );
	}

	/**
	 * Shortcode callback: enforce VIEW_OWN_CASES, then show list or case detail.
	 *
	 * @return string
	 */
	public static function render() {
		if ( ! is_user_logged_in() ) {
			return self::render_login_required();
		}
		if ( ! current_user_can( Case_Engine_RBAC::VIEW_OWN_CASES ) ) {
			return self::render_no_permission();
		}

		$view_case = isset( $_GET['view_case'] ) ? (int) $_GET['view_case'] : 0;
		if ( $view_case > 0 ) {
			$user_id = get_current_user_id();
			$case    = self::get_case_for_user( $view_case, $user_id );
			if ( $case ) {
				$parties = self::get_case_parties( $view_case );
				return self::render_case_detail( $case, $parties );
			}
		}

		return self::render_own_cases();
	}

	/**
	 * Message when user is not logged in.
	 *
	 * @return string
	 */
	private static function render_login_required() {
		$login_url = wp_login_url( get_permalink() );
		$output = '<div class="az-client-dashboard az-client-dashboard--guest">';
		$output .= '<div class="az-client-dashboard__card">';
		$output .= '<p>' . esc_html__( 'Please log in to view your cases and documents.', 'case-engine' ) . '</p>';
		$output .= '<p><a href="' . esc_url( $login_url ) . '" class="az-intake-btn az-intake-btn-primary">' . esc_html__( 'Log in', 'case-engine' ) . '</a></p>';
		$output .= '</div></div>';
		return $output;
	}

	/**
	 * Message when user lacks VIEW_OWN_CASES.
	 *
	 * @return string
	 */
	private static function render_no_permission() {
		return '<div class="az-client-dashboard az-client-dashboard--forbidden"><div class="az-client-dashboard__card"><p>' .
			esc_html__( 'You do not have permission to view this page.', 'case-engine' ) . '</p></div></div>';
	}

	/**
	 * List cases owned by the current user with "View details" link.
	 *
	 * @return string
	 */
	private static function render_own_cases() {
		$user_id = get_current_user_id();
		$cases   = self::get_own_cases( $user_id );
		$base_url = get_permalink();

		$output = '<div class="az-client-dashboard">';
		$output .= '<div class="az-client-dashboard__card">';
		$output .= '<span class="az-client-dashboard__subtitle">' . esc_html__( 'Dashboard', 'case-engine' ) . '</span>';
		$output .= '<h2>' . esc_html__( 'Your cases', 'case-engine' ) . '</h2>';

		if ( empty( $cases ) ) {
			$output .= '<p>' . esc_html__( 'You have no cases yet. Complete the intake to create a case.', 'case-engine' ) . '</p>';
			$output .= '</div></div>';
			return $output;
		}

		$output .= '<table class="az-client-dashboard__table">';
		$output .= '<thead><tr>';
		$output .= '<th>' . esc_html__( 'Case', 'case-engine' ) . '</th>';
		$output .= '<th>' . esc_html__( 'Status', 'case-engine' ) . '</th>';
		$output .= '<th>' . esc_html__( 'County', 'case-engine' ) . '</th>';
		$output .= '<th>' . esc_html__( 'Created', 'case-engine' ) . '</th>';
		$output .= '<th>' . esc_html__( 'Actions', 'case-engine' ) . '</th>';
		$output .= '</tr></thead><tbody>';

		foreach ( $cases as $row ) {
			$view_url = add_query_arg( 'view_case', (int) $row['id'], $base_url );
			$output .= '<tr>';
			$output .= '<td><span class="az-client-dashboard__case-num">' . (int) $row['id'] . '</span></td>';
			$output .= '<td>' . esc_html( $row['status'] ) . '</td>';
			$output .= '<td>' . esc_html( $row['county'] ) . '</td>';
			$output .= '<td>' . esc_html( $row['created_at'] ) . '</td>';
			$output .= '<td><a href="' . esc_url( $view_url ) . '" class="az-intake-btn az-intake-btn-link">' . esc_html__( 'View details', 'case-engine' ) . '</a></td>';
			$output .= '</tr>';
		}

		$output .= '</tbody></table>';
		$output .= '<p class="az-client-dashboard__footer-note">' . esc_html__( 'Document downloads and payment history will appear here in a later release.', 'case-engine' ) . '</p>';
		$output .= '</div></div>';
		return $output;
	}

	/**
	 * Render case detail: case info + parties.
	 *
	 * @param array $case   Case row (id, status, county, filing_date, role, created_at, has_children).
	 * @param array $parties Party rows from get_case_parties().
	 * @return string
	 */
	private static function render_case_detail( $case, $parties ) {
		$back_url = remove_query_arg( 'view_case', get_permalink() );
		$case_id  = (int) $case['id'];

		$output = '<div class="az-client-dashboard">';
		$output .= '<p><a href="' . esc_url( $back_url ) . '" class="az-intake-btn az-intake-btn-secondary">← ' . esc_html__( 'Back to your cases', 'case-engine' ) . '</a></p>';

		// Case info card
		$output .= '<div class="az-client-dashboard__card">';
		$output .= '<span class="az-client-dashboard__subtitle">' . esc_html__( 'Case', 'case-engine' ) . ' #' . $case_id . '</span>';
		$output .= '<h2 class="az-client-dashboard__title">' . esc_html__( 'Case details', 'case-engine' ) . '</h2>';
		$output .= '<div class="az-client-dashboard__info-grid">';
		$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'Status', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['status'] ) . '</span></div>';
		$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'County', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['county'] ) . '</span></div>';
		$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'Your role', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['role'] ) . '</span></div>';
		$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'Children', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['has_children'] ) . '</span></div>';
		if ( ! empty( $case['filing_date'] ) ) {
			$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'Filing date', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['filing_date'] ) . '</span></div>';
		}
		$output .= '<div class="az-client-dashboard__info-item"><span class="az-client-dashboard__info-label">' . esc_html__( 'Created', 'case-engine' ) . '</span><span class="az-client-dashboard__info-value">' . esc_html( $case['created_at'] ) . '</span></div>';
		$output .= '</div></div>';

		// Parties card
		$output .= '<div class="az-client-dashboard__card">';
		$output .= '<h2 class="az-client-dashboard__title">' . esc_html__( 'Parties', 'case-engine' ) . '</h2>';

		if ( empty( $parties ) ) {
			$output .= '<p>' . esc_html__( 'No party information on file.', 'case-engine' ) . '</p>';
		} else {
			$type_labels = array(
				'petitioner' => __( 'Petitioner', 'case-engine' ),
				'respondent' => __( 'Respondent', 'case-engine' ),
				'child'      => __( 'Child', 'case-engine' ),
			);
			foreach ( $parties as $p ) {
				$type = isset( $p['party_type'] ) ? $p['party_type'] : '';
				$type_label = isset( $type_labels[ $type ] ) ? $type_labels[ $type ] : ucfirst( $type );
				$output .= '<div class="az-client-dashboard__party-block">';
				$output .= '<div class="az-client-dashboard__party-type">' . esc_html( $type_label ) . '</div>';
				$output .= '<div class="az-client-dashboard__party-name">' . esc_html( $p['full_name'] ) . '</div>';
				if ( ! empty( $p['address'] ) ) {
					$output .= '<div class="az-client-dashboard__party-detail">' . esc_html__( 'Address', 'case-engine' ) . ': ' . esc_html( $p['address'] ) . '</div>';
				}
				if ( ! empty( $p['phone'] ) ) {
					$output .= '<div class="az-client-dashboard__party-detail">' . esc_html__( 'Phone', 'case-engine' ) . ': ' . esc_html( $p['phone'] ) . '</div>';
				}
				if ( ! empty( $p['email'] ) ) {
					$output .= '<div class="az-client-dashboard__party-detail">' . esc_html__( 'Email', 'case-engine' ) . ': ' . esc_html( $p['email'] ) . '</div>';
				}
				if ( ! empty( $p['dob'] ) ) {
					$output .= '<div class="az-client-dashboard__party-detail">' . esc_html__( 'Date of birth', 'case-engine' ) . ': ' . esc_html( $p['dob'] ) . '</div>';
				}
				if ( ! empty( $p['relationship'] ) ) {
					$output .= '<div class="az-client-dashboard__party-detail">' . esc_html__( 'Relationship', 'case-engine' ) . ': ' . esc_html( $p['relationship'] ) . '</div>';
				}
				$output .= '</div>';
			}
		}
		$output .= '</div></div>';
		return $output;
	}

	/**
	 * Get a case by ID only if it belongs to the given user.
	 *
	 * @param int $case_id Case ID.
	 * @param int $user_id WordPress user ID.
	 * @return array|null Case row or null if not found / not owned.
	 */
	public static function get_case_for_user( $case_id, $user_id ) {
		global $wpdb;
		$p = $wpdb->prefix;
		$sessions_table = $p . 'az_intake_sessions';
		$cases_table    = $p . 'az_cases';

		$case_id = (int) $case_id;
		$user_id = (int) $user_id;
		if ( ! $case_id || ! $user_id ) {
			return null;
		}

		$row = $wpdb->get_row( $wpdb->prepare(
			"SELECT c.id, c.status, c.county, c.filing_date, c.role, c.has_children, c.created_at
			 FROM {$cases_table} c
			 LEFT JOIN {$sessions_table} s ON c.intake_session_id = s.id
			 WHERE c.id = %d AND (c.user_id = %d OR s.user_id = %d)",
			$case_id,
			$user_id,
			$user_id
		), ARRAY_A );
		return $row ?: null;
	}

	/**
	 * Get parties for a case (petitioner, respondent, children).
	 *
	 * @param int $case_id Case ID.
	 * @return array List of party rows.
	 */
	public static function get_case_parties( $case_id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'az_parties';
		$case_id = (int) $case_id;
		if ( ! $case_id ) {
			return array();
		}
		return $wpdb->get_results( $wpdb->prepare(
			"SELECT party_type, full_name, address, phone, email, dob, relationship
			 FROM {$table}
			 WHERE case_id = %d
			 ORDER BY sort_order ASC, id ASC",
			$case_id
		), ARRAY_A );
	}

	/**
	 * Get cases whose intake session belongs to the given user.
	 *
	 * @param int $user_id WordPress user ID.
	 * @return array List of case rows (id, status, county, created_at).
	 */
	public static function get_own_cases( $user_id ) {
		global $wpdb;
		$p = $wpdb->prefix;
		$sessions_table = $p . 'az_intake_sessions';
		$cases_table    = $p . 'az_cases';

		$user_id = (int) $user_id;
		if ( ! $user_id ) {
			return array();
		}

		return $wpdb->get_results( $wpdb->prepare(
			"SELECT c.id, c.status, c.county, c.created_at
			 FROM {$cases_table} c
			 LEFT JOIN {$sessions_table} s ON c.intake_session_id = s.id
			 WHERE c.user_id = %d OR s.user_id = %d
			 ORDER BY c.created_at DESC",
			$user_id,
			$user_id
		), ARRAY_A );
	}
}
