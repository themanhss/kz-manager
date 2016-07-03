jQuery(document).ready(function()
{

	//selcet all 
	jQuery('.select_all').click(function() {
		jQuery("input:checkbox").attr('checked','checked');
		jQuery.uniform.update();	
			return false;
	});
	
	
	jQuery('.deselect_all').click(function() {
		jQuery("input:checkbox").attr('checked','checked');
		jQuery('input:checkbox').removeAttr('checked');
		jQuery.uniform.update();
			return false;
	});
	
	//tabs
	//jQuery("ul.tabs").tabs("div.panes > div.contain");
	//close link
	function activate_close(){
		jQuery('.close').click(function(){
			
			jQuery(this).parent().fadeOut('slow');
			
		});
		
	}
	
	
    //Style selects, checkboxes, etc
	jQuery(".TTWForm select:not('.translate_t,.no-unify'), .TTWForm input:radio, .TTWForm input:file ,.TTWForm input:checkbox").uniform();

    //Date and Range Inputs
	jQuery("#field1, #field2 , #field255, #field256, #field1s, #fieldz1,#field5zzzg, #fieldlimit, #fieldlimit2,#random_tags_count_field,#cg_minimum_width_f").rangeinput();

    /**
     * Get the jQuery Tools Validator to validate checkbox and
     * radio groups rather than each individual input
     */

	jQuery('[type=checkbox]').bind('change', function(){
        clearCheckboxError(jQuery(this));
    });
    
    
    //campaign type selector
    function showHide(){
    	
    	jQuery('.typepart').slideUp();
    	
    	jQuery('.'+ jQuery('#camp_type').val() ).slideDown();
    	
    }
    
    function updateTemplate(){
    	jQuery('textarea[name="camp_post_content"]').text(jQuery('.temp' + jQuery('#camp_type').val()).html());
    	
    }
    
    showHide();
    
    jQuery('#camp_type').change(function(){
        
        showHide();
        updateTemplate();
    });
    
    //slider function
    function slider(id,slide) {
 
		  
		if(jQuery(id).attr("checked"))
        {
            //call the function to be fired
            //when your box changes from
            //unchecked to checked
			jQuery(slide).slideDown();
        }
        else
        {
            //call the function to be fired
            //when your box changes from
            //checked to unchecked
        	jQuery(slide).slideUp();
        }
		jQuery.uniform.update();
	}
    
    function Deslider(id,slide , show  ) {
 	  
		if(jQuery(id).attr("checked"))
        {
             
			//if visible slideup
			 
			jQuery(slide + ':visible').slideUp();
			jQuery('#field111').val('*');
        }
        else
        {
            //call the function to be fired
            //when your box changes from
            //checked to unchecked
        	
        	
        	
        	if(jQuery(slide).length == 0){
			
        		jQuery(slide + ':hidden').slideDown();
        		
        		 
			}
        	
        	
        	if(show == false){
        		
        	}else{
        		showHide();
        	}
        	
        	
        }
		jQuery.uniform.update();
	}
    
    //generic sliders
    jQuery('input[data-controls]').each(function(){
       
    	slider(this,'#'+jQuery(this).attr('data-controls'));
    	
        
        jQuery(this).change(function(){
        	slider(this,'#'+jQuery(this).attr('data-controls'));
        });
        
    });
    
    //flicker user fl_user
    slider('#fl_user','#fl_user_c');
    
    jQuery("#fl_user").change(function(){ 

    	slider('#fl_user','#fl_user_c');
    	
    	if(jQuery('#fl_user').attr("checked"))
        {
          
			 
        }
        else
        {
             //uncheck keyword without filtering
        	jQuery('#fl_full').removeAttr('checked');
        	jQuery('#fl_full').trigger('change');
        }
			
	});
    
    jQuery("#eb_user").change(function(){ 

    	 
    	
    	if(jQuery('#eb_user').attr("checked"))
        {
          
			 
        }
        else
        {
             //uncheck keyword without filtering
        	jQuery('#eb_full').removeAttr('checked');
        	jQuery('#eb_full').trigger('change');
        }
			
	});
        
    
    
    
    //custom fields folding cusom_fields_option

    slider('#cusom_fields_option','#custom_fields_c');
 
    jQuery("#cusom_fields_option").change(function(){ 

    	slider('#cusom_fields_option','#custom_fields_c');
			
	});

    
    //translate 
    
    slider('#translate_option','#translate_c');
 
    jQuery("#translate_option").change(function(){ 

    	slider('#translate_option','#translate_c');
			
	});
    
   
    //generic DeSliders
    jQuery('input[data-controls-r]').each(function(){
       
    	Deslider(this, '#field111-container' , false);
    	
        
        jQuery(this).change(function(){
        	Deslider(this,'#field111-container'  );
        });
        
    });
    
    
    //yt user
    slider('#yt_user','#yt_user_c');

  
    jQuery("#yt_user").change(function(){ 

    	slider('#yt_user','#yt_user_c');
    	
    	if(jQuery('#yt_user').attr("checked"))
        {
          
			 
        }
        else
        {
             //uncheck keyword without filtering
        	jQuery('#yt_full').removeAttr('checked');
        	jQuery('#yt_full').trigger('change');
        }
    	
			
	});
    
    
    //Youtube uncheck cache when published 
	jQuery('#camp_youtube_order').change(function(){

	  if(jQuery(this).val() == 'published' ){
	    jQuery('#OPT_YT_CACHE').prop('checked',false);
	    jQuery.uniform.update();	
	  }
	  
	});
    
    
    jQuery('input[data-controls-r]').change(function(){
    	
    	if(! jQuery(this).attr("checked"))
        {
          
    		if(jQuery('#field111').val() == '*' ){
    			 
    			jQuery('#field111').val('');
    		}
    		
			 
        }     	
    });

    
    //replace link
    slider('#replace_link','#replace_link_c');

  
    jQuery("#replace_link").change(function(){ 

		slider('#replace_link','#replace_link_c');
			
	});

    //exact match
    slider('#exact_option','#exact_match_c');

    
    jQuery("#exact_option").change(function(){ 

    	slider('#exact_option','#exact_match_c');
			
	});
    
    //execlude_option match
    slider('#execlude_option','#exact_execlude_c');

    
    jQuery("#execlude_option").change(function(){ 

    	slider('#execlude_option','#exact_execlude_c');
			
	});
    
    //validate checkbox and radio groups
    function validateCheckRadio(){
        var err = {};

        jQuery('.radio-group, .checkbox-group').each(function(){
             if(jQuery(this).hasClass('required'))
                if (!jQuery(this).find('input:checked').length)
                    err[jQuery(this).find('input:first').attr('name')] = 'Please complete this mandatory field.';
        });

        if (!jQuery.isEmptyObject(err)){
            validator.invalidate(err);
            return false;
        }
        else return true;

    }





    //clear any checkbox errors
    function clearCheckboxError(input){
        var parentDiv = input.parents('.field');

        if (parentDiv.hasClass('required'))
            if (parentDiv.find('input:checked').length > 0){
                validator.reset(parentDiv.find('input:first'));
                parentDiv.find('.error').remove();
            }
    }




    //Position the error messages next to input labels
    jQuery.tools.validator.addEffect("labelMate", function(errors, event){
        jQuery.each(errors, function(index, error){
        	
            error.input.first().parents('.field').find('.error').remove().end().find('label').after('<span class="error">' + error.messages[0] + '</span>');
        });

    }, function(inputs){
        inputs.each(function(){
            jQuery(this).parents('.field').find('.error').remove();
        });

    });


    /**
     * Handle the form submission, display success message if
     * no errors are returned by the server. Call validator.invalidate
     * otherwise.
     */

  // jQuery("#post").validator({effect:'labelMate'});
   
   jQuery("#publish").click(function(event){
  
	   //validating

	   if( jQuery('#camp_type').val() == 'Feeds' ){
		   
		   jQuery('textarea[name="feeds"]').attr('required','required');
		   jQuery('textarea[name="camp_keys"]').removeAttr('required');
		   var inputs = jQuery('textarea[name="feeds"]').validator({effect:'labelMate'});
		   
	   }else if(jQuery('#camp_type').val() == 'Spintax'){
		    
		   jQuery('textarea[name="camp_keys"]').removeAttr('required');
		   var inputs = jQuery('textarea[name="camp_post_content"]').validator({effect:'labelMate'});
		
	   }else if( jQuery('#camp_type').val() == 'Facebook' ){ 	   
		   
		   jQuery('input[name="cg_fb_page"]').attr('required','required');
		    
		   var inputs = jQuery('input[name="cg_fb_page"]').validator({effect:'labelMate'});
		   
		   
	   }else{
		   jQuery('textarea[name="feeds"]').removeAttr('required');
		   jQuery('textarea[name="camp_keys"]').attr('required','required');
		   var inputs = jQuery(".TTWForm-container :input").validator({effect:'labelMate'});
       }
	   
	      
	   //check if not valid stop loading ajax icons
	   if( ! inputs.data("validator").checkValidity()){
		   
		   event.stopImmediatePropagation();
		   
	   }else{
		   
		   if( jQuery('#camp_type').val() == 'Youtube' ){
			   
			   
			   
		   }
		   
	   }
	   
	   
	   
   });

 //SUPPORTED TAGS
   function supportedUpdate(){
	   
	   if(typeof supportedTags != 'undefined')  {
	   
	   var supportedText='';
	   jQuery(supportedTags[jQuery('#camp_type').val()]).each(function(index,value ){
	       
	       if(index != 0 ) { supportedText = supportedText + ' , ' ;}
	       
	       supportedText = supportedText   + '<abbr title="' +  value[1] + '" >' + value[0] + '</abbr>' ; 
	   });
	   jQuery('.supportedTags').html('supported Tags: ' + supportedText );    

	   }
   }
   supportedUpdate();
   
   
   jQuery('#camp_type').change(function(){
	   supportedUpdate();
	});
   
   //CUSTOM FIELDS
   
   jQuery('#custom_new').click(function(){
	   jQuery('#custom_fields_c').append(jQuery('.custom_field_wrap').html());    
	   return false;
   });
    



   
    // run campaign 
    jQuery('#campaign_run').click(function(){

    	jQuery.ajax({
    	    url: jQuery(this).attr('href'),
    	    type: 'GET',
    	    cache: false,

    	    success: function (data) {
    	    	jQuery('#campaign_run').addClass('run').removeClass('run_load');

    	    	jQuery('#welcome-panel').remove();
    	    	jQuery('#status-meta-boxes .inside').append('<div style="display:none;margin-top:0" class="welcome-panel" id="welcome-panel"> '+ data +' <a href="#" class="welcome-panel-close">Dismiss</a><div class="welcome-panel-content"></div></div>');
    	    	jQuery('#welcome-panel').slideDown('slow',function(){
    	    		
    	    		//handle close button 
    	    		jQuery('.welcome-panel-close').click(function(){
    	    			jQuery('#welcome-panel').remove();
    	    			return false;
    	    		});
    	    		
    	    		
    	    		//update records if new post
    	    		if(jQuery('.new_post_link').length >0){
     	    		   
       	    		 jQuery('#last_run_date').css('background','yellow').fadeOut('slow',function(){
       	    			 	jQuery('#last_run_date').html( jQuery('.new_post_link').attr('time'));}).fadeIn('slow',function(){jQuery(this).css('background','transparent'); 
       	    			    jQuery('#last_post').css('background','yellow').fadeOut('slow',function(){jQuery('#last_post').html( '<a href="'+ jQuery('.new_post_link').attr('href') +'">'+jQuery('.new_post_link').html()+'</a>' ) ; }).fadeIn('slow',function(){jQuery(this).css('background','transparent'); });
       	    		 });
       	    		 	
       	    		   jQuery('#posted-meta-box .inside p').after('<div class="posted_itm"><a href="'+jQuery('.new_post_link').attr('href')+'">'+jQuery('.new_post_link').html()+'</a><br>on <small>'+jQuery('.new_post_link').attr('time')+'</small><br></div>') ;
       	    		   jQuery('#posted-meta-box .inside p strong').html('(' + jQuery('.posted_itm').length +')') ;
    	    		}
    	    		
    	    	});
    	    
    	    

    	    },

    	    beforeSend: function () {
    	    	
    	    	jQuery('#campaign_run').addClass('run_load').removeClass('run');
    	    	jQuery('#welcome-panel').slideUp('slow');
    	    }


    	});
    	return false;
    	});
    
    	/**
    	 * Show valid cat and hide non valid ones
    	 */
    	function updateUICategory(){
	        console.log(jQuery('select[name="camp_post_type"]').val());
	        jQuery('.post_category').hide();
	        jQuery('.post_' + jQuery('select[name="camp_post_type"]').val() ).show();
	    }
    	
    	/**
    	 * Take the visible category taxonomy and set it to the field 
    	 */
    	function updateTax(){
            jQuery('#cg_camp_tax').val( jQuery('select[name="camp_post_category[]"] option[value="' + jQuery('select[name="camp_post_category[]"]').val()[0] + '"]:visible').attr('data-tax')   );  

    	}
    	
    	updateUICategory();
    	jQuery('select[name="camp_post_type"]').change(function(){
	    	
	    	//hide non used and show used cate
	    	updateUICategory();
	    	
	    	//select first visible item
	    	jQuery('select[name="camp_post_category"]').val(jQuery('select[name="camp_post_category"] option:visible').eq(0).val());
	    	
	    	//update taxononmy
	    	updateTax();
	    
	    });
    	
    	//change tax when category change
    	jQuery('select[name="camp_post_category[]"]').change(function(){
    		updateTax();
    	});
    	
    	
    	//reactivate key ajax
    	jQuery(document).ready(function() {
			jQuery('#status-meta-boxes').on('click','.wp_automatic_key_reactivate',function() {
				
				var dataKey =jQuery(this).attr('data-key');
				jQuery('.spinner_' + dataKey ).show();
				
				jQuery.ajax({
					url : ajaxurl,
					type : 'POST',

					data : {
						action : 'wp_automatic_reactivate_key',
						id     : jQuery(this).attr('data-id') , 
						key    : dataKey  

					},
					
					success:function(data){
						jQuery('.spinner_' + dataKey ).hide();
						alert(data);
					}
				});

				return false;

			});
		});
    	
    	//fetch yt user playlists 
    	jQuery('#yt_playlist_update').click(function(){
    		jQuery('.spinner-playlist').show();
    		jQuery.ajax({
				url : ajaxurl,
				type : 'POST',
				dataType: 'json',

				data : {
					action : 'wp_automatic_yt_playlists',
					user   : jQuery('#camp_yt_user').val(),
					pid    : jQuery('#wp_automatic_post_id').val()

				},
				
				success: function(data){
					jQuery('.spinner-playlist').hide();
					jQuery('#cg_yt_playlist option').remove();
					
					jQuery(data['data']).each(function(index,val){
						 
						 if(jQuery('#cg_yt_playlist option[value="'+ val['id'] +'"]').length == 0 ){
							 
							 //append it
							 jQuery('#cg_yt_playlist').append('<option value="'+ val['id']+'">'+ val['title'] +'</option>');
							 
							 
						 }
 
						
					});
				}
				
			});
    		
    		
    		
    		return false;
    		
    	});
    	
    	//Specify playlist trigger update
		jQuery('#wp_automatic_playlist_opt').click(function(){

			  if(jQuery(this).attr("checked")){
			    
			    jQuery('#yt_playlist_update').trigger('click');
			    
			    
			    if(!jQuery('#yt_full').attr('checked') ){
			    	jQuery('#yt_full').trigger('click');
			    } 
			    
			    
			    
			    jQuery.uniform.update();
			  
			  }
			  
		}); 
		
		
		//update chosen playlist id
		jQuery('#cg_yt_playlist').click(
				function(){

					jQuery('#cg_yt_playlist_txt').val(jQuery(this).val());

		});
		
		//more_posted_posts load more posts button
		jQuery('#more_posted_posts').click(function(){
			
			//show spinner 
			jQuery('.spinner-more_posted_posts').addClass('is-active');
			
			jQuery.ajax({
				url : ajaxurl,
				type : 'POST',

				data : {
					action : 'wp_automatic_more_posted_posts',
					camp     : jQuery(this).attr('data-camp') , 
					page    : jQuery(this).data('page')    

				},
				
				success:function(data){
					 
					jQuery('.latest-posts-container').append(data);
					
					jQuery('.spinner-more_posted_posts').removeClass('is-active');
				}
			});
			
			//update page 
			jQuery(this).data('page',jQuery(this).data('page') + 10 )   ;

			return false;

			
		});
		
	     
	     
});
