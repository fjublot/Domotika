$(function() {
	// Reinit body
	$('body').on('click', function(e) {
		// Remove tooltips
		if ($('.tooltip').length) {
			$('.tooltip').remove();
		}
		// Remove dropdowns
		console.log(e.target.className);
		if (e.target.className !== "dropdown-table" && e.target.className !== "btn colVar active-dropdown"
			&& e.target.className !== "btn colParamCode active-dropdown") {
			$('body > .dropdown-table').remove();
			$('.colParamCode, .colVar').removeClass('active-dropdown');
		}
	});

	// Change theme
	$('.icon-2create1').on('click', function() {
		$(this).hide();
		$('#change-theme').show();
	});
	$('#change-theme').on('hover', function() {
		$(this).children('ul').show();
	}, function() {
		$(this).children('ul').hide();
	});
	$('#change-theme .dropdown-menu li').on('click', function() {
		var theme = $(this).prop('id');
		var themeUri = "css/themes/" + theme + ".css";
		// Set the new css link
		$('#theme_css').prop('href', themeUri);
		// Add theme class to body
		$('body').removeClass('default-theme alternate-theme-1 alternate-theme-2').addClass(theme);
		if ($('body').attr('class') === 'alternate-theme-1') {
			// Update donut colors for theme alternate 1
			$('.donut-chart').each(function() {
				$(this).data('easyPieChart').update($(this).attr('data-percent'));
				// $(this).attr('data-trackcolor', '#a7ac27');
				// $(this).data('easyPieChart').options.trackColor = '#a7ac27';
				$(this).data('easyPieChart').options.barColor = '#a7ac27';
			});
		} else {
			$('.donut-chart:eq(0)').each(function() {
				$(this).data('easyPieChart').update($(this).attr('data-percent'));
				$(this).data('easyPieChart').options.barColor = '#8c57ae';
			});
			$('.donut-chart:eq(1)').each(function() {
				$(this).data('easyPieChart').update($(this).attr('data-percent'));
				$(this).data('easyPieChart').options.barColor = '#c62751';
			});
		}
		// Reinit buttons
		$('.icon-2create1').show();
		$('#change-theme').hide();
	});

	// Manage the dropdown select tags
	$('#change-theme, .navbar-right .dropdown').hover(function() {
		$(this).find('.dropdown-menu').show();
	}, function() {
		$(this).find('.dropdown-menu').hide();
	}).on('click', function() {
		$(this).find('.dropdown-menu').toggle();
	});
	// Generate table dropdowns
	generateDropdowns();
	$('.navbar-right .dropdown-select li').on('click', function() {
		var dropdownBtn = $(this).parents(".dropdown").find('.btn');
		// dropdownBtn.find('span.caret').length ? dropdownBtn.html($(this).text() + '&nbsp;<span class="caret"></span>') : dropdownBtn.html($(this).text());
		if (dropdownBtn.find('span.caret').length) {
			dropdownBtn.html($(this).html() + '&nbsp;<span class="caret"></span>');
		} else {
			dropdownBtn.html($(this).text());
		}
		dropdownBtn.val($(this).text());
	});
	// Remove dropdown on scroll & resize events
	$(document).on('scroll resize', function() {
		// Remove tooltips
		$('.tooltip').remove();
		$('body > .dropdown-table').remove();
		$('.widget-table .dropdown-select .btn').removeClass('active-dropdown');
	});
	$('.widget-table .widget-content').on('scroll', function() {
		// Remove tooltips
		$('.tooltip').remove();
		$('body > .dropdown-table').remove();
		$('.widget-table .dropdown-select .btn').removeClass('active-dropdown');
	});

	// Scroll to top
	$("#back-to-top").hide();
	$(function () {
		$(window).scroll(function() {
			if ($(window).scrollTop()>100) {
				$("#back-to-top").fadeIn(1000);
			} else {
				$("#back-to-top").fadeOut(1000);
			}
		});
	    //back to top
	    $("#back-to-top").click(function() {
	    	$('body, html').animate({scrollTop:0}, 1000);
	    	return false;
	    });
	});

	// Manage the refresh animation
	animeRefresh();

	// Manage the focus event on sidebar
	// $('.nav-sidebar .submenu a, .nav-sidebar .submenu div').on('click', function() {
	// 	$('.submenu a, .submenu div').removeClass('submenu-focus');
	// 	var menu = $(this).parents('ul').prev();
	// 	var menuName = menu.attr('data-submenu-name');
	// 	menu.addClass('submenu-focus');
	// 	$(this).addClass('submenu-focus');
	// 	$('.nav-sidebar a[data-submenu-name=' + menuName + '], .nav-sidebar div[data-submenu-name=' + menuName + ']').addClass('submenu-focus');
	// });

	// Manage subnav of the opened sidebar
	$(".sidebar-opened .submenu div, .sidebar-opened .submenu a").on('click', function() {
		if ($(this).next('ul').css('display') === "none") {
			$(this).next('ul').show();
			$(this).children('.icon-collapsedown').addClass('icon-collapseup').removeClass('icon-collapsedown');
		} else {
			$(this).next('ul').hide();
			$(this).children('.icon-collapseup').addClass('icon-collapsedown').removeClass('icon-collapseup');
		}
	});

	// Add tooltip attributes & initialize
	addTooltips();

	// Set the min-width of the main content
	if (window.screen.width >= 1200 - 17) {
		$('.main').css('min-width', parseInt(window.screen.width) - 60);
	}
	$(window).on('resize', function() {
		// fontResize($('.widget-info h3'), 24);
		if ($(window).width() >= 1200 - 17) {
			$('.main').css('min-width', parseInt(window.screen.width) - 60);
		} else {
			$('.main').css('min-width', 0);
		}
	});

	// Collapse or open the widget
	$('.widget-header .icon-collapseup').on('click', function() {
		collapseWidget($(this).parents('.widget'), "collapse");
	});
	$('.widget-header .icon-collapsedown').on('click', function() {
		collapseWidget($(this).parents('.widget'), "open");
	});

	// Close widget & resize screen
	$('.widgets .icon-cross').on('click', function() {
		closeAndResizeWidgets($(this).parents('.widget'));
	});

	// Maximize the widget
	$('.icon-maximize:not(.chart)').on('click', function() {
		maximizeWidget($(this).parents('.widget'));
	});
	// Maximize the chart
	$('.icon-maximize.chart').on('click', function() {
		maximizeChart($(this).parents('.chart-container'));
	});

	// Harmonize the height of the widgets of the same row
	// $('.row.widgets').each(function() {
	// 	// Get the widgets of the current row
	// 	var widgetsRow = $(this).find('.widget');
	// 	// Get the larger height of the current widgets
	// 	var largerHeight = getLargerWidgetHeight(widgetsRow);
	// 	// Set the height of all widgets of the current row to the larger
	// 	widgetsRow.css('height', largerHeight);
	// });

	// Set the background images of the info widgets
	$('.widget-info:eq(1)').css({'background-image' : 'url("img/safran_sailing_team_1.jpg")',
		'background-size' : "100%"});
	$('.widget-info:eq(2)').css({'background-image' : 'url("img/safran_sailing_team_2.jpg")',
		'background-size' : "100%"});

	// Manage the drag & drop functionnality for widgets
	$( ".widgets" ).sortable({
      connectWith: ".widgets",
      handle: ".widget-header"
    });

    // Manage dropdown popover
    initializePopovers();

    // test Highcharts
    $('.symptomChart, .signatureChart').each(function() {
    	chartExample($(this));
	});

	// test Switchery
	initializeSwitcheries('');

	// test Donut chart
	/*
	$('.donut-chart:eq(0)').easyPieChart({
		animate: 1000,
		barColor: '#8c57ae',
		trackColor: '#eee',
		scaleColor: false,
		lineWidth: 5,
		lineCap: 'square'
	});
	$('.donut-chart:eq(1)').easyPieChart({
		animate: 1000,
		barColor: '#c62751',
		trackColor: '#eee',
		scaleColor: false,
		lineWidth: 5,
		lineCap: 'square'
	});
	$('.donut-chart:eq(2)').easyPieChart({
		animate: 1000,
		barColor: '#a7ac27',
		trackColor: '#eee',
		scaleColor: false,
		lineWidth: 5,
		lineCap: 'square'
	});
	// Donut color
	// var ctx = $('.donut-chart canvas')[0].getContext('2d');
	// ctx.strokeStyle = "#000";

	// Percent animation
	$('.donut-chart').each(function() {
		var interval = 1000 / $(this).attr('data-percent');
		animePercent($(this), interval);
	});
*/
	// Manage resize widget
	resizeWidget();

	// test DataTables
	/*
	$('.table-recommandation').DataTable();
	$('.dataTables_wrapper table').wrap('<div class="datatable"></div>');
	*/
	});