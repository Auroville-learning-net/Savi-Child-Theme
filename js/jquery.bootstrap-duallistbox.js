function move_list_items(sourceid, destinationid)
{
			   //alert(jQuery("#"+sourceid+" option:selected").size());
               if(destinationid == "to_select_list"){  
               
                         jQuery("#"+sourceid+"  option:selected").appendTo("#"+destinationid);
                         jQuery("#"+destinationid+" option").attr("selected",true);
               }
               else {
                    jQuery("#"+sourceid+"  option:selected").appendTo("#"+destinationid);  
                    jQuery("#"+sourceid+" option").attr("selected",true);
               }
}
jQuery( document ).ready(function() {
jQuery("#to_select_list option").attr("selected",true);
});


