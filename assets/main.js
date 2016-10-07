/*
 *	Click events for popup windows contact and impressum
 *
 */
// Detect if the browser is IE or not.
// If it is not IE, we assume that the browser is NS.
var IE = document.all?true:false

// If NS -- that is, !IE -- then set up for mouse capture
if (!IE) document.captureEvents(Event.MOUSEMOVE)

var btn = document.querySelectorAll("section a.btn");

for (i = 0; i < btn.length; i++) {
  btn[i].addEventListener('click', function() {
    this.nextElementSibling.style.display = "block";
  });
}

window.onclick = function(e) {
	var tempX = 0;
	var tempY = 0;

	if (IE) { // grab the x-y pos.s if browser is IE
	  tempX = event.clientX + document.body.scrollLeft
	  tempY = event.clientY + document.body.scrollTop
	} else {  // grab the x-y pos.s if browser is NS
	  tempX = e.pageX
	  tempY = e.pageY
	}
	// catch possible negative values in NS4
	if (tempX < 0){tempX = 0}
	if (tempY < 0){tempY = 0}

	for (i = 0; i < btn.length; i++) {
		var box = btn[i].nextElementSibling;
		var bbox = box.getBoundingClientRect();
		if (bbox.width == 0 || e.target == btn[i]) {
			continue;
		}
		if(tempX < bbox.left || tempX > bbox.right || tempY < bbox.top || tempY > bbox.bottom) {
			box.style.display = "none";
		}
	}
}

/*
 *	Carousel for tweets and videos
 *
 *
 */
carousel_init(false)

function carousel_init(automatic)
{

	var cid;
	window.carousels = document.querySelectorAll(".carousel");
	window.carousel_intervals = [];
	window.carousel_atts = [];
	for (cid = 0; cid < carousels.length; ++cid) {
		window.carousel_atts[cid] = document.createAttribute("iter");
		window.carousel_atts[cid].value = 0;
		carousels[cid].setAttributeNode(window.carousel_atts[cid]);
		carousel_jump(cid, 1);
		if (automatic) {
			window.carousel_intervals[cid] = window.setInterval(function(j) { return function() { carousel_iter(j, 1); }; }(cid), 4000);
		}

		/*
		 * Add the buttons 'n stuff
		 */
		 var Btns = [
		 				document.createElement("DIV"),
		 				document.createElement("DIV")
		 			];
		 var BtnItt = [-1, 1];

		 Btns[0].classList.add("left");
		 var leftCnt = document.createTextNode('\u276E');
		 Btns[0].appendChild(leftCnt);
		 Btns[1].classList.add("right");
		 var rightCnt = document.createTextNode('\u276F');
		 Btns[1].appendChild(rightCnt);


		 for(i=0; i < Btns.length; i++) {
		 	Btns[i].classList.add("arrow");
		 	Btns[i].onclick = function(j, itt) {
		 		return function() {
		 			clearInterval(window.carousel_intervals[j]);
		 			carousel_iter(j, itt);
		 		};
		 	}(cid, BtnItt[i]);
		 	Btns[i].onmousedown=function(j) {
		 		return function() {
		 			j.classList.add("clicked");
		 		};
		 	}(Btns[i]);
		 	Btns[i].onmouseup=function(j) {
		 		return function() {
		 			setTimeout(j.classList.remove("clicked"),300);
		 		};
		 	}(Btns[i]);

		 	carousels[cid].appendChild(Btns[i]);

		 }
	}
}

function carousel_iter(carousel_idx, n)
{
	var cur = window.carousels[carousel_idx].getAttribute("iter");
	cur = parseInt(cur);
	var carousel_items = window.carousels[carousel_idx].querySelectorAll(".carousel_item");
	carousel_items[cur].classList.remove("visible");
	carousel_jump(carousel_idx, cur + n);
}

function carousel_jump(carousel_idx, n)
{
	var carousel_items = window.carousels[carousel_idx].querySelectorAll(".carousel_item");
	var idx = n;
	var i;
	if (n > carousel_items.length) { idx = 1 }
	if (n < 1) { idx = carousel_items.length }
	for (i = 0; i < carousel_items.length; ++i)
	{
		carousel_items[i].classList.remove("visible");
	}
	carousel_items[idx-1].classList.add("visible");
	window.carousel_atts[carousel_idx].value = idx;
	window.carousels[carousel_idx].setAttributeNode(window.carousel_atts[carousel_idx]);
}