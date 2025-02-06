jQuery(document).ready(function($) {
    $("#addBtn").click(function(e) {
        e.preventDefault();

        let newInputGroup = $(`
            <div class='ui form'>
                <div class='fields'>
                    <div class='field'>
                        <label>Name</label>
                        <input type='text' name='new_tab_names[]' placeholder='Tab Name'>
                        <p class="required d-none" id="required">Please fill out this field</p>
                    </div>
                    <div class='field'>
                        <label>Priority</label>
                        <input type='number' name='new_tab_priorities[]' placeholder='Priority'>
                    </div>
                </div>
            </div>
        `);

        $("#inputFields").append(newInputGroup);
        // newInputGroup.find("input[name='new_tab_names[]']").focus();
    });

    $("form").submit(function(e) {
        let isValid = true;

        $("input[name='new_tab_names[]']").each(function() {
            if ($(this).val().trim() === "") {
                isValid = false;
            } else {
                $(this).css("border", "");
            }
        });

        if (!isValid) {
        $('#required').removeClass('d-none'); 
            e.preventDefault(); 
        }
    });
});
