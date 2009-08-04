/**
 * Tree drag scrolling code
 */

document.onmousemove = mouseMove;
document.onmouseup = treeEndPan;

var mouseLastPos;
var mousePanning = false;
var panElement;

function mouseMove(ev)
{ 
	ev = ev || window.event; 
	var mousePos = mouseCoords(ev);
	
	if (mousePanning && panElement) {
		panElement.scrollLeft += mouseLastPos.x - mousePos.x;
		panElement.scrollTop += mouseLastPos.y - mousePos.y;
	}
	
	mouseLastPos = mousePos;
} 
 
function mouseCoords(ev)
{ 
	if (ev.pageX || ev.pageY) { 
		return {x:ev.pageX, y:ev.pageY}; 
	}
	return { 
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft, 
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop 
	}; 
} 

function treeBeginPan(ev)
{
	mousePanning = true;
	return false;
}

function treeEndPan(ev)
{
	mousePanning = false;
}

function setPanElement(element)
{
	element.onmousedown = treeBeginPan;
	panElement = element;
}