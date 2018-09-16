var moving = new Array();
moving['start'] = false;
moving['stop'] = false;

function startmove(scroller, event)
{
	moving[scroller] = true;
	startX = event.clientX;
}

function posmax()
{
	return document.getElementById('scrollstop').offsetLeft + document.getElementById('scrollstop').clientWidth - document.getElementById('barre').offsetLeft;
}

function posmin()
{
	return document.getElementById('scrollstart').offsetLeft - document.getElementById('barre').offsetLeft;
}

function scrollmax()
{
	return document.getElementById('barre').offsetLeft + document.getElementById('barre').clientWidth;
}

function scrollmin()
{
	return document.getElementById('barre').offsetLeft;
}

function updategraph()
{
	document.getElementById('graph').src = document.getElementById('url_graph').value + '&startpourcent=' + document.getElementById('startpourcent').value + '&stoppourcent=' + document.getElementById('stoppourcent').value;
}

function calc_zoom()
{
	document.getElementById('startpourcent').value = Math.ceil(posmin() / (scrollmax()-scrollmin()) *100);
	document.getElementById('stoppourcent').value = Math.ceil(posmax() / (scrollmax()-scrollmin()) *100);
	document.getElementById('min').value = scrollmin();
	document.getElementById('max').value = scrollmax();
	document.getElementById('start').value = posmin();
	document.getElementById('stop').value = posmax();
	updategraph();
}

function stopmove(scroller, event)
{
	calc_zoom();
	moving[scroller] = false;
}

function move(scroller, event)
{
	if ( moving[scroller] == true )
	{
		Where = event.clientX - document.getElementById('scroll'+scroller).clientWidth/2;
		if ( scroller == 'start' )
		{
			minimum = scrollmin();
			maximum = document.getElementById('scrollstop').offsetLeft - document.getElementById('scrollstart').clientWidth;
		}
		else
		{
			minimum = document.getElementById('scrollstart').offsetLeft + document.getElementById('scrollstart').clientWidth;
			maximum = scrollmax() - document.getElementById('scrollstop').clientWidth;
		}
		if ( Where >= minimum && Where <= maximum )
			document.getElementById('scroll'+scroller).style.left = Where;
		startX = event.clientX;
	}
}

function init()
{
	moving['start'] = false;
	moving['stop'] = false;
	document.getElementById('graph').onload = "";
	document.getElementById('scrollstart').style.left = scrollmin();
	document.getElementById('scrollstop').style.left = scrollmax() - document.getElementById('scrollstop').clientWidth;
	document.getElementById('scrollstart').style.top = document.getElementById('barre').offsetTop - (document.getElementById('scrollstart').clientHeight - document.getElementById('barre').clientHeight) /2;
	document.getElementById('scrollstop').style.top = document.getElementById('barre').offsetTop - (document.getElementById('scrollstop').clientHeight - document.getElementById('barre').clientHeight) /2;
}