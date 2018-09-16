<table id="filters" class="tablesorter">
	<thead>
		<tr><th>First Name</th><th>Last Name</th><th>City</th><th>Age</th><th>Total</th><th>Discount</th><th>Date</th></tr>
	</thead>
	<tbody>
		<tr><td>Aaron</td><td>Johnson Sr</td><td>Atlanta</td><td>35</td><td>$5.95</td><td>22%</td><td>Jun 26, 2004 7:22 AM</td></tr>
		<tr><td>Aaron</td><td>Johnson</td><td>Yuma</td><td>12</td><td>$2.99</td><td>5%</td><td>Aug 21, 2009 12:21 PM</td></tr>
		<tr><td>Clark</td><td>Henry Jr</td><td>Tampa</td><td>51</td><td>$42.29</td><td>18%</td><td>Oct 13, 2000 1:15 PM</td></tr>
		<tr><td>Denni</td><td>Henry</td><td>New York</td><td>28</td><td>$9.99</td><td>20%</td><td>Jul 6, 2006 8:14 AM</td></tr>
		<tr><td>John</td><td>Hood</td><td>Boston</td><td>33</td><td>$19.99</td><td>25%</td><td>Dec 10, 2002 5:14 AM</td></tr>
		<tr><td>Clark</td><td>Kent Sr</td><td>Los Angeles</td><td>18</td><td>$15.89</td><td>44%</td><td>Jan 12, 2003 11:14 AM</td></tr>
		<tr><td>Peter</td><td>Kent Esq</td><td>Seattle</td><td>45</td><td>$153.19</td><td>44%</td><td>Jan 18, 2021 9:12 AM</td></tr>
		<tr><td>Peter</td><td>Johns</td><td>Milwaukee</td><td>13</td><td>$5.29</td><td>4%</td><td>Jan 8, 2012 5:11 PM</td></tr>
		<tr><td>Aaron</td><td>Evan</td><td>Chicago</td><td>24</td><td>$14.19</td><td>14%</td><td>Jan 14, 2004 11:23 AM</td></tr>
		<tr><td>Bruce</td><td>Evans</td><td>Upland</td><td>22</td><td>$13.19</td><td>11%</td><td>Jan 18, 2007 9:12 AM</td></tr>
		<tr><td>Clark</td><td>McMasters</td><td>Pheonix</td><td>18</td><td>$55.20</td><td>15%</td><td>Feb 12, 2010 7:23 PM</td></tr>
		<tr><td>Dennis</td><td>Masters</td><td>Indianapolis</td><td>65</td><td>$123.00</td><td>32%</td><td>Jan 20, 2001 1:12 PM</td></tr>
		<tr><td>John</td><td>Hood</td><td>Fort Worth</td><td>25</td><td>$22.09</td><td>17%</td><td>Jun 11, 2011 10:55 AM</td></tr>
	</tbody>
</table>

	<script>

$(function() {

	$('#filters').tablesorter({
		theme: 'blue',
		widgets: ['zebra', 'filter'],
		widgetOptions: {
			filter_reset: '.reset'
		}
	});

	// External search
	// buttons set up like this:
	// <button type="button" class="search" data-filter-column="4" data-filter-text="2?%">Saved Search</button>
	$('button').click(function(){
		var $t = $(this),
			col = $t.data('filter-column'), // zero-based index
			filter = [];

		filter[col] = $t.text(); // text to add to filter
		$('#filters').trigger('search', [ filter ]);
		return false;
	});

});</script>



