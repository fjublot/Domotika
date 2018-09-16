// Check if the first element of the selector is shown.
var isVisible = function(sel) {
	if($(sel).first().css('display') === "none") {
		return false;
	} else {
		return true;
	}
};

// Font resize
var fontResize = function(elt, initialSize) {
    //Set default resolution and font size
    var resolution = window.screen.width;
    var font = initialSize;

    //Get window width
    var width = $(window).width();
    console.log(resolution);
    elt.each(function() {
    	console.log("START");
		// font = parseInt($(this).css('font-size'));
		//Set new font size
		var newFont = font * (width/resolution);
		// if (newFont < 10) {
		// 	newFont = 10;
		// }
		console.log(newFont);
		$(this).css('font-size', newFont);
    });
    console.log("END");
    // $('body').css('font-size', newFont);
};

// Fix dropdown position
var dropdownFixPosition = function(button, dropdown) {
	var dropdownTop = button.offset().top + button.outerHeight();
	var dropdownClone = dropdown.clone();
	$('body').append(dropdownClone);
	dropdownClone.css({'top': dropdownTop, 'left': button.offset().left});
	dropdownClone.addClass('dropdown-table').show();
	button.addClass('active-dropdown');
	dropdownClone.children('li').on('click', function() {
		// Remove tooltips
		$('.tooltip').remove();
		button.html($(this).html() + '&nbsp;<span class="caret"></span>');
		button.val($(this).text());
		dropdownClone.remove();
	});
	addTooltips();
}

// Toggle the display of the sidebars.
var toggleSidebar = function() {
	// Show opened sidebar & hide closed sidebar, or vice versa.
	$('.sidebar').toggle();
	// Get the sidebar shown.
	var sidebar = $('.sidebar').filter(function() {
		return isVisible(this);
	});
	// Set the left margin of the main content to the width of the sidebar.
	$('.main').css('left', sidebar.css('width'));
};

// Collapse widget.
var collapseWidget = function(widgetObject, mode) {
	if (mode === "collapse") {
		// Hide the widget content
		$(widgetObject).find('.widget-content, .widget-footer').hide();
		// Show the widget header only
		widgetObject.css('height', $(widgetObject).find('.widget-header').height());
		// Change collapse icon to open
		widgetObject.find('.widget-header .icon-collapseup').hide();
		widgetObject.find('.widget-header .icon-collapsedown').show();
		// Hide other icons
		widgetObject.find('.widget-header .icon-refresh1, .widget-header .icon-maximize').hide();
	} else if (mode === "open") {
		// Show the widget content
		$(widgetObject).find('.widget-content, .widget-footer').show();
		// Show the entire widget
		widgetObject.css('height', $(widgetObject).height());
		// Change open icon to collapse
		widgetObject.find('.widget-header .icon-collapsedown').hide();
		widgetObject.find('.widget-header .icon-collapseup').show();
		// Show other icons
		widgetObject.find('.widget-header .icon-refresh1, .widget-header .icon-maximize').show();
	} else {
		console.error("The mode parameter is not set.");
	}
};

// Close widget
var closeWidget = function(widget) {
	// Remove tooltips
	$('.tooltip').remove();
	// Remove dropdowns
	$('.dropdown-table').remove();
	// Remove the overlay
	widget.prev('.overlay').remove();
	// Remove the widget
	widget.remove();
	// Generate dropdowns
	generateDropdowns();
};

// Close & resize the widgets of a row.
var closeAndResizeWidgets = function(widget) {
	// Get the other widgets of the row
	var otherWidgets = widget.siblings();
	// Close widget & overlay
	closeWidget(widget);
	// Resize the other widgets to fit the screen
	otherWidgets.each(function() {
		// Get the Bootstrap size of the current widget
		var sizeClass = $(this).prop('class').match(/col-sm-[\w-]*\b/)[0];
		var size = parseInt(sizeClass.split(/-/).pop());
		// Remove old Bootstrap size
		$(this).removeClass(sizeClass);
		// Set the Bootstrap size of the current widget according its original size
		if (size == 3) {
			// If widget size 3, it become 4
			$(this).addClass('col-sm-4');
		} else if (size == 6) {
			// If widget size 6, it become 8
			$(this).addClass('col-sm-8');
		} else if (size == 9) {
			// If widget size 9, it become 12
			$(this).addClass('col-sm-12');
		} else {
			// Initial size
			$(this).addClass('col-sm-' + size);
		}
	});
};

