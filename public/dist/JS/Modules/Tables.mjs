export function init() {
    epgpDatatable();
}

function epgpDatatable() {
    let table = $('#current-epgp-standings');
    if (table.length > 0) {
        table.DataTable();
    }
}
