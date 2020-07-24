export function init() {
    prepareGuildEventListeners();
}

function prepareGuildEventListeners() {
    $('#add-guild').on('click', function(event) {
        clearGuildForm();
        $('#guild-add-modal').modal('show');
    });
}

function clearGuildForm() {
    $('#name').val('');
    $('#logo').val('');
}
