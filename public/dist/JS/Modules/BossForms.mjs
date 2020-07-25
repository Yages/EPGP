export function init() {
    prepareBossEventListeners();
}

function prepareBossEventListeners() {
    $('#add-boss').on('click', function(event) {
        clearBossForm();
        $('#boss-add-modal').modal('show');
    });


    $('#boss-form').on('change', '#location', function(event) {
        const max = $(event.target).find(':selected').attr('data-bosses');
        $('#boss-order').attr('max', max);
    });

    $('#boss-save').on('click', function(event) {
        $.post(
            '/bosses/create',
            {
                "boss": $('#boss-form').serialize()
            },
            function (data) {
                if (data.success) {
                    let newRow = '<tr>' +
                        '  <td>' + data.boss.name + '</td>' +
                        '  <td>' + data.boss.location + '</td>' +
                        '  <td>' + data.boss.effortPoints + '</td>' +
                        '  <td>' + data.boss.killOrder + '</td>' +
                        '</tr>';
                    let table = new $.fn.dataTable.Api('#boss-management');
                    table.row.add($(newRow)).draw();
                } else alert('Something went wrong, ' + data.message);
                $('#boss-add-modal').modal('hide');
            },
            'json'
        );
    });

    // Edit Toggle
    $('#boss-management').on(
        'click',
        '.edit-boss-details',
        function(event) {
            const boss = JSON.parse(event.currentTarget.dataset.boss);
            const modal = $('#boss-edit-modal');
            modal.find('.modal-title').html('Edit ' + boss.name + "'s Effort Points");
            $('#name-edit').val(boss.name);
            $('#effort-points-edit').val(boss.effortPoints);
            $('#boss-edit-save').attr('data-id', event.currentTarget.dataset.id);
            modal.modal('show');
        }
    );

    $('#boss-edit-modal').on('click', '#boss-edit-save', function(event) {
        const boss = $('#boss-edit-form').serialize();
        const id = $('#boss-edit-save').attr('data-id');
        $.post(
            '/bosses/edit',
            {
                'boss_id': id,
                'boss': boss
            },
            function (data) {
                if (data.success) {
                    let row = $('.edit-boss-details[data-id="' + id + '"]').closest('tr');
                    let newRow = '<tr>' +
                        '  <td>' + data.boss.name + '</td>' +
                        '  <td>' + data.boss.location + '</td>' +
                        '  <td>' + data.boss.effortPoints + '</td>' +
                        '  <td>' + data.boss.killOrder + '</td>' +
                        '  <td>' +
                        '     <button data-id="' + data.boss.id + '" data-boss=\'' + JSON.stringify(data.boss) + '\'  type="button" data-toggle="tooltip" title="Edit ' + data.boss.name + '\'s Effort Points" class="btn btn-sm btn-outline-primary edit-boss-details"><i class="fas fa-edit"></i></button>' +
                        '  </td>' +
                        '</tr>';
                    let table = new $.fn.dataTable.Api('#boss-management');
                    table.row(row).remove();
                    table.row.add($(newRow)).draw();
                } else alert('Something went wrong, ' + data.message);
                $('#boss-edit-modal').modal('hide');
            },
            'json'
        );
    });
}

function clearBossForm() {
    $('#name').val('');
    $('#location').val([]);
    $('#effort-points').val(0);
    $('#boss-order').val(1);
}
