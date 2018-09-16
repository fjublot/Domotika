	var dragDropTopContainer = false;
	var dragTimer = -1;
	var dragContentObj = false;
	var contentToBeDragged = false;	// Reference to dragged <li>
	var contentToBeDragged_src = false;	// Reference to parent of <li> before drag started
	var contentToBeDragged_next = false; 	// Reference to next sibling of <li> to be dragged
	var destinationObj = false;	// Reference to <UL> or <LI> where element is dropped.
	var dragDropIndicator = false;	// Reference to small arrow indicating where items will be dropped
	var ulPositionArray = new Array();
	var mouseoverObj = false;	// Reference to highlighted DIV

	var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
	var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;

	var arrow_offsetX = -5;	// Offset X - position of small arrow
	var arrow_offsetY = 0;	// Offset Y - position of small arrow

	if(!MSIE || navigatorVersion > 6){
		arrow_offsetX = -6;	// Firefox - offset X small arrow
		arrow_offsetY = -13; // Firefox - offset Y small arrow
	}

	var indicateDestinationBox = false;
	function getTopPos(inputObj)
	{
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}

	function getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
	  }
	  return returnValue;
	}

	function cancelEvent()
	{
		return false;
	}
	function initDrag(e)	// Mouse button is pressed down on a LI
	{
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);

		dragTimer = 0;
		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';
		contentToBeDragged = this;
		contentToBeDragged_src = this.parentNode;
		contentToBeDragged_next = false;
		if(this.nextSibling){
			contentToBeDragged_next = this.nextSibling;
			if(!this.tagName && contentToBeDragged_next.nextSibling)contentToBeDragged_next = contentToBeDragged_next.nextSibling;
		}
		timerDrag();
		return false;
	}

	function timerDrag()
	{
		if(dragTimer>=0 && dragTimer<10){
			dragTimer++;
			setTimeout('timerDrag()',10);
			return;
		}
		if(dragTimer==10){

			if(cloneSourceItems && contentToBeDragged.parentNode.id.substr(0,8)=='allItems'){
				newItem = contentToBeDragged.cloneNode(true);
				newItem.onmousedown = contentToBeDragged.onmousedown;
				contentToBeDragged = newItem;
			}
			dragContentObj.style.display='block';
			dragContentObj.appendChild(contentToBeDragged);
		}
	}

	function moveDragContent(e)
	{
		if(dragTimer<10){
			if(contentToBeDragged){
				if(contentToBeDragged_next){
					contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
				}else{
					contentToBeDragged_src.appendChild(contentToBeDragged);
				}
			}
			return;
		}
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);


		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';

		if(mouseoverObj)mouseoverObj.className='';
		destinationObj = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox)indicateDestinationBox.style.display='none';
		var x = e.clientX + sl;
		var y = e.clientY + st;
		var width = dragContentObj.offsetWidth;
		var height = dragContentObj.offsetHeight;

		var tmpOffsetX = arrow_offsetX;
		var tmpOffsetY = arrow_offsetY;

		for(var no=0;no<ulPositionArray.length;no++){
			var ul_leftPos = ulPositionArray[no]['left'];
			var ul_topPos = ulPositionArray[no]['top'];
			var ul_height = ulPositionArray[no]['height'];
			var ul_width = ulPositionArray[no]['width'];
//			ul_topPos = ul_topPos - document.body.scrollTop;
//			ul_leftPos = ul_leftPos - document.body.scrollLeft;
//			ul_topPos = ul_topPos - scrollTop();
			if((x+width) > ul_leftPos && x<(ul_leftPos + ul_width) && (y+height)> ul_topPos && y<(ul_topPos + ul_height)){
				var noExisting = ulPositionArray[no]['obj'].getElementsByTagName('LI').length;
				if(indicateDestinationBox && indicateDestinationBox.parentNode==ulPositionArray[no]['obj'])noExisting--;
				//if( ulPositionArray[no]['obj'].id.substr(0,8)!='allItems')
				{
					dragDropIndicator.style.left = ul_leftPos + tmpOffsetX + 'px';
					var subLi = ulPositionArray[no]['obj'].getElementsByTagName('LI');

					var clonedItemAllreadyAdded = false;
					if(cloneSourceItems && !cloneAllowDuplicates){
						for(var liIndex=0;liIndex<subLi.length;liIndex++){
							if(contentToBeDragged.id == subLi[liIndex].id)clonedItemAllreadyAdded = true;
						}
						if(clonedItemAllreadyAdded)continue;
					}

					for(var liIndex=0;liIndex<subLi.length;liIndex++){
						var tmpTop = getTopPos(subLi[liIndex]);
						var tmpHeight = getLeftPos(subLi[liIndex]);
						if(!indicateDestionationByUseOfArrow){
							if(x<tmpHeight+subLi[liIndex].offsetWidth){
								if(y<tmpTop+subLi[liIndex].offsetHeight){
									destinationObj = subLi[liIndex];
									indicateDestinationBox.style.display='block';
									subLi[liIndex].parentNode.insertBefore(indicateDestinationBox,subLi[liIndex]);
									break;
								}
							}
						}else{
							if(x<tmpHeight+subLi[liIndex].offsetWidth){
								if(y<tmpTop){
									destinationObj = subLi[liIndex];
									dragDropIndicator.style.top = tmpTop + tmpOffsetY - Math.round(dragDropIndicator.clientHeight/2) + 'px';
									dragDropIndicator.style.display='block';
									break;
								}
							}
						}
					}

					if(!indicateDestionationByUseOfArrow){
						if(indicateDestinationBox.style.display=='none'){
							indicateDestinationBox.style.display='block';
							ulPositionArray[no]['obj'].appendChild(indicateDestinationBox);
						}

					}else{
						if(subLi.length>0 && dragDropIndicator.style.display=='none'){
							dragDropIndicator.style.top = getTopPos(subLi[subLi.length-1]) + subLi[subLi.length-1].offsetHeight + tmpOffsetY + 'px';
							dragDropIndicator.style.display='block';
						}
						if(subLi.length==0){
							dragDropIndicator.style.top = ul_topPos + arrow_offsetY + 'px'
							dragDropIndicator.style.display='block';
						}
					}

					if(!destinationObj)destinationObj = ulPositionArray[no]['obj'];
					mouseoverObj = ulPositionArray[no]['obj'].parentNode;
					mouseoverObj.className='mouseover';
					return;
				}
			}
		}
	}

	/* End dragging
	Put <LI> into a destination or back to where it came from.
	*/
	function dragDropEnd(e)
	{
		if(dragTimer==-1)return;
		if(dragTimer<10){
			dragTimer = -1;
			saveDragDropNodes();
			return;
		}
		dragTimer = -1;
		if(document.all)e = event;
		if(cloneSourceItems && (!destinationObj || (destinationObj && (destinationObj.id.substr(0,8)=='allItems' || destinationObj.parentNode.id.substr(0,8)=='allItems')))){
			contentToBeDragged.parentNode.removeChild(contentToBeDragged);
		}else{

			if(destinationObj){
				if(destinationObj.tagName=='UL'){
					destinationObj.appendChild(contentToBeDragged);
				}else{
					destinationObj.parentNode.insertBefore(contentToBeDragged,destinationObj);
				}
				mouseoverObj.className='';
				destinationObj = false;
				dragDropIndicator.style.display='none';
				if(indicateDestinationBox){
					indicateDestinationBox.style.display='none';
					document.body.appendChild(indicateDestinationBox);
				}
				contentToBeDragged = false;
				saveDragDropNodes();
				return;
			}
			if(contentToBeDragged_next){
				contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
			}else{
				contentToBeDragged_src.appendChild(contentToBeDragged);
			}
		}
		contentToBeDragged = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox){
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);
		}
		mouseoverObj = false;
		saveDragDropNodes();
	}

	/*
	Preparing data to be saved
	*/
	function saveDragDropNodes()
	{
		var saveString = "";
		var mainContainer = document.getElementById('mainContainer');
		var uls = mainContainer.getElementsByTagName('UL');
		for(var no=0;no<uls.length;no++){	// LOoping through all <ul>
			var lis = uls[no].getElementsByTagName('LI');
			for(var no2=0;no2<lis.length;no2++){
				if(saveString.length>0)saveString = saveString + ",";
				saveString = saveString + lis[no2].id;
			}
		}

		document.getElementById('data').value = saveString;
	}

	function initDragDropScript()
	{
		dragContentObj = document.getElementById('dragContent');
		dragDropIndicator = document.getElementById('dragDropIndicator');
		dragDropTopContainer = document.getElementById('dragDropContainer');
		document.documentElement.onselectstart = cancelEvent;;
		var listItems = dragDropTopContainer.getElementsByTagName('LI');	// Get array containing all <LI>
		var itemHeight = false;
		for(var no=0;no<listItems.length;no++){
			listItems[no].onmousedown = initDrag;
			listItems[no].onselectstart = cancelEvent;
			if(!itemHeight)itemHeight = listItems[no].offsetHeight;
			if(MSIE && navigatorVersion/1<6){
				listItems[no].style.cursor='hand';
			}
		}

		var listOfItems = document.getElementById('listOfItems');
		var uls = listOfItems.getElementsByTagName('UL');
		itemHeight = itemHeight + verticalSpaceBetweenListItems;
		for(var no=0;no<uls.length;no++){
			var subLi = uls[no].getElementsByTagName('LI');
			if ( subLi.length == 0 )
			{
				uls[no].style.height = '50px';
			}
			else
			{
				uls[no].style.height = (uls[no].offsetHeight+50)+'px';
			}
		}

		var mainContainer = document.getElementById('mainContainer');
		var uls = mainContainer.getElementsByTagName('UL');
		itemHeight = itemHeight + verticalSpaceBetweenListItems;
		for(var no=0;no<uls.length;no++){
			var subLi = uls[no].getElementsByTagName('LI');
			if ( subLi.length == 0 )
			{
				uls[no].style.height = '50px';
			}
			else
			{
				uls[no].style.height = (uls[no].offsetHeight+50)+'px';
			}
		}

		var leftContainer = document.getElementById('listOfItems');
		var itemBox = leftContainer.getElementsByTagName('UL')[0];

		document.documentElement.onmousemove = moveDragContent;	// Mouse move event - moving draggable div
		document.documentElement.onmouseup = dragDropEnd;	// Mouse move event - moving draggable div

		var ulArray = dragDropTopContainer.getElementsByTagName('UL');
		for(var no=0;no<ulArray.length;no++){
			ulPositionArray[no] = new Array();
			ulPositionArray[no]['left'] = getLeftPos(ulArray[no]);
			ulPositionArray[no]['top'] = getTopPos(ulArray[no]);
			ulPositionArray[no]['width'] = ulArray[no].offsetWidth;
			ulPositionArray[no]['height'] = ulArray[no].clientHeight;
			ulPositionArray[no]['obj'] = ulArray[no];
		}

		if(!indicateDestionationByUseOfArrow){
			indicateDestinationBox = document.createElement('LI');
			indicateDestinationBox.id = 'indicateDestination';
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);
		}
	}

	function updateDragDropScript()
	{
		var ulArray = dragDropTopContainer.getElementsByTagName('UL');
		for(var no=0;no<ulArray.length;no++){
			ulPositionArray[no] = new Array();
			ulPositionArray[no]['left'] = getLeftPos(ulArray[no]);
			ulPositionArray[no]['top'] = getTopPos(ulArray[no]);
			ulPositionArray[no]['width'] = ulArray[no].offsetWidth;
			ulPositionArray[no]['height'] = ulArray[no].clientHeight;
			ulPositionArray[no]['obj'] = ulArray[no];
		}
	}
