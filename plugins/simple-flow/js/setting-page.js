<script type="text/javascript">
    function setElementId(element, id) {
            var newId = "front-page-element-" + id;    

            jQuery(element).attr("id", newId);               

            var inputField = jQuery("select", element);
            inputField.attr("name", "element-page-id-" + id); 

            var labelField = jQuery("label", element);
            labelField.attr("for", "element-page-id-" + id); 
        }
        
    jQuery(document).ready(function() {
        
        var elementCounter = jQuery("input[name=element-max-id]").val();
        if(typeof(elementCounter)==='undefined') {
            var elementCounter = 0;
        }
        
        jQuery("#featured-posts-list").sortable( {
            stop: function(event, ui) {
               var i = 0;

               jQuery("li", this).each(function() {
                   setElementId(this, i);                    
                   i++;
               });

               elementCounter = i;
               jQuery("input[name=element-max-id]").val(elementCounter);
           }
        });             
        
        jQuery("#add-featured-post").click(function() {
            var elementRow = jQuery("#front-page-element-placeholder").clone();
            var newId = "front-page-element-" + elementCounter;
                
            elementRow.attr("id", newId);
            elementRow.show();
                
            var inputField = jQuery("select", elementRow);
            inputField.attr("name", "element-page-id-" + elementCounter); 
                 
            var labelField = jQuery("label", elementRow);
            labelField.attr("for", "element-page-id-" + elementCounter); 
 
            var removeLink = jQuery("a", elementRow).click(function() {
            removeElement(elementRow);  
                return false;
            });
            
            elementCounter++;
            
            jQuery("input[name=element-max-id]").val(elementCounter);
                 
            jQuery("#featured-posts-list").append(elementRow);
                
            return false;
        });
               
    });
    function removeElement(element) {
                jQuery(element).remove();
            }
</script>

