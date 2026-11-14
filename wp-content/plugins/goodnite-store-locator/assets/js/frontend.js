/**
 * Goodnite Store Locator - Frontend Script
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var section = document.getElementById('goodnite-store-locator-section');
		if (!section) return;

		var form = document.getElementById('goodnite-sl-search-form');
		var input = document.getElementById('goodnite-sl-pincode-input');
		var searchBtn = document.getElementById('goodnite-sl-search-btn');
		var geoBtn = document.getElementById('goodnite-sl-geolocate-btn');
		var resultsWrapper = document.getElementById('goodnite-sl-results-wrapper');
		var statusBar = document.getElementById('goodnite-sl-status-bar');
		var statusBadge = document.getElementById('goodnite-sl-status-badge');
		var statusText = document.getElementById('goodnite-sl-status-text');
		var quickPills = section.querySelectorAll('.quick-pill-btn');

		var config = window.goodnite_sl_config || {
			api_url: '/wp-json/goodnite/v1/stores/search',
			i18n: {
				searching: 'Searching for nearest stores...',
				exact_match: 'Authorized Store Found for Your Pincode',
				nearby_match: 'Nearest Authorized Store in Your Region',
				no_stores: 'No authorized stores found near this pincode currently.',
				call_now: 'Call Store',
				get_directions: 'Get Directions',
				open_hours: 'Timings',
				error: 'Unable to search stores right now. Please try again.',
				geo_error: 'Location access was denied or not supported.'
			}
		};

		var isSearching = false;

		// Handle Form Submit
		if (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var pincode = input.value.trim();
				if (pincode) {
					performSearch(pincode);
				}
			});
		}

		// Handle Enter Key / Input events
		if (input) {
			input.addEventListener('input', function () {
				var val = this.value.trim();
				// Auto-search if user types exactly 6 numeric digits
				if (/^\d{6}$/.test(val) && !isSearching) {
					performSearch(val);
				}
			});
		}

		// Handle Quick Pills
		quickPills.forEach(function (pill) {
			pill.addEventListener('click', function () {
				var pin = this.getAttribute('data-pincode');
				if (pin && input) {
					input.value = pin;
					performSearch(pin);
				}
			});
		});

		// Handle Geolocation Button
		if (geoBtn) {
			geoBtn.addEventListener('click', function () {
				if (!navigator.geolocation) {
					alert(config.i18n.geo_error);
					return;
				}

				setLoading(true);
				geoBtn.disabled = true;

				navigator.geolocation.getCurrentPosition(
					function (position) {
						var lat = position.coords.latitude;
						var lng = position.coords.longitude;

						// Reverse geocode via free BigDataCloud/OSM API to get pincode
						fetch('https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=' + lat + '&longitude=' + lng + '&localityLanguage=en')
							.then(function (res) { return res.json(); })
							.then(function (data) {
								geoBtn.disabled = false;
								var postcode = data.postcode || '';
								var city = data.city || data.locality || '';

								if (postcode) {
									input.value = postcode;
									performSearch(postcode, city);
								} else if (city) {
									input.value = city;
									performSearch('', city);
								} else {
									setLoading(false);
									alert('Could not determine exact postal code from your location. Please enter it manually.');
								}
							})
							.catch(function () {
								geoBtn.disabled = false;
								setLoading(false);
								alert('Could not determine your postal code automatically.');
							});
					},
					function () {
						geoBtn.disabled = false;
						setLoading(false);
						alert(config.i18n.geo_error);
					},
					{ timeout: 10000 }
				);
			});
		}

		/**
		 * Fetch dealers from REST API
		 */
		function performSearch(pincode, city) {
			if (isSearching) return;
			setLoading(true);

			var url = config.api_url + '?pincode=' + encodeURIComponent(pincode || '') + '&city=' + encodeURIComponent(city || '');

			fetch(url, {
				method: 'GET',
				headers: {
					'X-WP-Nonce': config.nonce || ''
				}
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (data) {
					setLoading(false);
					if (data && data.success) {
						renderResults(data);
					} else {
						renderEmpty(data.message || config.i18n.no_stores);
					}
				})
				.catch(function () {
					setLoading(false);
					renderEmpty(config.i18n.error);
				});
		}

		/**
		 * Set UI Loading State
		 */
		function setLoading(loading) {
			isSearching = loading;
			var spinner = searchBtn ? searchBtn.querySelector('.btn-spinner') : null;
			var btnText = searchBtn ? searchBtn.querySelector('.btn-text') : null;

			if (loading) {
				if (spinner) spinner.style.display = 'inline-block';
				if (btnText) btnText.style.display = 'none';
				if (searchBtn) searchBtn.disabled = true;
			} else {
				if (spinner) spinner.style.display = 'none';
				if (btnText) btnText.style.display = 'inline';
				if (searchBtn) searchBtn.disabled = false;
			}
		}

		/**
		 * Render Results into UI
		 */
		function renderResults(data) {
			var dealers = data.dealers || [];
			var matchType = data.match_type || 'exact';
			var searchedPin = data.pincode_searched || '';

			if (!dealers.length) {
				renderEmpty(config.i18n.no_stores, searchedPin);
				return;
			}

			// Show Status Bar
			if (statusBar && statusBadge && statusText) {
				statusBar.style.display = 'flex';
				statusBadge.className = 'status-badge ' + matchType;
				statusBadge.textContent = matchType === 'exact' ? 'Exact Match' : 'Regional Match';
				statusText.textContent = matchType === 'exact' 
					? dealers.length + ' ' + config.i18n.exact_match + ' (' + searchedPin + ')'
					: config.i18n.nearby_match + ' for ' + searchedPin;
			}

			var html = '<div class="goodnite-sl-dealers-grid">';

			dealers.forEach(function (dealer) {
				var isNearest = matchType === 'exact';
				var mapUrl = dealer.google_maps_link || ('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(dealer.name + ' ' + dealer.address));

				html += '<div class="goodnite-sl-dealer-card ' + (isNearest ? 'is-nearest' : '') + '">';
				
				html += '<div class="dealer-card-badge-top">';
				html += isNearest ? '★ Official Experience Store' : '📍 Authorized Partner Store';
				html += '</div>';

				html += '<h3 class="dealer-title">' + escapeHTML(dealer.name) + '</h3>';

				html += '<div class="dealer-address">';
				html += '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
				html += '<span>' + escapeHTML(dealer.address) + ', ' + escapeHTML(dealer.city) + ' - <strong>' + escapeHTML(dealer.pincode) + '</strong></span>';
				html += '</div>';

				html += '<div class="dealer-meta-list">';
				if (dealer.timings) {
					html += '<div class="dealer-meta-item">';
					html += '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
					html += '<span>' + config.i18n.open_hours + ': <strong>' + escapeHTML(dealer.timings) + '</strong></span>';
					html += '</div>';
				}

				if (dealer.phone) {
					html += '<div class="dealer-meta-item">';
					html += '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
					html += '<span>' + escapeHTML(dealer.phone) + '</span>';
					html += '</div>';
				}
				html += '</div>';

				html += '<div class="dealer-actions">';
				if (dealer.phone_url) {
					html += '<a href="' + dealer.phone_url + '" class="btn-dealer-call">';
					html += '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
					html += config.i18n.call_now;
					html += '</a>';
				}

				html += '<a href="' + mapUrl + '" target="_blank" rel="noopener noreferrer" class="btn-dealer-map">';
				html += '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>';
				html += config.i18n.get_directions;
				html += '</a>';
				html += '</div>';

				html += '</div>';
			});

			html += '</div>';

			resultsWrapper.innerHTML = html;
		}

		/**
		 * Render Empty State
		 */
		function renderEmpty(message, pin) {
			if (statusBar) statusBar.style.display = 'none';

			var html = '<div class="goodnite-sl-empty-results">';
			html += '<div class="empty-icon-circle">';
			html += '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="8" y1="12" x2="16" y2="12"></line></svg>';
			html += '</div>';
			html += '<h3>' + (pin ? 'No direct store found in ' + escapeHTML(pin) : 'No stores found') + '</h3>';
			html += '<p>' + escapeHTML(message) + ' Try entering a nearby main city or district PIN code, or contact our national sleep concierge for home delivery assistance.</p>';
			html += '</div>';

			resultsWrapper.innerHTML = html;
		}

		function escapeHTML(str) {
			if (!str) return '';
			return String(str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}
	});
})();
