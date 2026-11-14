<?php
if (!defined('ABSPATH')) {
	exit;
}

class Goodnite_SL_DB
{
	public static function get_table_name()
	{
		global $wpdb;
		return $wpdb->prefix . 'goodnite_dealers';
	}

	public static function init()
	{
		$installed_ver = get_option('goodnite_sl_db_version');
		if ($installed_ver !== GOODNITE_SL_DB_VERSION) {
			self::create_tables();
		}
	}

	public static function create_tables()
	{
		global $wpdb;

		$table_name = self::get_table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			store_name varchar(255) NOT NULL,
			contact_person varchar(100) DEFAULT '',
			phone varchar(50) NOT NULL,
			email varchar(100) DEFAULT '',
			address text NOT NULL,
			city varchar(100) NOT NULL,
			state varchar(100) DEFAULT '',
			pincode varchar(20) NOT NULL,
			served_pincodes text DEFAULT '',
			google_maps_link text DEFAULT '',
			timings varchar(150) DEFAULT '',
			is_active tinyint(1) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			KEY pincode_idx (pincode),
			KEY city_idx (city),
			KEY active_idx (is_active)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		update_option('goodnite_sl_db_version', GOODNITE_SL_DB_VERSION);
	}

	/**
	 * Import dealers from a CSV file
	 *
	 * @param string $file_path Path to uploaded CSV file
	 * @param string $mode 'replace' or 'append'
	 * @return array
	 */
	public static function import_from_csv($file_path, $mode = 'replace')
	{
		global $wpdb;

		if (!file_exists($file_path) || !is_readable($file_path)) {
			return array(
				'success' => false,
				'message' => __('CSV file could not be read or does not exist.', 'goodnite-store-locator'),
			);
		}

		$handle = fopen($file_path, 'r');
		if (false === $handle) {
			return array(
				'success' => false,
				'message' => __('Failed to open CSV file stream.', 'goodnite-store-locator'),
			);
		}

		$header = fgetcsv($handle, 0, ',');
		if (empty($header)) {
			fclose($handle);
			return array(
				'success' => false,
				'message' => __('The uploaded CSV file appears to be empty.', 'goodnite-store-locator'),
			);
		}

		$header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);

		$header_map = array();
		foreach ($header as $index => $col) {
			$col_clean = strtolower(trim($col));
			$col_clean = str_replace(array(' ', '-', '/'), '_', $col_clean);

			// Map aliases
			if (in_array($col_clean, array('name', 'store', 'dealer_name', 'shop_name'), true)) {
				$col_clean = 'store_name';
			} elseif (in_array($col_clean, array('mobile', 'telephone', 'contact_number', 'contact'), true)) {
				$col_clean = 'phone';
			} elseif (in_array($col_clean, array('zip', 'zipcode', 'pin', 'postal_code'), true)) {
				$col_clean = 'pincode';
			} elseif (in_array($col_clean, array('serviceable_pincodes', 'covered_pincodes', 'other_pincodes'), true)) {
				$col_clean = 'served_pincodes';
			} elseif (in_array($col_clean, array('maps_link', 'google_map', 'map_url', 'maps_url'), true)) {
				$col_clean = 'google_maps_link';
			} elseif (in_array($col_clean, array('hours', 'working_hours', 'opening_hours'), true)) {
				$col_clean = 'timings';
			}

			$header_map[$col_clean] = $index;
		}

		$required = array('store_name', 'phone', 'address', 'pincode');
		$missing = array();
		foreach ($required as $req) {
			if (!isset($header_map[$req])) {
				$missing[] = $req;
			}
		}

		if (!empty($missing)) {
			fclose($handle);
			return array(
				'success' => false,
				'message' => sprintf(
					__('CSV is missing required columns: %s. Please use the sample template.', 'goodnite-store-locator'),
					implode(', ', $missing)
				),
			);
		}

		$table_name = self::get_table_name();

		if ('replace' === $mode) {
			$wpdb->query("TRUNCATE TABLE {$table_name}");
		}

		$imported_count = 0;
		$skipped_count = 0;
		$row_index = 1;
		while (($row = fgetcsv($handle, 0, ',')) !== false) {
			$row_index++;
			if (empty(array_filter($row))) {
				continue;
			}

			$get_val = function ($key) use ($header_map, $row) {
				if (isset($header_map[$key]) && isset($row[$header_map[$key]])) {
					return trim($row[$header_map[$key]]);
				}
				return '';
			};

			$store_name = sanitize_text_field($get_val('store_name'));
			$phone = sanitize_text_field($get_val('phone'));
			$address = sanitize_textarea_field($get_val('address'));
			$pincode = sanitize_text_field($get_val('pincode'));
			$city = sanitize_text_field($get_val('city'));
			$state = sanitize_text_field($get_val('state'));
			$contact_person = sanitize_text_field($get_val('contact_person'));
			$email = sanitize_email($get_val('email'));
			$served_pincodes = sanitize_text_field($get_val('served_pincodes'));
			$google_maps_link = esc_url_raw($get_val('google_maps_link'));
			$timings = sanitize_text_field($get_val('timings'));

			if (empty($google_maps_link) && !empty($store_name)) {
				$query_parts = array_filter(array($store_name, $address, $city, $pincode));
				$google_maps_link = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(implode(', ', $query_parts));
			}

			if (empty($store_name) || empty($phone) || empty($pincode)) {
				$skipped_count++;
				continue;
			}

			// Format served pincodes: comma separated without extra spaces
			if (!empty($served_pincodes)) {
				$pincode_arr = array_map('trim', explode(',', $served_pincodes));
				$served_pincodes = implode(',', array_filter($pincode_arr));
			}

			$inserted = $wpdb->insert(
				$table_name,
				array(
					'store_name' => $store_name,
					'contact_person' => $contact_person,
					'phone' => $phone,
					'email' => $email,
					'address' => $address,
					'city' => $city,
					'state' => $state,
					'pincode' => $pincode,
					'served_pincodes' => $served_pincodes,
					'google_maps_link' => $google_maps_link,
					'timings' => !empty($timings) ? $timings : '10:00 AM - 8:00 PM',
					'is_active' => 1,
					'created_at' => current_time('mysql'),
				),
				array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s')
			);

			if (false !== $inserted) {
				$imported_count++;
			} else {
				$skipped_count++;
			}
		}

