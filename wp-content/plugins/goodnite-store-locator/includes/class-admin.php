<?php
/**
 * Admin Panel & CSV Importer for Goodnite Store Locator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Goodnite_SL_Admin {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_admin_actions' ) );
	}

	public static function add_admin_menu() {
		add_menu_page(
			__( 'Goodnite Store Locator', 'goodnite-store-locator' ),
			__( 'Store Locator', 'goodnite-store-locator' ),
			'manage_options',
			'goodnite-store-locator',
			array( __CLASS__, 'render_admin_page' ),
			'dashicons-location-alt',
			26
		);
	}

	public static function enqueue_admin_assets( $hook ) {
		if ( 'toplevel_page_goodnite-store-locator' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'goodnite-sl-admin-css',
			GOODNITE_SL_URL . 'assets/css/admin.css',
			array(),
			GOODNITE_SL_VERSION
		);

		wp_enqueue_script(
			'goodnite-sl-admin-js',
			GOODNITE_SL_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			GOODNITE_SL_VERSION,
			true
		);
	}

	/**
	 * Handle CSV Upload, Download Sample, and Deletion
	 */
	public static function handle_admin_actions() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Handle Sample CSV Download
		if ( isset( $_GET['action'] ) && 'goodnite_download_sample_csv' === $_GET['action'] ) {
			check_admin_referer( 'goodnite_download_sample_csv_nonce' );
			self::stream_sample_csv();
			exit;
		}

		// Handle CSV Upload Form Submission
		if ( isset( $_POST['goodnite_sl_import_submit'] ) ) {
			check_admin_referer( 'goodnite_sl_import_nonce' );

			if ( empty( $_FILES['dealers_csv']['tmp_name'] ) ) {
				add_settings_error( 'goodnite_sl_notices', 'no_file', __( 'Please choose a valid CSV file to upload.', 'goodnite-store-locator' ), 'error' );
				return;
			}

			$file_name = sanitize_file_name( $_FILES['dealers_csv']['name'] );
			$ext       = strtolower( pathinfo( $file_name, PATHINFO_EXTENSION ) );

			if ( 'csv' !== $ext ) {
				add_settings_error( 'goodnite_sl_notices', 'invalid_ext', __( 'Only .csv files are supported.', 'goodnite-store-locator' ), 'error' );
				return;
			}

			$mode   = isset( $_POST['import_mode'] ) && 'append' === $_POST['import_mode'] ? 'append' : 'replace';
			$result = Goodnite_SL_DB::import_from_csv( $_FILES['dealers_csv']['tmp_name'], $mode );

			if ( $result['success'] ) {
				add_settings_error( 'goodnite_sl_notices', 'import_success', $result['message'], 'updated' );
			} else {
				add_settings_error( 'goodnite_sl_notices', 'import_error', $result['message'], 'error' );
			}
		}

		// Handle Single Dealer Delete
		if ( isset( $_GET['action'] ) && 'delete_dealer' === $_GET['action'] && isset( $_GET['dealer_id'] ) ) {
			check_admin_referer( 'goodnite_delete_dealer_' . (int) $_GET['dealer_id'] );
			Goodnite_SL_DB::delete_dealer( (int) $_GET['dealer_id'] );
			wp_safe_redirect( admin_url( 'admin.php?page=goodnite-store-locator&tab=dealers&deleted=1' ) );
			exit;
		}

		// Handle Truncate / Clear All
		if ( isset( $_POST['goodnite_sl_clear_all'] ) ) {
			check_admin_referer( 'goodnite_sl_clear_all_nonce' );
			Goodnite_SL_DB::delete_all();
			add_settings_error( 'goodnite_sl_notices', 'clear_success', __( 'All dealers database records have been wiped clean.', 'goodnite-store-locator' ), 'updated' );
		}
	}

	/**
	 * Stream sample CSV template directly to browser
	 */
	public static function stream_sample_csv() {
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=goodnite-dealers-sample-template.csv' );

		$output = fopen( 'php://output', 'w' );

		// Header columns
		fputcsv( $output, array(
			'store_name',
			'contact_person',
			'phone',
			'email',
			'address',
			'city',
			'state',
			'pincode',
			'served_pincodes',
			'timings',
			'google_maps_link'
		) );

		// Sample rows
		$sample_data = array(
			array(
				'Goodnite Experience Flagship Store',
				'Rajesh Sharma',
				'+91 98112 34567',
				'delhi.flagship@goodnite.com',
				'Plot 14, Ring Road, South Extension Part 2',
				'New Delhi',
				'Delhi',
				'110049',
				'110048, 110016, 110024, 110029',
				'10:00 AM - 08:30 PM',
				'https://maps.google.com/?q=South+Extension+Part+2+New+Delhi'
			),
			array(
				'Goodnite Mattress Studio - CP',
				'Amit Verma',
				'+91 98101 98765',
				'cp.store@goodnite.com',
				'Inner Circle, Block C, Connaught Place',
				'New Delhi',
				'Delhi',
				'110001',
				'110002, 110003, 110006',
				'10:30 AM - 09:00 PM',
				'https://maps.google.com/?q=Connaught+Place+New+Delhi'
			),
			array(
				'Goodnite Sleep Lounge - Indiranagar',
				'Priya Nair',
				'+91 99001 22334',
				'blr.indiranagar@goodnite.com',
				'100 Feet Road, HAL 2nd Stage, Indiranagar',
				'Bangalore',
				'Karnataka',
				'560038',
				'560008, 560075, 560071',
				'10:00 AM - 08:00 PM',
				'https://maps.google.com/?q=Indiranagar+100+Feet+Road+Bangalore'
			),
			array(
				'Goodnite Experience Store - Bandra',
				'Karan Mehta',
				'+91 98200 55443',
				'mumbai.bandra@goodnite.com',
				'Hill Road, Bandra West',
				'Mumbai',
				'Maharashtra',
				'400050',
				'400051, 400052, 400053',
				'10:00 AM - 09:00 PM',
				'https://maps.google.com/?q=Bandra+West+Mumbai'
			)
		);

		foreach ( $sample_data as $row ) {
			fputcsv( $output, $row );
		}

		fclose( $output );
	}

	/**
	 * Render Admin Dashboard
	 */
	public static function render_admin_page() {
		$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'import';
		$search     = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '';
		$total_count= Goodnite_SL_DB::get_dealers_count( $search );
		?>
		<div class="wrap goodnite-sl-admin-wrapper">
			<div class="goodnite-sl-header">
				<div class="goodnite-sl-header-title">
					<h1><span class="dashicons dashicons-location-alt"></span> <?php esc_html_e( 'Goodnite Store Locator', 'goodnite-store-locator' ); ?></h1>
					<p class="description"><?php esc_html_e( 'Manage your stores & dealers database, upload CSV spreadsheets, and configure pincode lookup.', 'goodnite-store-locator' ); ?></p>
				</div>
				<div class="goodnite-sl-header-badge">
					<span class="badge-count"><?php echo esc_html( $total_count ); ?></span>
					<span class="badge-label"><?php esc_html_e( 'Total Active Stores', 'goodnite-store-locator' ); ?></span>
				</div>
			</div>

			<?php settings_errors( 'goodnite_sl_notices' ); ?>

			<nav class="nav-tab-wrapper">
				<a href="?page=goodnite-store-locator&tab=import" class="nav-tab <?php echo 'import' === $active_tab ? 'nav-tab-active' : ''; ?>">
					<span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'CSV Import & Sync', 'goodnite-store-locator' ); ?>
				</a>
				<a href="?page=goodnite-store-locator&tab=dealers" class="nav-tab <?php echo 'dealers' === $active_tab ? 'nav-tab-active' : ''; ?>">
					<span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Dealers Database', 'goodnite-store-locator' ); ?> (<?php echo esc_html( $total_count ); ?>)
				</a>
				<a href="?page=goodnite-store-locator&tab=integration" class="nav-tab <?php echo 'integration' === $active_tab ? 'nav-tab-active' : ''; ?>">
					<span class="dashicons dashicons-shortcode"></span> <?php esc_html_e( 'Homepage Setup & Shortcodes', 'goodnite-store-locator' ); ?>
				</a>
			</nav>

			<div class="goodnite-sl-tab-content">
				<?php
				switch ( $active_tab ) {
					case 'dealers':
						self::render_dealers_tab( $search, $total_count );
						break;
					case 'integration':
						self::render_integration_tab();
						break;
					case 'import':
					default:
						self::render_import_tab( $total_count );
						break;
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 1: CSV Importer
	 */
	private static function render_import_tab( $total_count ) {
		$download_sample_url = wp_nonce_url( admin_url( 'admin.php?page=goodnite-store-locator&action=goodnite_download_sample_csv' ), 'goodnite_download_sample_csv_nonce' );
		?>
		<div class="goodnite-sl-grid-2col">
			<!-- Upload Box -->
			<div class="goodnite-card">
				<div class="card-header">
					<h2><span class="dashicons dashicons-media-spreadsheet"></span> <?php esc_html_e( 'Upload Dealers Spreadsheet (CSV)', 'goodnite-store-locator' ); ?></h2>
					<p><?php esc_html_e( 'Select your updated CSV file to bulk import stores and pincodes into the database.', 'goodnite-store-locator' ); ?></p>
				</div>
				<div class="card-body">
					<form method="post" enctype="multipart/form-data" class="goodnite-sl-upload-form">
						<?php wp_nonce_field( 'goodnite_sl_import_nonce' ); ?>

						<div class="upload-dropzone" id="goodnite-dropzone">
							<span class="dashicons dashicons-cloud-upload"></span>
							<h3><?php esc_html_e( 'Choose CSV File or Drag & Drop Here', 'goodnite-store-locator' ); ?></h3>
							<p class="file-name-display"><?php esc_html_e( 'Supports .csv formatted spreadsheets', 'goodnite-store-locator' ); ?></p>
							<input type="file" name="dealers_csv" id="dealers_csv" accept=".csv" required class="file-input">
						</div>

						<div class="form-group mode-selection">
							<label class="section-label"><strong><?php esc_html_e( 'Import Mode:', 'goodnite-store-locator' ); ?></strong></label>
							<div class="radio-options">
								<label class="radio-pill">
									<input type="radio" name="import_mode" value="replace" checked>
									<span class="pill-content">
										<strong><?php esc_html_e( 'Replace All Existing Stores', 'goodnite-store-locator' ); ?></strong>
										<small><?php esc_html_e( 'Wipes current store list and replaces with this fresh upload (Recommended for full catalog updates).', 'goodnite-store-locator' ); ?></small>
									</span>
								</label>
								<label class="radio-pill">
									<input type="radio" name="import_mode" value="append">
									<span class="pill-content">
										<strong><?php esc_html_e( 'Append / Add New Stores', 'goodnite-store-locator' ); ?></strong>
										<small><?php esc_html_e( 'Keeps current stores and appends new rows from the CSV file.', 'goodnite-store-locator' ); ?></small>
									</span>
								</label>
							</div>
						</div>

						<div class="form-actions">
							<button type="submit" name="goodnite_sl_import_submit" class="button button-primary button-hero">
								<span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Import & Update Store Database', 'goodnite-store-locator' ); ?>
							</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Guidelines & Sample Download -->
			<div class="goodnite-card goodnite-card-secondary">
				<div class="card-header">
					<h2><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e( 'CSV Template & Formatting Guide', 'goodnite-store-locator' ); ?></h2>
				</div>
				<div class="card-body">
					<p><?php esc_html_e( 'Use our standard template to ensure all store names, phone numbers, and pincodes are mapped seamlessly:', 'goodnite-store-locator' ); ?></p>
					
					<a href="<?php echo esc_url( $download_sample_url ); ?>" class="button button-secondary button-large btn-download-template">
						<span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download Sample CSV Template', 'goodnite-store-locator' ); ?>
					</a>

					<hr class="divider">

					<h4><?php esc_html_e( 'Supported CSV Column Headers:', 'goodnite-store-locator' ); ?></h4>
					<ul class="columns-checklist">
						<li><code>store_name</code> <span class="tag-required"><?php esc_html_e( 'Required', 'goodnite-store-locator' ); ?></span> - Name of the store/experience center</li>
						<li><code>phone</code> <span class="tag-required"><?php esc_html_e( 'Required', 'goodnite-store-locator' ); ?></span> - Contact number (enables Click-to-Call)</li>
						<li><code>pincode</code> <span class="tag-required"><?php esc_html_e( 'Required', 'goodnite-store-locator' ); ?></span> - Primary 6-digit postal code of store</li>
						<li><code>address</code> <span class="tag-required"><?php esc_html_e( 'Required', 'goodnite-store-locator' ); ?></span> - Full street address</li>
						<li><code>city</code> - City name (used for smart regional fallback)</li>
						<li><code>state</code> - State name</li>
						<li><code>served_pincodes</code> - Comma-separated extra pincodes (e.g. <code>110001, 110002</code>)</li>
						<li><code>timings</code> - Store operating hours (e.g. <code>10:00 AM - 8:30 PM</code>)</li>
						<li><code>google_maps_link</code> - Google Maps link for directions (auto-generated if left blank)</li>
						<li><code>contact_person</code> & <code>email</code> - Optional contact info</li>
					</ul>

					<?php if ( $total_count > 0 ) : ?>
					<hr class="divider">
					<form method="post" onsubmit="return confirm('Are you sure you want to delete ALL stores from the database? This cannot be undone.');">
						<?php wp_nonce_field( 'goodnite_sl_clear_all_nonce' ); ?>
						<button type="submit" name="goodnite_sl_clear_all" class="button button-link-delete">
							<span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Wipe / Clear All Stores from Database', 'goodnite-store-locator' ); ?>
						</button>
					</form>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 2: Manage Dealers Table
	 */
	private static function render_dealers_tab( $search, $total_count ) {
		$page_num = isset( $_GET['paged'] ) ? max( 1, (int) $_GET['paged'] ) : 1;
		$limit    = 30;
		$offset   = ( $page_num - 1 ) * $limit;
		$dealers  = Goodnite_SL_DB::get_dealers( $limit, $offset, $search );
		$total_pages = ceil( $total_count / $limit );
		?>
		<div class="goodnite-card">
			<div class="card-header card-header-flex">
				<div>
					<h2><?php esc_html_e( 'Current Dealers in Database', 'goodnite-store-locator' ); ?></h2>
					<p><?php printf( esc_html__( 'Showing %d stores currently loaded in database.', 'goodnite-store-locator' ), $total_count ); ?></p>
				</div>
				<form method="get" class="search-box-form">
					<input type="hidden" name="page" value="goodnite-store-locator">
					<input type="hidden" name="tab" value="dealers">
					<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search by Name, City, Pincode...', 'goodnite-store-locator' ); ?>" class="regular-text">
					<button type="submit" class="button"><?php esc_html_e( 'Search', 'goodnite-store-locator' ); ?></button>
					<?php if ( ! empty( $search ) ) : ?>
						<a href="?page=goodnite-store-locator&tab=dealers" class="button"><?php esc_html_e( 'Clear', 'goodnite-store-locator' ); ?></a>
					<?php endif; ?>
				</form>
			</div>

			<div class="card-body no-padding">
				<?php if ( empty( $dealers ) ) : ?>
					<div class="empty-state">
						<span class="dashicons dashicons-store"></span>
						<h3><?php esc_html_e( 'No stores found in database.', 'goodnite-store-locator' ); ?></h3>
						<p><?php esc_html_e( 'Upload your CSV spreadsheet to populate store locations.', 'goodnite-store-locator' ); ?></p>
						<a href="?page=goodnite-store-locator&tab=import" class="button button-primary"><?php esc_html_e( 'Go to CSV Importer', 'goodnite-store-locator' ); ?></a>
					</div>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th style="width: 50px;"><?php esc_html_e( 'ID', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'Store Name', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'City / State', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'Pincode', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'Served Pincodes', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'Phone', 'goodnite-store-locator' ); ?></th>
								<th><?php esc_html_e( 'Timings', 'goodnite-store-locator' ); ?></th>
								<th style="width: 90px;"><?php esc_html_e( 'Actions', 'goodnite-store-locator' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $dealers as $dealer ) : ?>
								<tr>
									<td>#<?php echo esc_html( $dealer['id'] ); ?></td>
									<td>
										<strong><?php echo esc_html( $dealer['store_name'] ); ?></strong>
										<div class="store-address-muted"><?php echo esc_html( $dealer['address'] ); ?></div>
									</td>
									<td>
										<?php echo esc_html( $dealer['city'] ); ?>
										<?php if ( ! empty( $dealer['state'] ) ) : ?>
											<span class="muted">, <?php echo esc_html( $dealer['state'] ); ?></span>
										<?php endif; ?>
									</td>
									<td><span class="pincode-badge"><?php echo esc_html( $dealer['pincode'] ); ?></span></td>
									<td>
										<?php if ( ! empty( $dealer['served_pincodes'] ) ) : ?>
											<small class="served-pincodes-list"><?php echo esc_html( $dealer['served_pincodes'] ); ?></small>
										<?php else : ?>
											<span class="muted">—</span>
										<?php endif; ?>
									</td>
									<td><a href="tel:<?php echo esc_attr( $dealer['phone'] ); ?>"><?php echo esc_html( $dealer['phone'] ); ?></a></td>
									<td><small><?php echo esc_html( $dealer['timings'] ); ?></small></td>
									<td>
										<?php if ( ! empty( $dealer['google_maps_link'] ) ) : ?>
											<a href="<?php echo esc_url( $dealer['google_maps_link'] ); ?>" target="_blank" class="button button-small" title="<?php esc_attr_e( 'View on Google Maps', 'goodnite-store-locator' ); ?>">
												<span class="dashicons dashicons-external"></span>
											</a>
										<?php endif; ?>
										<?php
										$del_url = wp_nonce_url(
											admin_url( 'admin.php?page=goodnite-store-locator&action=delete_dealer&dealer_id=' . $dealer['id'] ),
											'goodnite_delete_dealer_' . $dealer['id']
										);
										?>
										<a href="<?php echo esc_url( $del_url ); ?>" onclick="return confirm('Delete this store?');" class="button button-small button-link-delete" title="<?php esc_attr_e( 'Delete', 'goodnite-store-locator' ); ?>">
											<span class="dashicons dashicons-trash"></span>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php if ( $total_pages > 1 ) : ?>
						<div class="tablenav bottom">
							<div class="tablenav-pages">
								<span class="displaying-num"><?php printf( esc_html__( '%d items', 'goodnite-store-locator' ), $total_count ); ?></span>
								<?php
								echo paginate_links( array(
									'base'      => add_query_arg( 'paged', '%#%' ),
									'format'    => '',
									'prev_text' => '&laquo;',
									'next_text' => '&raquo;',
									'total'     => $total_pages,
									'current'   => $page_num,
								) );
								?>
							</div>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Tab 3: Integration & Shortcode Instructions
	 */
	private static function render_integration_tab() {
		?>
		<div class="goodnite-card">
			<div class="card-header">
				<h2><span class="dashicons dashicons-welcome-widgets-menus"></span> <?php esc_html_e( 'How to Display on Homepage', 'goodnite-store-locator' ); ?></h2>
				<p><?php esc_html_e( 'Integrate the Store Locator anywhere on your website using shortcodes, Gutenberg blocks, or PHP code.', 'goodnite-store-locator' ); ?></p>
			</div>
			<div class="card-body">
				<div class="integration-step">
					<h3>1. Using Standard WordPress Shortcode (Recommended)</h3>
					<p><?php esc_html_e( 'Copy and paste this shortcode into any Page, Post, or Elementor / Gutenberg Shortcode block:', 'goodnite-store-locator' ); ?></p>
					<pre class="code-block"><code>[goodnite_store_locator]</code></pre>
				</div>

				<div class="integration-step">
					<h3>2. Shortcode with Custom Heading & Subtitle</h3>
					<pre class="code-block"><code>[goodnite_store_locator title="Find an Exclusive Goodnite Experience Store" subtitle="Enter your 6-digit postal code to discover authorized dealers, touch the mattresses in person, and get expert consultation." placeholder="Enter your 6-digit PIN code..."]</code></pre>
				</div>

				<div class="integration-step">
					<h3>3. Direct PHP Template Integration (For Custom Theme Developers)</h3>
					<p><?php esc_html_e( 'Add this line directly into your theme’s front-page.php or template-parts/section-find-store.php:', 'goodnite-store-locator' ); ?></p>
					<pre class="code-block"><code>&lt;?php echo do_shortcode( '[goodnite_store_locator]' ); ?&gt;</code></pre>
				</div>

				<div class="integration-step">
					<h3>4. REST API Endpoint</h3>
					<p><?php esc_html_e( 'For headless setups or mobile apps, query the live REST API directly:', 'goodnite-store-locator' ); ?></p>
					<pre class="code-block"><code>GET <?php echo esc_url( rest_url( 'goodnite/v1/stores/search?pincode=110001' ) ); ?></code></pre>
				</div>
			</div>
		</div>
		<?php
	}
}
