



var cycleData1 = [
	{
		image : "images/cycle-dummy.png",
		title: "Tyl Uilenspiegel 1",
		author: "G. Theod",
		place: "Amsterdam",
		textData : [
			"First line",
			"Second line",
			"Third line",
			"Fouth line"
		]
	},
	{
		image : "images/cycle-dummy.png",
				title: "Tyl Uilenspiegel 2",
		author: "G. Theod",
		place: "Amsterdam",
		textData : [
			"First line",
			"Second line",
			"Third line"
		]
	},
	{
		image : "images/cycle-dummy.png",
				title: "Tyl Uilenspiegel 3",
		author: "G. Theod",
		place: "Amsterdam",
		textData : [
			"First line",
			"Second line",
			"Third line",
			"Fouth line",
			"Fifth line",
			"Sixt line"
		]
	},
	{
		image : "images/cycle-dummy.png",
				title: "Tyl Uilenspiegel 4",
		author: "G. Theod",
		place: "Amsterdam",
		textData : [
			"First line",
			"Second line",
			"Third line",
			"Fouth line",
			"Fifth line"
		]
	},
	{
		image : "images/cycle-dummy.png",
		title: "Tyl Uilenspiegel 5",
		author: "G. Theod",
		place: "Amsterdam",
		textData : [
			"First line",
			"Second line"
		]
	}
];
sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);


/**
 * Initialize $.CORE in case it's not present
 */
if (typeof $.CORE === "undefined") {
	$.CORE = Object;
}

$.CORE.themehover = {
	construct: function(){
		this.execThemeHover();
	},
	execThemeHover : function(){
		console.log("hovering");
		$(".themalist_item").hover(function(){

			$(".themalist_item_nav", this).stop(false, true).fadeToggle("slow");
			
		});
	}
}

$.CORE.collapse = {
	construct : function(){
		this.execCollapse();
	},
	execCollapse : function(){
			var advSearchOpen = false
			$('#search_advanced, .frm_close').click(function() {
				if(!advSearchOpen){
				  $('#searchForm').slideDown('slow', function() {
				    // Animation complete.
				    advSearchOpen = true;
				  });
				}else{
				  $('#searchForm').slideUp('slow', function() {
				    // Animation complete.
				    advSearchOpen = false;
				  });
				}
			});
	}
};


/**
 * Create dropdown
 */
$.CORE.dropdown = {
	construct : function() {
		this.execSelects();
	},
	execSelects : function() {
		//var arrSelects = new Array('searchForm select');
		$.each($("#searchForm select"), function() {
			var name = $(this).attr("id");
			var source = $("#" + name);
			
			
			var selected = source.find("option[selected]");  // get selected <option>
			var options = $("option", source);  // get all <option> elements
			// create <dl> and <dt> with selected value inside it
			source.after('<dl id="target'+name+'" class="dropdown"></dl>')
			$("#target"+name).append('<dt><a href="#">' + selected.text() + 
				'<span class="value">' + selected.val() + 
				'</span></a></dt>')
			$("#target"+name).append('<dd><ul></ul></dd>')
			// iterate through all the <option> elements and create UL
			options.each(function(){
				$("#target"+name+" dd ul").append('<li><a href="#">' + 
					$(this).text() + '<span class="value">' + 
					$(this).val() + '</span></a></li>');
			});
		});
		
		$(document).bind('click', function(e) {
			var $clicked = $(e.target);

			if (!$clicked.parents().hasClass("dropdown")) {
				
				$(".dropdown dd ul").hide();
			}
		});
		
		$(".dropdown dt a").click(function(e) {
			$('ul', $(this).parent().next()).toggle();
			e.preventDefault();
			console.log($(this).parent().next());
		});

		$(".dropdown dd ul li a").click(function(e) {
	e.preventDefault();
			var parent = $(this).parents('div.col');
			
			var text = $(this).html();
			$(".dropdown dt a", parent).html(text);
			$(".dropdown dd ul", parent).hide();

			var source = $("#" + parent.find('select').attr('id'));
			source.val($(this).find("span.value").html())
		});
	}
};


$(document).ready(function() {
	$.CORE.dropdown.construct();
	$.CORE.collapse.construct();
	$.CORE.themehover.construct();


	if($.fn.roundabout){
		RoundAbout.init($(".cycle"), cycleData1);
	}
	if($.fn.tooltip){
		$("#pages-thumbnails img").tooltip({
					
					position: 'bottom center',
					relative: false,
				   // tweak the position
				   offset: [-50, 0]

				   // use the "slide" effect
				   //effect: 'slide'

				// add dynamic plugin with optional configuration for bottom edge
				}).dynamic({ bottom: { direction: 'down', bounce: true } });
	
	}
});





var RoundAbout = (function () {
	var init,
		animateTo,
		animEndedCallback,
		showChildDescription,
		animateDescriptionLine,
		clearDescription,
		el,
		data,
		settings = {
			cycleDuration : 500,
			descriptionElementDelay : 50,
			descriptionElementDuration : 150
		};

	init = function (element, cycleData) {
		var i, l;
		el = element;
		data = cycleData;

		for (i = 0, l = data.length; i < l; i += 1) {
			$(el).find(".rotator").append("<li><img src=\""+ data[i].image +"\" /><div class=\"tooltip\"><div class=\"slideTitle\">"+ data[i].title +" <span class=\"slideAuthor\">"+ data[i].author +"</span></div><span class=\"slidePlace\">"+ data[i].place +"</span></div></li>")
		}

		$(el).find(".rotator").roundabout({
			minOpacity : 0.2,
			shape : 'square',
			duration : settings.cycleDuration,
			clickToFocus : false
		});

		$(el).find(".next").on("click", function (e) {
			e.preventDefault();
			animateTo("animateToNextChild");
		});

		$(el).find(".prev").on("click", function (e) {
			e.preventDefault();
			animateTo("animateToPreviousChild");
		});
		loadTooltips();
		showChildDescription();



	};

	animateTo = function (functionName) {
		clearDescription();
		$(el).find(".rotator").roundabout(functionName);
		
		setTimeout(function () {
			animEndedCallback();
		}, settings.cycleDuration + settings.cycleDuration / 10);
	};

	animEndedCallback = function () {
		
		showChildDescription();

	};

	loadTooltips = function(){
		console.log("enableThumbnail");


		// initialize tooltip
		$(".rotator li img").tooltip({
			
			position: 'bottom center',
			relative: true,
		   // tweak the position
		   offset: [-120, 0]

		   // use the "slide" effect
		   //effect: 'slide'

		// add dynamic plugin with optional configuration for bottom edge
		}).dynamic({ bottom: { direction: 'down', bounce: true } });
		

	}

	

	showChildDescription = function () {
		var i, l,
			index = $(el).find(".rotator").roundabout("getChildInFocus");

		for (i = 0, l = data[index].textData.length; i < l; i += 1) {
			animateDescriptionLine(data[index].textData[i], i * settings.descriptionElementDelay);
		}
	};

	animateDescriptionLine = function (lineData, delay) {
		var descContainer = $(el).find(".description"),
			lineElement = $("<div><span>" + lineData + "</span></div>");

		$(lineElement).css({
			opacity : 0,
			left : 50
		});
		$(descContainer).append(lineElement);
		$(lineElement).delay(delay).animate({
			opacity : 1,
			left : 0
		}, settings.descriptionElementDuration);
	};

	clearDescription = function () {
		$(el).find(".description").find("div").fadeTo(settings.cycleDuration / 2, 0, function () {
			$(this).remove();
		});
	};

	return {
		init : init,
		animEndedCallback : animEndedCallback
	};
}());