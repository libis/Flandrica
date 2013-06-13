var theme1 = Timeline.ClassicTheme.create();
theme1.autoWidth = false; // Set the Timeline's "width" automatically.
                         // Set autoWidth on the Timeline's first band's theme,
                         // will affect all bands.
theme1.timeline_start = new Date(Date.UTC(400, 0, 1));
theme1.timeline_stop  = new Date(Date.UTC(2100, 0, 1));

theme1.ether = {
    backgroundColors: [
    //    "#EEE",
    //    "#DDD",
    //    "#CCC",
    //    "#AAA"
    ],
 //   highlightColor:     "white",
    highlightOpacity:   100,
    interval: {
        line: {
            show:       true,
            opacity:    100
           // color:      "#aaa",
        },
        weekend: {
            opacity:    30
          //  color:      "#FFFFE0",
        },
        marker: {
            hAlign:     "Bottom",
            vAlign:     "Right"
        }
    }
};

theme1.event = {
    track: {
               height: 30, // px. You will need to change the track
                           //     height if you change the tape height.
                  gap:  10, // px. Gap between tracks
               offset:  10, // px. top margin above tapes
      autoWidthMargin:  1.5
      /* autoWidthMargin is only used if autoWidth (see above) is true.
         The autoWidthMargin setting is used to set how close the bottom of the
         lowest track is to the edge of the band's div. The units are total track
         width (tape + label + gap). A min of 0.5 is suggested. Use this setting to
         move the bottom track's tapes above the axis markers, if needed for your
         Timeline.
      */
    },
    overviewTrack: {
              offset: 6, // px -- top margin above tapes
          tickHeight:  6, // px
              height:  2, // px
                 gap:  1, // px
     autoWidthMargin:  5 // This attribute is only used if autoWidth (see above) is true.
    },
    tape: {
        height:         24 // px. For thicker tapes, remember to change track height too.
    },
    instant: {
                       icon: Timeline.urlPrefix + "/images/dull-blue-circle.png",
                             // default icon. Icon can also be specified per event
                  iconWidth: 24,
                 iconHeight: 27,
           impreciseOpacity: 20, // opacity of the tape when durationEvent is false
        impreciseIconMargin: 3   // A tape and an icon are painted for imprecise instant
                                 // events. This attribute is the margin between the
                                 // bottom of the tape and the top of the icon in that
                                 // case.
//        color:             "#58A0DC",
//        impreciseColor:    "#58A0DC",
    },
    duration: {
        impreciseOpacity: 20 // tape opacity for imprecise part of duration events
  //      color:            "#58A0DC",
  //      impreciseColor:   "#58A0DC",
    },
    label: {
        backgroundOpacity: 50,// only used in detailed painter
           offsetFromLine:  3 // px left margin amount from icon's right edge
  //      backgroundColor:   "white",
  //      lineColor:         "#58A0DC",
    },
    highlightColors: [  // Use with getEventPainter().setHighlightMatcher
                        // See webapp/examples/examples.js
        "#FFFF00",
        "#FFC000",
        "#FF0000",
        "#0000FF"
    ],
    highlightLabelBackground: false, // When highlighting an event, also change the event's label background?
    bubble: {
        width:          250, // px
        maxHeight:        0, // px Maximum height of bubbles. 0 means no max height.
                             // scrollbar will be added for taller bubbles
        titleStyler: function(elmt) {
            elmt.className = "timeline-event-bubble-title";
        },
        bodyStyler: function(elmt) {
            elmt.className = "timeline-event-bubble-body";
        },
        imageStyler: function(elmt) {
            elmt.className = "timeline-event-bubble-image";
        },
        wikiStyler: function(elmt) {
            elmt.className = "timeline-event-bubble-wiki";
        },
        timeStyler: function(elmt) {
            elmt.className = "timeline-event-bubble-time";
        }
    }
};

