jQuery(document).ready(function ($) {
    let tabCount = 0;
    $('#addBtn').click(function (e) {
        e.preventDefault();
        tabCount++;
    $('#add_new_tab').removeClass('d-none');
        $('#inputFields').append(`
            <div class="ui form input-group">
                <div class="fields">
                    <div class="field">
                        <label>Name</label>
                        <input type="text" name="new_tab_names[]" placeholder="Tab Name" class="required-field">
                        <p class="text-danger d-none required">This field is required</p>
                    </div>
                    <div class="field">
                        <label>Priority</label>
                        <input type="number" name="new_tab_priorities[]" placeholder="Priority">
                    </div>
                </div>
            </div>
        `);
        $('#added_new_tab').removeClass('d-none');
        $('#no_change_detected').addClass('d-none');
        $('#tab_save_success').addClass('d-none');
        $('#added_new_tab').html(`<p><strong>Added ${tabCount} new tab${tabCount > 1 ? 's' : ''}</strong></p>`);
    });


    $('form').submit(function (e) {
        let isValid = true;
        $('.required-field').each(function () {

            if ($.trim($(this).val()) === '') {
                $(this).siblings('.required').removeClass('d-none');
                $('#added_new_tab').addClass('d-none');
                isValid = false;
            } else {
                $(this).siblings('.required').addClass('d-none');
                
            }
        });

        if (!isValid) {
            e.preventDefault(); 
        }
    });
});
