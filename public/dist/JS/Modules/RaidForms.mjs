export function init() {
    prepareTableFilters();
    prepareModalActions();
    prepareTableActions();
    prepareBossDetailActions();
}

function prepareTableFilters() {
    const api = new $.fn.dataTable.Api('#raid-management');

    const locationSelect = $('#location-filter-raids');

    locationSelect.on('change', function(event) {
        const val = $.fn.dataTable.util.escapeRegex(
            $(this).val()
        );

        api.column(2)
            .search( val ? '^'+val+'$' : '', true, false )
            .draw();
    });
}

function prepareTableActions() {
    const table = $('#raid-management');
    table.on('click', '.view-raid', function(event) {
        const raid = event.currentTarget.dataset.id;
        window.location = '/raids/detail?id=' + raid;
    });
    table.on('click', '.edit-raid', function(event) {
        const raid = event.currentTarget.dataset.id;
        window.location = '/raids/detail?id=' + raid;
    });
}

function prepareModalActions() {
    const modal = $('#raid-add-modal');

    $('#create-raid').on('click', function(event) {
        modal.modal('show');
    });

    $('#save-raid').on('click', function(event) {
        $.post(
            '/raids/create',
            {
                'location': $('#location').val()
            },
            function (data) {
                if (data.success) {
                    window.location = '/raids/detail?id=' + data.raid.id;
                } else {
                    alert('Failed to create raid entry.');
                    modal.modal('hide');
                }
            },
            'json'
        )
    });
}

function prepareBossDetailActions() {
    $('#boss-detail').on('click', '.boss-action', function(event) {
        const action = event.currentTarget.dataset.action;
        const bossData = {
            'action': action,
            'boss': event.currentTarget.dataset.boss,
            'raid': event.currentTarget.dataset.raid
        };
        $.post(
            '/raids/boss/update',
            bossData,
            function (data) {
                if (data.success) {
                    const boss = $('#raid-boss-' + data.boss.id);
                    boss.toggleClass('text-success');
                    const bossNameSpan = boss.find('.boss-detail-row');
                    let bossName = '&num;' + bossNameSpan.attr('data-order') + ' &ndash; ' + bossNameSpan.attr('data-bossname');
                    if (action === 'mark-alive') {
                        bossNameSpan.html(bossName);
                    } else bossNameSpan.html('<del>' + bossName + '</del>');
                } else alert(data.message);
            },
            'json'
        )
    });
}