var NeatlineTime={
	resizeTimerID:null,resizeTimeline:function(){
		resizeTimerID==null&&(resizeTimerID=window.setTimeout(function(){
			resizeTimerID=null;tl.layout()
		},500))
	},

	_monkeyPatchFillInfoBubble:function(){
	Timeline.DefaultEventSource.Event.prototype.fillInfoBubble=function(d,e,c){
		var a=d.ownerDocument,h=this.getText(),b=this.getLink(),
		f=this.getImage();
		if(f!=null){
			var g=a.createElement("img");
			g.src=f;e.event.bubble.imageStyler(g);
                        b!=null?(y=a.createElement("a"),
			y.href=b,y.appendChild(g),
			d.appendChild(y)):d.appendChild(g);
			
		}
			f=a.createElement("div");
			g=a.createElement("span");
			g.innerHTML=h;
			b!=null?(h=a.createElement("a"),
			h.href=b,h.appendChild(g),
			f.appendChild(h)):f.appendChild(g);
			e.event.bubble.titleStyler(f);
			d.appendChild(f);
			b=a.createElement("div");
			this.fillDescription(b);
			e.event.bubble.bodyStyler(b);
			d.appendChild(b);

			c=a.createElement("div");
			this.fillWikiInfo(c);e.event.bubble.wikiStyler(c);
			d.appendChild(c);
		}
	}
	,loadTimeline:function(d,e){
		NeatlineTime._monkeyPatchFillInfoBubble();
		var c=new Timeline.DefaultEventSource;
		Timeline.getDefaultTheme().mouseWheel="zoom";
		var a=[
			Timeline.createBandInfo({
				overview:true,
				eventSource:c,
				width:50,
				intervalUnit:Timeline.DateTime.CENTURY,
				theme:theme1,
				intervalPixels:200,
				layout:'original'
			}),

	       Timeline.createBandInfo({
                 width:          35, // set to a minimum, autoWidth will then adjust
                 intervalUnit:   Timeline.DateTime.DECADE,
                 intervalPixels: 92,
                 eventSource:    c,

                 theme:          theme1,
                 overview:       true,
                 layout:         'original'  // original, overview, detailed
             }),
             Timeline.createBandInfo({
                 width:          515, // set to a minimum, autoWidth will then adjust
                 intervalUnit:   Timeline.DateTime.DECADE,
                 intervalPixels: 92,
                 eventSource:    c,

                 theme:          theme1,
                 layout:         'original',  // original, overview, detailed
                 zoomIndex:10,
     			 zoomSteps:[				                                                                                 {pixelsPerInterval:280,unit:Timeline.DateTime.MONTH},
     				{pixelsPerInterval:140,unit:Timeline.DateTime.MONTH},
     				{pixelsPerInterval:70,unit:Timeline.DateTime.MONTH},
     				{pixelsPerInterval:35,unit:Timeline.DateTime.MONTH},
     				{pixelsPerInterval:400,unit:Timeline.DateTime.YEAR},
     				{pixelsPerInterval:200,unit:Timeline.DateTime.YEAR},
     				{pixelsPerInterval:100,unit:Timeline.DateTime.YEAR},
     				{pixelsPerInterval:50,unit:Timeline.DateTime.YEAR},
     				{pixelsPerInterval:400,unit:Timeline.DateTime.DECADE},
     				{pixelsPerInterval:200,unit:Timeline.DateTime.DECADE},
     				{pixelsPerInterval:100,unit:Timeline.DateTime.DECADE}
     				]
             })

		];

         a[0].syncWith = 1;
         a[2].syncWith = 1;
         a[0].highlight = true;

		tl=Timeline.create(document.getElementById(d),a,Timeline.HORIZONTAL);
		tl.loadJSON(e,function(a,b){
				a.events.length>0&&(c.loadJSON(a,b),
				tl.getBand(0).setCenterVisibleDate(new Date(Date.UTC(1750, 0, 1)))
		)}
	)}
};