export function init() {
    initDatatable('current-epgp-standings');
    initDatatable('character-management');
    initDatatable('guild-management');
    initDatatable('location-management');
    initDatatable('boss-management');
    initDatatable('loot-management');
    initDatatable('raid-management', {
        "order": [[ 1, "desc" ]]
    });
}

function initDatatable(id, options) {
    const table = $('#' + id);
    if (table.length > 0) {
        if (typeof options === 'undefined') {
            table.DataTable();
        } else table.DataTable(options);
    }
}

