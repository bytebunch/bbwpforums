jQuery(document).ready(function($) {
	
	$(".topic_form").submit(function(){
		CKEDITOR.instances.editor1.updateElement();
		var title = $(".bb_title").val();
		title = title.trim();
		var content = $("#editor1").val();
		content = content.trim();
		if(title == '' || title == " " || content == '' || content == " ")
		{
			$(".form_message").html("You must specify topic subject and your message.");
			return false;
		}
	});
	
	
	
	$(".topic_reply form").submit(function(){
		CKEDITOR.instances.editor1.updateElement();
		var content = $("#editor1").val();
		content = content.trim();
		if(content == '' || content == " ")
		{
			$(".form_message").html("You must specify your message.");
			return false;
		}
	});
	
	
	$(".ckeditor_smileys img").click(function(e){
		e.preventDefault();
		var img_title = $(this).attr("alt");
		var img_html = '<img src="'+$(this).attr("src")+'" alt="'+img_title+'" title="'+img_title+'" />';
		var element = CKEDITOR.dom.element.createFromHtml( img_html );
		CKEDITOR.instances.editor1.insertElement(element);
		return false;
	});
	
	
	/*$(".clickme").click(function(){
		if(CKEDITOR)
		{
			//editor_data = ':)';
			editor_data = '<blockquote>Dummy text for ckeditor</blockquote>';
			var element = CKEDITOR.dom.element.createFromHtml( editor_data );
			CKEDITOR.instances.editor1.insertElement(element);
			
			CKEDITOR.instances.editor1.insertElement(element);
		}
		return false;
	});*/
	
	
	
    $(document).on("click", ".bb_quote_link", function(e){
	
	//alert("sdf");
	if (jQuery("#editor1").length > 0) {
		
		e.preventDefault();
		
		var id = jQuery(this).attr("href").substr(1);
		var quote_id = '.post_'+id+' .bbf_reply_content';
		qout = jQuery(quote_id).html();
	
		qout = qout.replace(/&nbsp;/g, " ");
		/*qout = qout.replace(/<p>/g, "");
		qout = qout.replace(/<\/\s*p>/g, "");
		qout = qout.replace(/(\r\n|\n|\r)/gm," ");
		qout = qout.replace(/\s+/g," ");*/
		

		
			
			qout = '<div><span> Originally Posted by <strong>'+ jQuery(this).attr("data-bbp-author")+ '</strong></span>' + qout + '</div>';
			if(CKEDITOR)
			{
				var element = CKEDITOR.dom.element.createFromHtml( qout );
				CKEDITOR.instances.editor1.insertElement(element);
				var editor_data = CKEDITOR.instances.editor1.getData();
			}
			
			/*var txtr = jQuery("#bbp_reply_content");
			var cntn = txtr.val();

			if (jQuery.trim(cntn) != '') {
				qout = "\n\n" + qout;
			}

			txtr.val(cntn + qout);*/

		var old_ie = jQuery.browser.msie && parseInt(jQuery.browser.version) < 9;

		if (!old_ie) {
			jQuery("html, body").animate({scrollTop: jQuery("#new-post").offset().top}, 1000);
		} else {
			document.location.href = "#new-post";
		}
	}
});
});
