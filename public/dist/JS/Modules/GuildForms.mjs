export function init() {
    prepareGuildEventListeners();
}

function prepareGuildEventListeners() {
    $('#add-guild').on('click', function(event) {
        clearGuildForm();
        $('#guild-add-modal').modal('show');
    });

    $('#guild-save').on('click', function(event) {
        $.post(
            '/guilds/create',
            {
                "guild": $('#guild-form').serialize()
            },
            function (data) {
                if (data.success) {
                    let newRow = '<tr>' +
                        '  <td>' + data.guild.name + '</td>' +
                        '  <td>' + data.guild.logo + '</td>' +
                        '</tr>';
                    let table = new $.fn.dataTable.Api('#guild-management');
                    table.row.add($(newRow)).draw();
                } else alert('Something went wrong, ' + data.guild.name + 'has not been saved.');
                $('#guild-add-modal').modal('hide');
            },
            'json'
        );
    });
}

function clearGuildForm() {
    $('#name').val('');
    $('#logo').val('');
}
