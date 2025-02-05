jQuery(document).ready(function($) {
    $("#addBtn").click(function(e) {
        e.preventDefault(); 

        $("#inputFields").append(`
            <div>
                <input type="text" name="new_tab_names[]" placeholder="Enter tab name">
            </div>
        `);
    });
});
