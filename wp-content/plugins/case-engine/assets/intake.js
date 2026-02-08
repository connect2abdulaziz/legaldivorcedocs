(function ($) {
	'use strict';

	var $container = $('#az-intake');
	if (!$container.length) return;

	var total = window.caseEngineIntake && window.caseEngineIntake.total ? parseInt(window.caseEngineIntake.total, 10) : 12;
	// Keep session cookie so reload restores progress (screen + answers).
	var sessionKey = getSessionKey();

	function getSessionKey() {
		var match = document.cookie.match(/az_intake_session=([a-zA-Z0-9]{32})/);
		return match ? match[1] : '';
	}

	function setSessionKey(key) {
		document.cookie = 'az_intake_session=' + key + '; path=/; max-age=86400*7; SameSite=Lax';
	}

	function getCurrentScreen() {
		return parseInt($container.attr('data-current') || '1', 10);
	}

	function setCurrentScreen(num) {
		$container.attr('data-current', num);
		$('.az-intake-screen').removeClass('az-intake-active').attr('aria-hidden', 'true');
		var $screen = $('#az-intake-screen-' + num);
		$screen.addClass('az-intake-active').attr('aria-hidden', 'false');
		$screen.find('.az-intake-stop-message').attr('hidden', true).text('');
		// Update progress bar
		var pct = Math.round((num / total) * 100);
		$('.az-intake-step-current').text(num);
		$('.az-intake-progress-fill').css('width', pct + '%');
	}

	function getAnswersForScreen(screenNum) {
		var $screen = $('#az-intake-screen-' + screenNum);
		var data = {};
		$screen.find('input, select, textarea').each(function () {
			var $el = $(this);
			var name = $el.attr('name');
			if (!name) return;
			if ($el.attr('type') === 'radio' || $el.attr('type') === 'checkbox') {
				if ($el.attr('type') === 'checkbox') {
					data[name] = $el.is(':checked') ? '1' : '';
				} else if ($el.is(':checked')) {
					data[name] = $el.val();
				}
			} else {
				data[name] = $el.val() || '';
			}
		});
		// Flatten children[0][x] etc. for server
		return data;
	}

	function getAllAnswersUpTo(screenNum) {
		var merged = {};
		for (var s = 1; s <= screenNum; s++) {
			var $screen = $('#az-intake-screen-' + s);
			$screen.find('input, select, textarea').each(function () {
				var $el = $(this);
				var name = $el.attr('name');
				if (!name) return;
				if ($el.attr('type') === 'radio') {
					if ($el.is(':checked')) merged[name] = $el.val();
				} else if ($el.attr('type') === 'checkbox') {
					merged[name] = $el.is(':checked') ? '1' : '';
				} else {
					merged[name] = $el.val() || '';
				}
			});
		}
		return merged;
	}

	function showStopMessage($screen, message) {
		var $msg = $screen.find('.az-intake-stop-message');
		$msg.text(message).attr('hidden', false);
		$screen.find('.az-intake-btn-next').prop('disabled', true);
	}

	function hideStopMessage($screen) {
		$screen.find('.az-intake-stop-message').attr('hidden', true).text('');
		$screen.find('.az-intake-btn-next').prop('disabled', false);
	}

	// Populate all form fields from saved answers (used after restore on page load).
	function restoreFormState(answers) {
		if (!answers || typeof answers !== 'object') return;
		var children = answers.children;
		if (children && typeof children === 'object') {
			var indices = Object.keys(children).filter(function (k) { return /^\d+$/.test(k); }).map(Number).sort(function (a, b) { return a - b; });
			indices.forEach(function (i) {
				var row = children[i];
				if (!row || typeof row !== 'object') return;
				if (i > 0) {
					var $list = $('.az-intake-children-list');
					var n = $list.find('.az-intake-child-row').length;
					var html = '<div class="az-intake-child-row">' +
						'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Full name</span> <input type="text" name="children[' + n + '][full_name]" /></label></div>' +
						'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Date of birth</span> <input type="date" name="children[' + n + '][dob]" /></label></div>' +
						'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Relationship</span> <input type="text" name="children[' + n + '][relationship]" placeholder="e.g. Son, Daughter" /></label></div>' +
						'</div>';
					$list.append(html);
				}
				$container.find('input[name="children[' + i + '][full_name]"]').val(row.full_name || '');
				$container.find('input[name="children[' + i + '][dob]"]').val(row.dob || '');
				$container.find('input[name="children[' + i + '][relationship]"]').val(row.relationship || '');
			});
		}
		Object.keys(answers).forEach(function (key) {
			if (key === 'children') return;
			var val = answers[key];
			var $el = $container.find('[name="' + key + '"]').first();
			if (!$el.length) return;
			var tag = $el.prop('tagName');
			var type = ($el.attr('type') || '').toLowerCase();
			if (type === 'radio') {
				$container.find('[name="' + key + '"][value="' + (val || '').replace(/"/g, '\\"') + '"]').prop('checked', true);
			} else if (type === 'checkbox') {
				$el.prop('checked', val === '1' || val === 'yes' || val === true);
			} else {
				$el.val(val || '');
			}
		});
	}

	// When user changes their answer on a gate screen (e.g. agreement_check), clear stop message and re-enable Continue
	$container.on('change', 'input.az-intake-radio, input.az-intake-checkbox', function () {
		var current = getCurrentScreen();
		var $screen = $('#az-intake-screen-' + current);
		var $msg = $screen.find('.az-intake-stop-message');
		if ($msg.length && $msg.attr('hidden') !== 'hidden') {
			$msg.attr('hidden', true).text('');
			$screen.find('.az-intake-btn-next').prop('disabled', false);
		}
	});

	// Toggle children-agreement block when "has_children" = yes (screen 4)
	function bindHasChildren() {
		$container.on('change', 'select[name="has_children"]', function () {
			var val = $(this).val();
			$('.az-intake-children-agreement').toggle(val === 'yes');
			if (val !== 'yes') {
				$('input[name="children_agreement"]').prop('checked', false);
			}
		});
		// Initial
		var $sel = $container.find('select[name="has_children"]');
		if ($sel.length) $('.az-intake-children-agreement').toggle($sel.val() === 'yes');
	}

	// Add another child row (screen 9)
	$container.on('click', '.az-intake-add-child', function () {
		var $list = $('.az-intake-children-list');
		var n = $list.find('.az-intake-child-row').length;
		var html = '<div class="az-intake-child-row">' +
			'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Full name</span> <input type="text" name="children[' + n + '][full_name]" /></label></div>' +
			'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Date of birth</span> <input type="date" name="children[' + n + '][dob]" /></label></div>' +
			'<div class="az-intake-form-group"><label><span class="az-intake-label-text">Relationship</span> <input type="text" name="children[' + n + '][relationship]" placeholder="e.g. Son, Daughter" /></label></div>' +
			'</div>';
		$list.append(html);
	});

	// Next: validate gate, then save and advance or show stop
	$container.on('click', '.az-intake-btn-next', function () {
		var current = getCurrentScreen();
		var $screen = $('#az-intake-screen-' + current);
		var answers = getAllAnswersUpTo(current);

		// Screen 5: show/hide children question from screen 4
		if (current === 5 && answers.has_children === 'yes') {
			answers.children_agreement = $screen.find('input[name="children_agreement"]:checked').val() || '';
		}

		$.ajax({
			url: window.caseEngineIntake && window.caseEngineIntake.ajaxUrl,
			type: 'POST',
			data: {
				action: 'az_intake_save',
				nonce: window.caseEngineIntake && window.caseEngineIntake.nonce,
				session_key: sessionKey,
				current_screen: current,
				answers: answers
			},
			success: function (res) {
				if (res.success && res.data) {
					if (res.data.session_key) {
						sessionKey = res.data.session_key;
						setSessionKey(sessionKey);
					}
					if (res.data.can_proceed === false) {
						showStopMessage($screen, res.data.stop_message || 'This service only supports uncontested divorces.');
						return;
					}
					hideStopMessage($screen);
					if (res.data.next_screen) {
						setCurrentScreen(res.data.next_screen);
					}
				}
			},
			error: function () {
				showStopMessage($screen, 'Unable to save. Please try again.');
			}
		});
	});

	// Previous
	$container.on('click', '.az-intake-btn-prev', function () {
		var current = getCurrentScreen();
		if (current > 1) {
			setCurrentScreen(current - 1);
			$('#az-intake-screen-' + (current - 1)).find('.az-intake-btn-next').prop('disabled', false);
			$('#az-intake-screen-' + (current - 1)).find('.az-intake-stop-message').attr('hidden', true);
		}
	});

	// Screen 12: "Go to Dashboard" — mark session completed and clear cookie so next visit starts fresh
	$container.on('click', '#az-intake-screen-12 a[href*="client-dashboard"]', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (sessionKey) {
			$.post(window.caseEngineIntake && window.caseEngineIntake.ajaxUrl, {
				action: 'az_intake_complete',
				nonce: window.caseEngineIntake && window.caseEngineIntake.nonce,
				session_key: sessionKey
			});
		}
		document.cookie = 'az_intake_session=; path=/; max-age=0; SameSite=Lax';
		window.location.href = href;
	});

	// Payment button (screen 11): placeholder — in production would redirect to Stripe Checkout
	$container.on('click', '.az-intake-btn-payment', function () {
		$.ajax({
			url: window.caseEngineIntake && window.caseEngineIntake.ajaxUrl,
			type: 'POST',
			data: {
				action: 'az_intake_payment',
				nonce: window.caseEngineIntake && window.caseEngineIntake.nonce,
				session_key: sessionKey
			},
			success: function (res) {
				if (res.success && res.data && res.data.redirect) {
					if (sessionKey) {
						$.post(window.caseEngineIntake && window.caseEngineIntake.ajaxUrl, {
							action: 'az_intake_complete',
							nonce: window.caseEngineIntake && window.caseEngineIntake.nonce,
							session_key: sessionKey
						});
					}
					document.cookie = 'az_intake_session=; path=/; max-age=0; SameSite=Lax';
					setCurrentScreen(12);
					window.location.href = res.data.redirect;
				}
			}
		});
	});

	// Init: show screen 1, then restore session if cookie exists (so reload keeps progress)
	setCurrentScreen(1);
	bindHasChildren();
	if (sessionKey) {
		$.ajax({
			url: window.caseEngineIntake && window.caseEngineIntake.ajaxUrl,
			type: 'POST',
			data: {
				action: 'az_intake_restore',
				nonce: window.caseEngineIntake && window.caseEngineIntake.nonce,
				session_key: sessionKey
			},
			success: function (res) {
				if (res.success && res.data) {
					if (res.data.clear_cookie) {
						document.cookie = 'az_intake_session=; path=/; max-age=0; SameSite=Lax';
						sessionKey = '';
					}
					if (res.data.restored) {
						restoreFormState(res.data.answers);
						var screen = parseInt(res.data.current_screen, 10) || 1;
						setCurrentScreen(screen);
						if (res.data.session_key) {
							sessionKey = res.data.session_key;
							setSessionKey(sessionKey);
						}
						$('.az-intake-children-agreement').toggle($container.find('select[name="has_children"]').val() === 'yes');
					}
				}
			}
		});
	}
})(jQuery);
