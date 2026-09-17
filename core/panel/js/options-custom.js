/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 *
 * 0.6.0 方案B（体验重构）：设置项搜索（B1）、Tab 键盘导航（B2）、
 * 分组折叠（B3）、后台暗色适配（B4）、保存 Toast（B5）。
 */

jQuery(document).ready(function($) {

	// Loads the color pickers
	$('.of-color').wpColorPicker();

	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	// Loads tabbed sections if they exist
	if ( $('.nav-tab-wrapper').length > 0 ) {
		options_framework_tabs();
	}

	// ===== B4 暗色适配：检测 WP 后台暗色（多信号，兼容 6.4+ 与常见暗色方案） =====
	function fuwari_is_dark() {
		var bodyClasses = document.body.className || '';
		if ( bodyClasses.indexOf('wp-dark-mode') !== -1 ||
		     bodyClasses.indexOf('is-dark-theme') !== -1 ||
		     bodyClasses.indexOf('wp-admin-dark') !== -1 ||
		     bodyClasses.indexOf('dark-theme') !== -1 ) {
			return true;
		}
		try {
			if ( window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ) {
				return true;
			}
		} catch ( e ) {}
		return false;
	}
	if ( fuwari_is_dark() ) {
		$('#optionsframework-wrap').addClass('fuwari-dark');
	}

	// ===== B5 保存/重置 Toast =====
	function fuwari_toast( msg, type ) {
		var $toast = $('#fuwari-toast'),
			icon = 'dashicons-update';
		if ( type === 'success' ) { icon = 'dashicons-yes-alt'; }
		else if ( type === 'error' ) { icon = 'dashicons-warning'; }
		if ( $toast.length === 0 ) {
			$toast = $('<div id="fuwari-toast" role="status"></div>').appendTo('body');
		}
		$toast.attr('class', type || 'info')
		      .html('<span class="dashicons ' + icon + '"></span>' + msg)
		      .addClass('show');
		clearTimeout( fuwari_toast._timer );
		fuwari_toast._timer = setTimeout( function() {
			$toast.removeClass('show');
		}, 2600 );
	}

	var $optionsForm = $('#optionsframework form');
	if ( $optionsForm.length ) {
		$optionsForm.on('submit', function() {
			var isReset = ( $('input[type="submit"]:focus', this).attr('name') === 'reset' );
			try {
				localStorage.setItem('fuwari_save_pending', isReset ? 'reset' : 'save');
			} catch ( e ) {}
			fuwari_toast( isReset ? '正在重置设置…' : '正在保存设置…', 'info' );
		});
	}
	(function fuwari_check_saved() {
		var pending = null;
		try { pending = localStorage.getItem('fuwari_save_pending'); } catch ( e ) {}
		if ( ! pending ) { return; }
		try { localStorage.removeItem('fuwari_save_pending'); } catch ( e ) {}
		var $notice = $('#setting-error-save_options');
		if ( pending === 'reset' ) {
			fuwari_toast( '设置已重置', 'success' );
		} else if ( $notice.length && ( $notice.hasClass('updated') || $notice.hasClass('notice-success') ) ) {
			fuwari_toast( '保存成功', 'success' );
		} else if ( $notice.length ) {
			fuwari_toast( '保存失败，请检查设置项', 'error' );
		} else {
			fuwari_toast( '设置已保存', 'success' );
		}
	})();

	// ===== B3 分组折叠（记忆到 localStorage，默认展开） =====
	function fuwari_bind_group_toggle() {
		$('.fuwari-group-toggle').off('click.fuwari').on('click.fuwari', function(e) {
			e.preventDefault();
			var $btn = $(this),
				$first = $btn.closest('[data-fuwari-group]'),
				gid = $first.attr('data-fuwari-group');
			if ( ! gid ) { return; }
			var collapsed = ! $first.hasClass('fuwari-group-collapsed');
			$first.toggleClass('fuwari-group-collapsed', collapsed);
			$('[data-fuwari-group="' + gid + '"]').not($first).toggle( ! collapsed );
			$btn.attr('aria-expanded', collapsed ? 'false' : 'true');
			try { localStorage.setItem('fuwari_group_collapsed_' + gid, collapsed ? '1' : '0'); } catch ( err ) {}
		});
		// 恢复记忆的折叠状态
		$('[data-fuwari-group]').each(function() {
			var $el = $(this),
				gid = $el.attr('data-fuwari-group');
			// 只处理每组首项（同组元素中的第一个）
			if ( ! $('[data-fuwari-group="' + gid + '"]').first().is($el) ) { return; }
			var val = null;
			try { val = localStorage.getItem('fuwari_group_collapsed_' + gid); } catch ( err ) {}
			if ( val === '1' ) {
				$el.addClass('fuwari-group-collapsed');
				$('[data-fuwari-group="' + gid + '"]').not($el).hide();
				$el.find('.fuwari-group-toggle').attr('aria-expanded', 'false');
			}
		});
	}
	fuwari_bind_group_toggle();

	// ===== B1 设置项搜索（过滤当前 tab，匹配项跨 tab 自动切换） =====
	var $searchInput = $('#fuwari-options-search-input'),
		$searchCount = $('#fuwari-search-count');

	function fuwari_section_hay( $s ) {
		return ( $s.find('.heading').text() + ' ' +
		       $s.find('.explain').text() + ' ' +
		       $s.find('label').text() + ' ' +
		       $s.find('input[type="text"],input[type="search"],textarea,select').attr('placeholder') + ' ' +
		       $s.find('input,select,textarea').attr('id') ).toLowerCase();
	}

	function fuwari_apply_search() {
		var q = ( $searchInput.val() || '' ).trim().toLowerCase();
		if ( q === '' ) {
			// 恢复：当前 tab 全部显示，并恢复分组折叠记忆
			$('.group:visible .section').show();
			// 0.8.0-beta.5：分组内设置项渲染为 .fuwari_group_opened（非 .section），清空搜索时一并恢复
			$('.group:visible .fuwari_group_opened').show();
			$('[data-fuwari-group].fuwari-group-collapsed').each(function() {
				var gid = $(this).attr('data-fuwari-group');
				$('[data-fuwari-group="' + gid + '"]').not(this).hide();
			});
			$searchCount.hide().text('');
			return;
		}
		var $activeGroup = $('.group:visible'),
			total = 0,
			visible = 0;
		// 第一遍：过滤叶子设置项（分组容器自身不带搜索文本，且其显隐由后代命中决定）
		$activeGroup.find('.section').filter(function() {
			return ! $(this).find('.section').length;
		}).each(function() {
			var $s = $(this);
			total++;
			var hit = ( fuwari_section_hay( $s ).indexOf( q ) !== -1 );
			$s.toggle( hit );
			if ( hit ) {
				visible++;
				// 命中项所在的折叠分组容器一并展开显示，避免结果被隐藏的父容器吞掉
				$s.closest('[data-fuwari-group]').show();
			}
		});
		// 0.8.0-beta.5：分组内设置项（.fuwari_group_opened，无 .section class）同样参与过滤与计数
		$activeGroup.find('.fuwari_group_opened').each(function() {
			var $s = $(this);
			total++;
			var hit = ( fuwari_section_hay( $s ).indexOf( q ) !== -1 );
			$s.toggle( hit );
			if ( hit ) {
				visible++;
				// 命中项所在分组容器展开显示
				$s.closest('.section[data-fuwari-group]').show();
			}
		});
		// 第二遍：无任何命中项的分组容器隐藏（有命中的保持显示）
		$activeGroup.find('.section[data-fuwari-group]').each(function() {
			var $g = $(this),
				any = false;
			$g.find('.section, .fuwari_group_opened').each(function() {
				if ( $(this).is(':visible') ) { any = true; }
			});
			$g.toggle( any );
		});
		if ( visible === 0 ) {
			// 当前 tab 无匹配 → 自动切换到第一个有匹配的 tab
			var switched = false,
				// 0.8.0-beta.5：防递归——切换后若目标 tab 仍无匹配（理论上不出现），不再重复切回同一 tab
				tried = {};
			$('.group').each(function() {
				if ( switched ) { return; }
				var any = false;
				$(this).find('.section').filter(function() {
					return ! $(this).find('.section').length;
				}).add( $(this).find('.fuwari_group_opened') ).each(function() {
					if ( fuwari_section_hay( $(this) ).indexOf( q ) !== -1 ) { any = true; }
				});
				var href = '#' + this.id;
				if ( any && ! $(this).is(':visible') && ! tried[ href ] ) {
					// 搜索内部自动切换：保留关键词，直接激活目标 tab 并继续过滤
					tried[ href ] = true;
					fuwari_activate_tab( href, true );
					switched = true;
				}
			});
			// 0.8.0-beta.5：跨 tab 切换成功后（递归过滤已更新计数），不再执行空态提示覆盖
			if ( ! switched ) {
				// 全部 tab 都无匹配 → 空态提示
				$searchCount.show().text('无匹配设置项');
			}
		} else {
			$searchCount.show().text('匹配 ' + visible + ' / ' + total + ' 项');
		}
	}

	if ( $searchInput.length ) {
		$searchInput.on('input', function() { fuwari_apply_search(); });
	}

	// 0.6.0 方案B + 0.8.0-beta.5：Tab 激活统一入口（供点击 / 键盘 / 搜索切换复用）。
	// 0.8.0-beta.5：从 options_framework_tabs() 内提升到 ready 顶层——原嵌套定义作用域在
	// options_framework_tabs() 局部，跨 tab 搜索调用 fuwari_apply_search() → fuwari_activate_tab()
	// 时 ReferenceError，导致搜索跨 tab 自动切换崩溃。$group 局部依赖改为 $('.group')。
	// keepSearch=true 为搜索内部跨 tab 自动切换（保留关键词继续过滤）；
	// 用户手动点击/键盘切换时清空搜索，恢复当前 tab 完整显示，避免"点菜单后被过滤成空白/弹回原 tab"。
	function fuwari_activate_tab( href, keepSearch ) {
		$('.nav-tab-wrapper li').removeClass('active');
		$('.nav-tab-wrapper li a[href="' + href + '"]').parent('li').addClass('active');
		if ( typeof(localStorage) != 'undefined' ) {
			localStorage.setItem('active_tab', href );
		}
		$('.group').hide();
		$(href).fadeIn();
		if ( ! keepSearch && $searchInput.length && ( $searchInput.val() || '' ).trim() !== '' ) {
			// 手动切换：清空搜索词并恢复完整显示
			$searchInput.val('');
			fuwari_apply_search();
			return;
		}
		// 搜索状态下（含搜索内部跨 tab 切换），切换后重新过滤
		if ( $searchInput.length && ( $searchInput.val() || '' ).trim() !== '' ) {
			fuwari_apply_search();
		}
	}

	function options_framework_tabs() {

		var $group = $('.group'),
			$navtabs = $('.nav-tab-wrapper li a'),
			active_tab = '';

		// Hides all the .group sections to start
		$group.hide();
		$('.nav-tab-wrapper li').removeClass('active');

		// Find if a selected tab is saved in localStorage
		if ( typeof(localStorage) != 'undefined' ) {
			active_tab = localStorage.getItem('active_tab');
		}

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $(active_tab).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').parent('li').addClass('active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper li:first').addClass('active');
		}

		// Bind tabs clicks
		$navtabs.on('click', function(e) {
			e.preventDefault();
			$(this).blur();
			fuwari_activate_tab( $(this).attr('href') );
		});

		// ===== B2 Tab 键盘导航（↑ ↓ ← → 切换，首尾循环） =====
		$navtabs.on('keydown', function(e) {
			var k = e.keyCode;
			if ( k === 38 || k === 40 || k === 37 || k === 39 ) {
				var idx = $navtabs.index( this ),
					next = ( k === 40 || k === 39 ) ? ( idx + 1 ) % $navtabs.length : ( idx - 1 + $navtabs.length ) % $navtabs.length;
				e.preventDefault();
				$navtabs.eq( next ).focus().trigger('click');
			}
		});
	}

});
