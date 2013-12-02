<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$cache = dirname(__FILE__) . '/cache/twitter-json.txt';
$data = file_get_contents('http://search.twitter.com/search.json?q=via%20%40addthis&rpp=100&geocode=56.5%2C35.9%2C10000000000.0mi&result_type=recent&callback=?');

		$cachefile = fopen($cache, 'wb');
        fwrite($cachefile,utf8_encode($data));
        fclose($cachefile);
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="http://www.techepsilon.com/images/favicon.png" type="image/x-icon" rel="shortcut icon"/>
<title>TechEpsilon - Labs - GlobeTweet</title>
<meta name="Keywords" content="TechEpsilon, website design, website development, web application development, user interface design, search engine optimization, facebook development, content management system, iphone development, ipad development, android development, blackberry development, windows phone 7 development, social media, emarketing, design, branding, software company, offshore development" />
<meta name="Description" content="GlobeTweet is a WebGL experiment on mapping latest Tweets on a 3D globe based on the location of the Tweet." />

<link href="addons/stylesorg.css" rel="stylesheet" type="text/css" />
<link href="addons/style.css" rel="stylesheet" type="text/css" />
<script>
function start()
{
cyc();
mark=earth.initMarker(arrlat[0],arrlong[0]);
roll=0;
tim=8000;
c=0;
rotate();
document.getElementById('panelcover').style.visibility='hidden';}
</script>
<script src="addons/index.js"></script> 

<script src="addons/api.js"></script> <!--globe api-->
<script type="text/javascript">  <!--initialize globe-->
function initialize() {

	var options = { zoom: 4, position: [0.0, 0.0], proxyHost: 'http://data.webglearth.com/cgi-bin/corsproxy.fcgi?url=' };
    earth = new WebGLEarth('earth_div', options);
	var bingKey = 'Ar48owY2MuITARXqbvv3LpxLDv4GbS9ZMtPgCSnR25EGLNoUSnvxfjeGlJtIX2F8';

    

    bingA = earth.initMap(WebGLEarth.Maps.BING, ['AerialWithLabels', bingKey]);
    //OSM = earth.initMap(WebGLEarth.Maps.OSM);
    //bingR = earth.initMap(WebGLEarth.Maps.BING, ['Road', bingKey]);
   

    earth.setBaseMap(bingA);	
	centerscreen();
}
</script>

<script type="text/javascript"> <!--rotate-->
function rotate(){
		var a=arrind[c];
		earth.flyTo(arrlat[a],arrlong[a]);
		mark.setPosition(arrlat[a],arrlong[a]);
		if(roll==1){
			var m=arrval[c];
			tim=12000;
			$('#nav li:eq('+m+') a').trigger('click');
			$('#tweetcontainer_middle').cycle('pause');
		}
		if(c==arrval.length-1)
		{
			c=0;
			setTimeout("rotate()",tim);
		}
		else
		{
			c++;
			setTimeout("rotate()",tim);
		}
	
}
</script>


<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
function centerscreen()
{
var winW = 630, winH = 460;
if (document.body && document.body.offsetWidth) {
 winW = document.body.offsetWidth;
 winH = document.body.offsetHeight;
}
if (document.compatMode=='CSS1Compat' &&
    document.documentElement &&
    document.documentElement.offsetWidth ) {
 winW = document.documentElement.offsetWidth;
 winH = document.documentElement.offsetHeight;
}
if (window.innerWidth && window.innerHeight) {
 winW = window.innerWidth;
 winH = window.innerHeight;
}
var w=(winW/2)-158;
var h=(winH/2)-171;
document.getElementById('tweetcontainer').style.top=h+'px';
document.getElementById('tweetcontainer').style.left=w+'px';
var wpanel=(winW/2)-211;
document.getElementById('panelcoverlink').style.marginLeft=wpanel+'px';
}
</script>
<script type="text/javascript">
$(window).load(function(){ 
    $("#loadingdiv").delay(5000).hide(0);
});
</script>
<script type="text/javascript">
$(window).resize(function() {
centerscreen();
});
</script>

<script type="text/javascript" src="addons/slideshow.js"></script>
<script type="text/javascript" src="addons/transition.js"></script>
<script language="JavaScript" type="text/javascript"> <!--cycle tweets-->
index=0;
function cyc() {
var i=0;
index++;
if(index==1){
$('#tweetcontainer_middle').cycle(
{
fx: 'fade',
speed: 400,
timeout: 8000,
pager:  '#nav', 
pagerAnchorBuilder: function(idx, slide) {
       return '<li><a href="#">'+(i++)+'</a></li>'; 
}, 
width: 328,
height: 125,
pause: true,
cleartypeNoBg:true
}
);}
}
</script>