		fclose($handle);

		return array(
			'success' => true,
			'imported' => $imported_count,
			'skipped' => $skipped_count,
			'message' => sprintf(
				__('Import completed successfully! %d stores imported/updated. (%d skipped)', 'goodnite-store-locator'),
				$imported_count,
				$skipped_count
			),
		);
	}

	/**
	 * Search dealers by Pincode with smart nearby fallback
	 *
	 * @param string $pincode 6-digit or partial pincode
	 * @param string $city Optional city filter
	 * @param int $limit Maximum results
	 * @return array
	 */
	public static function search_dealers($pincode, $city = '', $limit = 20)
	{
		global $wpdb;

		$table_name = self::get_table_name();
		$pincode = sanitize_text_field(trim($pincode));
		$city = sanitize_text_field(trim($city));

		if (empty($pincode) && empty($city)) {
			return array(
				'match_type' => 'none',
				'dealers' => array(),
			);
		}

		// 1. Exact match on pincode or served_pincodes
		$exact_query = "SELECT * FROM {$table_name} WHERE is_active = 1 AND (
			pincode = %s 
			OR FIND_IN_SET(%s, REPLACE(served_pincodes, ' ', '')) > 0
			OR served_pincodes LIKE %s
		)";
		$like_pincode = '%' . $wpdb->esc_like($pincode) . '%';

		$exact_results = $wpdb->get_results(
			$wpdb->prepare($exact_query, $pincode, $pincode, $like_pincode),
			ARRAY_A
		);

		if (!empty($exact_results)) {
			return array(
				'match_type' => 'exact',
				'dealers' => $exact_results,
			);
		}

		// 2. Smart Regional / Nearby Fallback
		// If 6-digit pincode, check first 3-4 digits (same postal district/region)
		$nearby_results = array();
		if (strlen($pincode) >= 4) {
			$prefix = substr($pincode, 0, 3); // First 3 digits
			$prefix_like = $wpdb->esc_like($prefix) . '%';

			$nearby_query = "SELECT * FROM {$table_name} WHERE is_active = 1 AND pincode LIKE %s LIMIT %d";
			$nearby_results = $wpdb->get_results(
				$wpdb->prepare($nearby_query, $prefix_like, $limit),
				ARRAY_A
			);
		}

		// 3. Fallback by City if provided or if prefix returned empty
		if (empty($nearby_results) && !empty($city)) {
			$city_like = '%' . $wpdb->esc_like($city) . '%';
			$city_query = "SELECT * FROM {$table_name} WHERE is_active = 1 AND city LIKE %s LIMIT %d";
			$nearby_results = $wpdb->get_results(
				$wpdb->prepare($city_query, $city_like, $limit),
				ARRAY_A
			);
		}

		if (!empty($nearby_results)) {
			return array(
				'match_type' => 'nearby',
				'dealers' => $nearby_results,
			);
		}

		return array(
			'match_type' => 'none',
			'dealers' => array(),
		);
	}

	/**
	 * Get list of dealers for Admin
	 */
	public static function get_dealers($limit = 50, $offset = 0, $search = '')
	{
		global $wpdb;

		$table_name = self::get_table_name();
		$search = sanitize_text_field(trim($search));

		if (!empty($search)) {
			$like = '%' . $wpdb->esc_like($search) . '%';
			$sql = $wpdb->prepare(
				"SELECT * FROM {$table_name} 
				 WHERE store_name LIKE %s OR city LIKE %s OR pincode LIKE %s OR phone LIKE %s 
				 ORDER BY id DESC LIMIT %d OFFSET %d",
				$like,
				$like,
				$like,
				$like,
				$limit,
				$offset
			);
		} else {
			$sql = $wpdb->prepare(
				"SELECT * FROM {$table_name} ORDER BY id DESC LIMIT %d OFFSET %d",
				$limit,
				$offset
			);
		}

		return $wpdb->get_results($sql, ARRAY_A);
	}

	/**
	 * Total dealers count
	 */
	public static function get_dealers_count($search = '')
	{
		global $wpdb;

		$table_name = self::get_table_name();
		if (!empty($search)) {
			$like = '%' . $wpdb->esc_like($search) . '%';
			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$table_name} 
					 WHERE store_name LIKE %s OR city LIKE %s OR pincode LIKE %s OR phone LIKE %s",
					$like,
					$like,
					$like,
					$like
				)
			);
		}

		return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
	}

	/**
	 * Delete a dealer
	 */
	public static function delete_dealer($id)
	{
		global $wpdb;
		return $wpdb->delete(self::get_table_name(), array('id' => (int) $id), array('%d'));
	}

	/**
	 * Truncate all dealers
	 */
	public static function delete_all()
	{
		global $wpdb;
		return $wpdb->query('TRUNCATE TABLE ' . self::get_table_name());
	}
}
