export function init() {
    initDatatable('current-epgp-standings');
    initDatatable('character-management');
}

function initDatatable(id) {
    const table = $('#' + id);
    if (table.length > 0) {
        table.DataTable();
    }
}

