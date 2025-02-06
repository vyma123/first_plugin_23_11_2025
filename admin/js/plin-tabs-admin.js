jQuery(document).ready(function($) {
    $("#addBtn").click(function(e) {
        e.preventDefault();

        let newTabInput = $('<input type="text" name="new_tab_names[]" placeholder="Enter tab name">');
        let newPriorityInput = $('<input type="number" name="new_tab_priorities[]" placeholder="Priority" value="">');

        let newInputGroup = $("<div>").append(newTabInput).append(newPriorityInput);
        $("#inputFields").append(newInputGroup);
        
        newTabInput.focus();
    });

    $("form").submit(function(e) {
        let isValid = true;

        $("input[name='new_tab_names[]']").each(function() {
            if ($(this).val().trim() === "") {
                isValid = false;
                $(this).css("border", "2px solid red");
            } else {
                $(this).css("border", "");
            }
        });

        if (!isValid) {
            alert("Please fill out this field");
            e.preventDefault(); 
            return;
        }
    });
});