// Add tooltips
var addTooltips = function() {
	$('.icon-2create1').attr({'data-toggle': "tooltip", 'data-placement': "bottom", 'title': "Change theme", 'data-container': "body"});
	$('.widget .icon-collapseup').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Collapse up", 'data-container': "body"});
	$('.widget .icon-collapsedown').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Collapse down", 'data-container': "body"});
	$('.widget .icon-maximize').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Maximize", 'data-container': "body"});
	$('.widget .icon-refresh1').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Refresh", 'data-container': "body"});
	$('.widget .icon-cross').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Close", 'data-container': "body"});
	$('.widget .icon-2submit').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Submit", 'data-container': "body"});

	$('.icon-bigupbold').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Up", 'data-container': "body"});
	$('.icon-bigdoublebold').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Flat", 'data-container': "body"});
	$('.icon-bigdownbold').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Down", 'data-container': "body"});

	$('.widget .icon-trash').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Delete", 'data-container': "body"});
	$('.widget .icon-edit1').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "Edit", 'data-container': "body"});
	$('.widget .icon-new').attr({'data-toggle': "tooltip", 'data-placement': "left", 'title': "New", 'data-container': "body"});
	// Initialize tooltips
	$('[data-toggle="tooltip"]').tooltip();
}

// Maximize the widget.
var maximizeWidget = function(widget) {
	// Clone the widget
	var maximizedWidget = widget.clone();
	// Add class
	maximizedWidget.addClass('widgets widget-maximized');
	// Change the fullscreen button to close button and set the click event of the close button
	maximizedWidget.find('.icon-maximize').removeClass('icon-maximize').addClass('icon-cross')
		.on('click', function() {
			closeWidget($(this).parents('.widget'));
		});
	// Remove collapse & open buttons
	maximizedWidget.find('.icon-collapseup, .icon-collapsedown').remove();
	
	// Remove the move cursor from the header of the fullscreen widget
	maximizedWidget.find('.widget-header').removeClass('ui-sortable-handle');

	// Add the cloned widget onto the main content
	maximizedWidget.prependTo(widget.parents('.main')).css({'position' : 'absolute', 'z-index' : 10, 'margin-left' : "15px", 'margin-bottom' : 0,
		'width': $('.main').width(), 'height' : $(window).height()});
	// Set the height of the widget content (100% - 40px header - 45px footer)
	var widgetContentHeight = parseInt(maximizedWidget.height()) - 85;
	maximizedWidget.find('.widget-content').height(widgetContentHeight);
	// Add overlay onto the main content
	$("<div>", {class: 'overlay'}).prependTo(widget.parents('.main')).css({'position': 'absolute', 'z-index': 10,
		'width': parseInt($('.main').outerWidth()) + 10, 'height': $('.main').outerHeight(),
		'margin-left': 0, 'margin-bottom': 0, 'left': "-10px"}).on('click', function() {
			closeWidget(maximizedWidget);
		});
	// REINIT FOR MAXIMIZED WIDGET
	// Initialize the tooltips
	$('.widget-maximized .tooltip').remove();
	addTooltips();
	$('[data-toggle="tooltip"]').tooltip();
	// Initialize the popovers
	$('.widget-maximized .popover').remove();
	initializePopovers();
	// test Switchery
	$('.widget-maximized .switchery').remove();
	initializeSwitcheries('.widget-maximized');
	// refresh animation
	animeRefresh();
	// dropdowns
	generateDropdowns();
};

// Chart example
var chartExample = function(chartContainer) {
	$(chartContainer).highcharts({
		chart: {
			type: 'spline'
		},
		title: {
			text: null
		},
		yAxis: {
			title: null
		},
		legend: {
			enabled: false
		},
		series: [{
			name: 'SP01',
			color: '#00a6e3',
			data: [[1, 100], [2, 40], [3, 25], [3.7, 50], [4, 87], [5, 95], [6, 47], [7, 51], [7.2, 100]]
		}, {
			name: 'SP02',
			color: '#c62751',
			data: [[1, -15], [2, 5], [3, 52], [3.7, -1], [4, -38], [5, 0], [5.7, -2], [6, 47], [7, 70], [7.3, 50]]
		}]
	});
};

// Maximize the chart.
var maximizeChart = function(chart) {
	// Create the fullscreen chart
	var maximizedChart = $("<div>", {id: "fullscreen-chart", class: "col-sm-12 widget widget-chart"});
	// Set the height of the chart content
	maximizedChart.height($(window).height());

	// Add the cloned chart onto the main content
	maximizedChart.prependTo(chart.parents('.main')).css({'position' : 'absolute', 'z-index' : 10, 'margin-left' : "15px", 'padding': 0, 'margin-bottom' : 0,
		'width': $('.main').width(), 'height': $(window).height()});
	// Add overlay onto the main content
	$("<div class='overlay'></div>").prependTo(chart.parents('.main')).css({'position': 'absolute', 'z-index': 10,
		'width': parseInt($('.main').outerWidth()) + 10, 'height': $('.main').outerHeight(),
		'margin-left': 0, 'margin-bottom': 0, 'left': "-10px"}).on('click', function() {
			closeWidget(maximizedChart);
		});

	// Create the fullscreen chart
	chartExample(maximizedChart);
	// Create the close button
	maximizedChart.append($("<span>", {class: 'icon-cross'}));
	// Set the click event of the close button
	maximizedChart.find('.icon-cross').on('click', function() {
		closeWidget($(this).parents('#fullscreen-chart'));
	});
	// REINIT FOR MAXIMIZED WIDGET
	// Initialize the tooltips
	$('.widget-maximized .tooltip').remove();
	addTooltips();
	$('[data-toggle="tooltip"]').tooltip();
};

