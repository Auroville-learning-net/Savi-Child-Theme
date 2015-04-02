function move_list_items(sourceid, destinationid)
{
			   //alert(jQuery("#"+sourceid+" option:selected").size());
               if(destinationid == "to_select_list"){  
                        jQuery("#span_opp_ids").hide();
                        var check_Data = jQuery("#link_left_opp_ids").attr('data-link');
                        
                        if( check_Data != undefined && check_Data != null ){
							//alert("check_Data:"+check_Data);
							var dataLINKS = jQuery("#link_left_opp_ids").attr("data-link")+"|"+
							    			jQuery("#link_opp_ids").attr('data-link');
							
						}else{
							var dataLINKS = jQuery("#link_opp_ids").attr('data-link');
						}
						jQuery("#link_opp_ids").attr('data-link',null);				
                        jQuery("#link_left_opp_ids").attr("data-link",dataLINKS);
                        jQuery("#span_left_opp_ids").show();
                         jQuery("#"+sourceid+"  option:selected").appendTo("#"+destinationid);
                         jQuery("#"+destinationid+" option").attr("selected",true);
               }
               else {
				      jQuery("#span_left_opp_ids").hide();
				      
				      var check_Data1 = jQuery("#link_opp_ids").attr('data-link');
				      //alert("check_Data1:"+check_Data1);
				       if(check_Data1 != undefined && check_Data1 != null){
							var dataLINKS = jQuery("#link_opp_ids").attr("data-link")+"|"+
											jQuery("#link_left_opp_ids").attr('data-link');
											
					   }else{
							var dataLINKS = jQuery("#link_left_opp_ids").attr('data-link');
						}
						jQuery("#link_left_opp_ids").attr('data-link',null);						
					  jQuery("#link_opp_ids").attr("data-link",dataLINKS);
					  jQuery("#span_opp_ids").show();
                      jQuery("#"+sourceid+"  option:selected").appendTo("#"+destinationid);  
                      jQuery("#"+sourceid+" option").attr("selected",true);
               }
}
jQuery( document ).ready(function() {
jQuery("#to_select_list option").attr("selected",true);
});


