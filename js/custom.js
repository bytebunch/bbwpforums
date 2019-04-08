function contact_us_map(mapdivid, map_lat, map_lng)
{
	jQuery(document).ready(function($){
		if($("#"+mapdivid).length > 0)
		{
			var map3;
			var markers3 = [];
			var map_coor_lat3 = map_lat;
			var map_coor_lng3 = map_lng;
			var mapCenter3 = new google.maps.LatLng(map_coor_lat3, map_coor_lng3);
			var myOptions3 = {
				zoom: 15,
				center: mapCenter3,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			  }

			  map3 = new google.maps.Map(document.getElementById(mapdivid), myOptions3);

			  markers3.push(new google.maps.Marker({
				position: mapCenter3,
				map: map3,
				//icon: bbblog.theme_uri+"images/address_icon.png"
			  })); // markerss.push function end here

			map3.setCenter(new google.maps.LatLng(31.423897, 74.370940));


			var contentString = '<div id="content">'+
				  '<div id="bodyContent" class="map_info_window_content">'+
				  '<p style="margin-bottom:20px">House No 87, Salman Block, Nishter Colony, Lahore, Pakistan</p>'+
				  '<p><span class="phone_icon"></span>+92 324 4874422</p>'+
				  '<p><span class="email_icon"></span>contact@bytebunch.com</p>'+
				  '</div>'+
				  '</div>';

  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });
  infowindow.open(map3,markers3[0]);


		 }
	});
} // api for single map function end here

contact_us_map("contact_us_map",31.423664,74.360317);

function get_live_chat_data()
{
	jQuery(document).ready(function($) {
        $.ajax({
					beforeSend : function(){$(".live_chat_form").find(".ajax_loader").show();},
					type: 'POST',
				    url: ajax_url,
					data: {
							action: 'livechat_ajax'
					      },
				    success:function(result){
									  		$(".live_chat_form").find(".live_chat_box_content").html(result);
												setTimeout(get_live_chat_data, 30000);
											},
					 error: function(errorThrown)
									{
									  //alert(errorThrown);
									}
				}).done(function() {
					  $(".live_chat_form").find(".ajax_loader").hide();
					});
    });
}


/******************************************/
/***** html 5 confirm password validation **********/
/******************************************/
jQuery(document).ready(function($) {


	$(".bbf .bbf_reply_content div.spoiler_container .show_spoiler_button").live("click", function(){
		$(this).parent("div").find(".spoiler_content").first().toggle();
	});

	$('.delete_this').click(function(){
		var r = confirm("Are you sure.");
		if (r == true) {
			return true;
		} else {
			return false;;
		}
	});



	if($("#password").length > 0 && $("#cpassword").length > 0)
	{
		var password = document.getElementById("password");
		var confirm_password = document.getElementById("cpassword");

		function validatePassword(){
		  if(password.value != confirm_password.value) {
			confirm_password.setCustomValidity("Passwords Don't Match");
		  } else {
			confirm_password.setCustomValidity('');
		  }
		}

		password.onchange = validatePassword;
		confirm_password.onkeyup = validatePassword;
	}

	if($(".live_chat_form").length > 0 && nodejs_live_chat != 1){
		setTimeout(get_live_chat_data,30000);
	}
	if(nodejs_live_chat == 1 && $(".live_chat_form").length > 0){
		var socket = io.connect( 'http://www.bytebunch.com:8000' );
		if(socket){
			socket.on( 'message', function( data ) {
				var first_li_class = $( ".live_chat_box_content ul li" ).first().attr('class');
				if(first_li_class == 'odd'){ first_li_class = 'even'; }else{ first_li_class = 'odd'; }

				//var actualContent = $( ".live_chat_box_content ul" ).html();
				var newMsgContent = '<li class="'+first_li_class+'"> <div class="live_chat_profile"><a href="'+page_users_url+data.userid+'">'+data.name+'</a></div>';
				newMsgContent += '<div class="live_chat_body"><p class="posted_date">'+data.date+'</p><p class="posted_conent">'+data.message.replace(/\\\"/g,"")+'</p></div><div class="clearboth"></div></li>';
				$( ".live_chat_box_content ul" ).prepend(newMsgContent);
				//var content = newMsgContent + actualContent;
				//$( ".live_chat_box_content ul" ).html( content );
			});
		}
	}

	$(".live_chat_form").submit(function(e){
		e.preventDefault();
		var current_form = $(this);
		var live_chat_message = $(this).find(".live_chat_message").val();
		live_chat_message = live_chat_message.trim();
		if(live_chat_message != "" && live_chat_message != " " && live_chat_message != false)
		{
			$.ajax({
					beforeSend : function(){current_form.find(".ajax_loader").show(); current_form.find(".live_chat_message").attr("value","");},
					type: 'POST',
				  url: ajax_url,
					data: {
						action: 'livechat_ajax',
		    		live_chat_message: live_chat_message,
						user_id: current_form.find('input.user_id').val()
					},
				  success: function(result){
						if(nodejs_live_chat != 1){
							current_form.find(".live_chat_box_content").html(result);
						}
					},
					error: function(errorThrown){
						//alert(errorThrown);
					}
		}).done(function() {
				current_form.find(".ajax_loader").hide();
			});
		}

		return false;
	});

});