<script type="text/javascript"> // Display Latest Tweet

$(document).ready(function(){

arrlat=new Array();
arrlong=new Array();
arrloc=new Array();
arrval=new Array();
formed=new Array();
arrind=new Array();
cnt=0;
var fav ='Favorite';
var ret ='Retweet';
var rep ='Reply';
var i=0;
$result=$.getJSON('cache/twitter-json.txt', function(data){
		$.each(data.results, function(index, item){i++;
			if(item.location!="" && item.iso_language_code=="en"){
				var loc=item.location;
				var xmlhttp;
				xmlhttp=new XMLHttpRequest();
				var geourl="http://api.geonames.org/searchJSON?q=" + loc + "&maxRows=10&username=vidya&callback=?";
				xmlhttp.open("GET",geourl,true);
				xmlhttp.send();
				$.getJSON(geourl,function(data){
					if (data.totalResultsCount !== undefined && data.totalResultsCount > 1){
						var it=data.geonames[0];
						var lng=it.lng;
						var lat=it.lat;
						var  coun = it.countryName;
						var msg=item.text.linkify();
						var p=5;
						msg=makehashLink(msg);
						msg=makeuserLink(msg);
						
						$('#tweetcontainer_middle').append(
						
						'<div>'+
						'<div class="tweettextandimage">'+
							'<div><img id="userimg" src="' + 
								item.profile_image_url +
								'"/></div>'+
							'<div class="textsection">'+
								'<div class="tweetcontainerheader">'+
									'<div class="bigtext_bold"><span><a href="http://www.twitter.com/'+ item.from_user + '" target="_blank">' + item.from_user+ '</a>&nbsp;'  + '</span>'+
									'</div>'+
									'<div class="bigtext">'+'<b>' +  item.from_user_name + '</b>'+
									'</div>'+
									'<div class="cleardiv"></div>'+
								'</div>'+
								'<div class="mediumtext"><b>' + msg+'</b></div>' +
							'</div>'+
							'<div class="cleardiv"></div>'+
							'<div class="smalltext_link">'+
								'<div class="tweettime">'+item.created_at.substring(4,11)+'</div>'+
								'<div class="favorite">'+
									'<span><a href="http://www.twitter.com/intent/favorite?tweet_id=' + item.id_str + ' " target="_blank">' + fav + '</a>&nbsp;' + '</span>'+
								'</div>'+
								'<div class="retweet">'+
									'<span><a href="http://www.twitter.com/intent/retweet?tweet_id=' + item.id_str + ' " target="_blank">' + ret + '</a>&nbsp;' + '</span>'+
								'</div>'+
								'<div class="reply">'+
									'<span><a href="http://www.twitter.com/intent/tweet?tweet_id=' +item.id_str + ' " target="_blank">' + rep + '</a>&nbsp;' + '</span>'+
								'</div>'+
								'<div class="countryname">'+coun+'</div>'+
								'<div class="cleardiv"></div>'+
							'</div>'+
						'</div');
						arrlat[index]=lat;
						arrlong[index]=lng;
						arrloc[index]=1;
						formed.push(index);
						arrval.push(formed.indexOf(index));
						arrind.push(index);
						cnt++;
						//window.alert(formed);
					}
					else{
						arrloc[index]=0;
						arrlat[index]=-999;
						arrlong[index]=-999;}
				});
			}
			else{
				arrloc[index]=0;
				arrlat[index]=-999;
				arrlong[index]=-999;}
		});
	});


// Convert Twitter API Timestamp to "Time Ago"


});	
// Create Usable Links
String.prototype.linkify = function() {
       return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/g, function(m) {
              return m.link(m);}
			  );
