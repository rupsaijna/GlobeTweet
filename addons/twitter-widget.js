// Display Latest Tweet

$(document).ready(function(){

var arr=new Array();
var i=0;
$result=$.getJSON('cache/twitter-json.txt', function(data){
        $.each(data, function(index, item){if(item.user.location!="" && item.user.lang=="en"){
                $('#tweettextandimage').append('<div><img class="userimg" src="' + item.user.profile_image_url + '" width="48" height="48" alt="Twitter" style="margin-top: 7px";">'+'<div id="textsection" style="width: 300px; height: 70px; margin-top:7px;">' + item.text.linkify() +'<br>'+item.created_at+item.user.location+'</br></div><div class="cleardiv"></div></div>');
				arr.push(item.user.location);
				/*window.alert(arr[i++]);*/}
		});
});


// Convert Twitter API Timestamp to "Time Ago"

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  var r = '';
  if (delta < 60) {
        r = 'a minute ago';
  } else if(delta < 120) {
        r = 'couple of minutes ago';
  } else if(delta < (45*60)) {
        r = (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (90*60)) {
        r = 'an hour ago';
  } else if(delta < (24*60*60)) {
        r = '' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
        r = '1 day ago';
  } else {
        r = (parseInt(delta / 86400)).toString() + ' days ago';
  }

  	  return r;
	}

});	


// Create Usable Links

String.prototype.linkify = function() {
       return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&\?\/.=]+/, function(m) {
              return m.link(m);
      });
};