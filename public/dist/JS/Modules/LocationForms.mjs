export function init() {
    prepareLocationEventListeners();
}

function prepareLocationEventListeners() {
    $('#add-location').on('click', function(event) {
        clearLocationForm();
        $('#location-add-modal').modal('show');
    });

    $('#location-save').on('click', function(event) {
        $.post(
            '/locations/create',
            {
                "location": $('#location-form').serialize()
            },
            function (data) {
                if (data.success) {
                    let newRow = '<tr>' +
                        '  <td>' + data.location.name + '</td>' +
                        '</tr>';
                    let table = new $.fn.dataTable.Api('#location-management');
                    table.row.add($(newRow)).draw();
                } else alert('Something went wrong, ' + data.location.name + 'has not been saved.');
                $('#location-add-modal').modal('hide');
            },
            'json'
        );
    });
}

function clearLocationForm() {
    $('#name').val('');
}