$};
function makehashLink(text)  // this REGEX converts http(s) links that are embedded in the tweeet text into real hyperlinks.
 {   
 var exp = /[#]+([A-Za-z0-9-_]+)/g;   
 return text.replace(exp,"<a href=\"http://twitter.com/search?q=%23$1\" target=\"_blank\">#$1</a>"); 
 } 
function makeuserLink(text)  // this REGEX converts http(s) links that are embedded in the tweeet text into real hyperlinks.
 {   
 var exp = /[@]+([A-Za-z0-9-_]+)/g;   
 return text.replace(exp,"<a href=\"http://twitter.com/$1\" target=\"_blank\">@$1</a>"); 
 } 
</script>

<script>
function search()
{
	ser=document.getElementById('txt').value;
	ser=ser.toLowerCase();
	arrval=new Array();
	arrind=new Array();
	var i=0;
	$.getJSON('cache/twitter-json.txt', function(data){
		$.each(data.results, function(index, item){
			if(arrloc[index]!=0){
				var msg=item.text.toLowerCase();
				if(ser.charAt(0)=='#'){
					if(msg.indexOf(ser)!=-1){
						arrval.push(formed.indexOf(index));
						arrind.push(index);}
				}
				else if(ser.charAt(0)=='@'){
					var name=item.from_user_name.toLowerCase();
					var screenname=item.from_user.toLowerCase();
					if(msg.indexOf(ser)!=-1 || name.indexOf(ser)!=-1 || screenname.indexOf(ser)!=-1){
						arrval.push(formed.indexOf(index));
						arrind.push(index);}
				}
				else{
					var name=item.from_user_name.toLowerCase();
					var screenname=item.from_user.toLowerCase();
					var loct=item.location.toLowerCase();
					if(msg.indexOf(ser)!=-1 || name.indexOf(ser)!=-1 || screenname.indexOf(ser)!=-1 || loct.indexOf(ser)!=-1)
					{
						arrval.push(formed.indexOf(index));
						arrind.push(index);
						
					}
				}
			}
		});
	});
	setTimeout('search_cycle()',3000);
}
				
function search_cycle()
{
$('#tweetcontainer_middle').cycle('pause');
c=0;
roll=1;
tim=12000;
rotate();
}
function searchKeyPress(e)
    {
        if (window.event) { e = window.event; }
            if (e.keyCode == 13)
            {
                document.getElementById('searchptr').click();
            }
    }
</script>
<!-- Google Analytics Tracking Code Starts Here -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-9833815-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body class="mainpagebody"  onload="initialize()">
<div id="loadingdiv"></div>
<div class="copyright">
<div class="copyrleft">Developed by <a href="http://www.techepsilon.com" target="_blank">TechEpsilon</a>. Powered by <a href="http://www.webglearth.com" target="_blank">WebGL Earth</a> &amp; <a href="http://www.bing.com/maps" target="_blank">Bing Maps.</a></div>
<a href="http://www.techepsilon.com/labs/" target="_blank"><div class="copyrright"></div></a>

</div>
<div class="panelcover" id="panelcover" onclick="start()">
<div id="panelcoverlink" onclick="location.href='http://www.techepsilon.com/blog/?p=234';"></div>
</div>
	<div class="container"><!--Container starts here-->
		<div class="header"><!--Header section starts here-->
			<div class="logo">
                <a href="http://www.techepsilon.com/labs" title="GlobeTweet - A TechEpsilon Experiment" target="_blank">
				<div class="techepsilon_logo_link"></div></a>
				<div class="socialnetworkholder">
				
				
				
				
				
				</div>
			</div>
			
			<ul id="nav" style="height:0px; width:500px;"></ul>
				<div class="searchboxanddropdownbox">
					<div class="searchboxholder" id="searchboxholder">
						<input type="text" name="txt" id="txt" class="searchbox" onkeypress="searchKeyPress(event);" placeholder="Type and hit enter to search Tweets"/>
						 <input type="submit" id="searchptr" name="searchptr" value="Search" onclick="search();" style="visibility:hidden;"/>
					</div>						
				<div class="cleardiv"></div>		
				</div>
			
			<div class="cleardiv"></div>	
		</div>
		<div id="earth_div" style="position:relative;width:100%;height:96%; top: -15px;"></div>

		<div class="globeholder">		
		<div id="tweety">
			<div class="tweetcontainer" id="tweetcontainer">
				<div class="tweetcontainer_top"></div>
				<div class="tweetcontainer_middle" id="tweetcontainer_middle"></div>
				<div class="tweetcontainer_bottom"></div>

			</div>
		</div>

<div id="sliderpos"> 
    <span id="substractzoom"/> 
    <span id="addzoom"/> 
    <span id="weapp-zoomslider" class="goog-slider-vertical"/>  </div> 
  <div id="panpos"> 
    <span id="weapp-pancontrol"/>  </div> 
	</div>
	
  
		
		<!--Globe section ends here-->						
		</div>	
	</div>
	<!--Container starts here-->
</body>
</html>