// Get the height of the larger widget.
var getLargerWidgetHeight = function(widgets) {
	var largerHeight = 0;
	widgets.each(function() {
		if (parseInt($(this).outerHeight()) > largerHeight) {
			// If the height of the current widget is larger
			largerHeight = $(this).outerHeight();
		}
	});
	return largerHeight;
};

// Test popover examples
var popoverSelectVarExamples = function(elt) {
	elt.popover({
		trigger: 'focus',
		placement: 'right',
		html: true,
		content: '<ul class="popover-dropdown">\
			<li onclick="popoverSelect($(this));">\
    		<span class="icon-bigupbold red-bg"></span></li>\
    		<li onclick="popoverSelect($(this));">\
    		<span class="icon-bigdoublebold green-bg"></span></li>\
    		<li onclick="popoverSelect($(this));">\
    		<span class="icon-bigdownbold blue-bg"></span></li>\
		</ul>'
	});
}
var popoverSelectParamCodeExamples = function(elt) {
	elt.popover({
		trigger: 'focus',
		placement: 'right',
		html: true,
		content: '<ul class="popover-dropdown"><li onclick="popoverSelect($(this));">\
    		EGT CRUISE\
    		</li>\
    		<li onclick="popoverSelect($(this));">\
    		Lorem ipsum\
		</li></ul>'
	});
}
// Set click event in popover
var popoverSelect = function(elt) {
	var html = elt.html() + '<span class="caret"></span>';
	elt.parents('.popover').prev('.btn-popover').html(html);
}
// Initialize popovers
var initializePopovers = function() {
	// Init popover content
    $(".colVar[data-toggle=popover]").each(function() {
    	popoverSelectVarExamples($(this));
    });
    $(".colParamCode[data-toggle=popover]").each(function() {
    	popoverSelectParamCodeExamples($(this));
    });
};

// Initialize switcheries
var initializeSwitcheries = function(sel) {
	var switcheries = Array.prototype.slice.call(document.querySelectorAll(sel + ' .js-switch'));
	switcheries.forEach(function(elt) {
		new Switchery(elt, { color: '#41b7f1' });
	});
};

// Manage donut percentage animation
var animePercent = function(elt, interval) {
	var donut = elt, i = 0, percent = donut.attr('data-percent');
	donut.children('.donut-text').text(i + "%");
	var timer = setInterval(function() {
		var percentList = [];
		for (var j = 1 ; j <= percent ; j++) {
			percentList.push(j);
		}
		donut.children('.donut-text').text(percentList[i] + "%");
		i++;
		if (i >= percentList.length) {
			clearInterval(timer);
		}
	}, interval);
};

// Manage the refresh animation
var animeRefresh = function() {
	$('.icon-refresh1').on("click", function() {
		var refresh = $(this), animateClass = "icon-refresh-animate";
		refresh.addClass(animateClass);
		var donut = $(this).parents('.widget-alert').find('.donut-chart');
		if (donut.length) {
			var percent = donut.attr('data-percent');
			// Reanime percent
			animePercent(donut, 1000 / percent);
			// Reinit & reanime donut
			donut.data('easyPieChart').update(0);
			donut.data('easyPieChart').update(percent);
		}
		// setTimeout is to indicate some async operation
		window.setTimeout(function() {
			refresh.removeClass(animateClass);
		}, 1400);
	});
};

// Resize widget
var resizeWidget = function() {
	$('.icon-up').on("click", function() {
		var widget = $(this).parents('.widget');
		// Get the Bootstrap size of the current widget
		var sizeClass = widget.prop('class').match(/col-sm-[\w-]*\b/)[0];
		var size = parseInt(sizeClass.split(/-/).pop()) + 1;
		if (size > 12) {
			size = 12;
		}
		// Remove old Bootstrap size
		widget.removeClass(sizeClass);
		// Add new Bootstrap size
		widget.addClass("col-sm-" + size);
	});
	$('.icon-down').on("click", function() {
		var widget = $(this).parents('.widget');
		// Get the Bootstrap size of the current widget
		var sizeClass = widget.prop('class').match(/col-sm-[\w-]*\b/)[0];
		var size = parseInt(sizeClass.split(/-/).pop()) - 1;
		if (size < 3) {
			size = 3;
		}
		// Remove old Bootstrap size
		widget.removeClass(sizeClass);
		// Add new Bootstrap size
		widget.addClass("col-sm-" + size);
	});
};

// Generate table dropdowns
var generateDropdowns = function() {
	$('.widget-table .dropdown-select .btn').on('click', function() {
		// Remove tooltips
		$('.tooltip').remove();
		var dropdownMenu = $(this).next('.dropdown-menu');
		dropdownMenu.hide();
		if ($(this).hasClass('active-dropdown')) {
			$('body > .dropdown-table').remove();
			$(this).removeClass('active-dropdown');
		} else {
			$('body > .dropdown-table').remove();
			dropdownFixPosition($(this), dropdownMenu);
		}
	});
};