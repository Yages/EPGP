export function init() {
    const api = new $.fn.dataTable.Api('#loot-management');

    const locationSelect = $('#location-filter');
    const bossSelect = $('#boss-filter');

    locationSelect.on('change', function(event) {
        let val = $.fn.dataTable.util.escapeRegex(
            $(this).val()
        );

        api.column(2)
            .search( val ? '^'+val+'$' : '', true, false )
            .draw();
    });

    bossSelect.on('change', function(event) {
        let val = $.fn.dataTable.util.escapeRegex(
            $(this).val()
        );

        api.column(3)
            .search( val ? '^'+val+'$' : '', true, false )
            .draw();
    });
}

