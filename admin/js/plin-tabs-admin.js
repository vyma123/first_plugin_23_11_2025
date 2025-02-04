jQuery(document).ready(function($) {
    $("#addBtn").click(function() {
        console.log('cd');

        const addBtn = document.getElementById("inputFields");
        addBtn.innerHTML += `
        <div>
        <input type="text" name="new_tab_names[]">
        </div>
        `;
    });
});
