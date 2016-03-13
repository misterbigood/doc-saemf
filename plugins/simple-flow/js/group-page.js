<script type="text/javascript">
    jQuery(document).ready(function() {
        var elementCounter = jQuery("input[name=element-max-id]").val();
        if(typeof(elementCounter)==='undefined') {
            var elementCounter = 0;
        }
        jQuery("#add-group").click(function() {
            var elementRow = jQuery("#group-element-placeholder").clone();
            var newId = "group-element-" + elementCounter;
                
            elementRow.attr("id", newId);
            elementRow.show();
                
            var inputField = jQuery("input", elementRow);
            inputField.attr("name", "group-id-" + elementCounter); 
                 
            var labelField = jQuery("label", elementRow);
            labelField.attr("for", "group-id-" + elementCounter);
            
            var selectField = jQuery("select", elementRow);
            selectField.attr("name", "contact-id-" + elementCounter);
 
            var removeLink = jQuery("a", elementRow).click(function() {
            removeElement(elementRow);  
                return false;
            });
            
            elementCounter++;
            
            jQuery("input[name=element-max-id]").val(elementCounter);
                 
            jQuery("#groups-list").append(elementRow);
                
            return false;
        });             
    });
    function removeElement(element) {
                jQuery(element).remove();
            }
</script>

