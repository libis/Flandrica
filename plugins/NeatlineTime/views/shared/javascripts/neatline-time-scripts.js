

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
			d.appendChild(g)
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