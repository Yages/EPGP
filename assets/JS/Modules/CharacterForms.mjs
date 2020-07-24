export function init() {
    prepareCharacterEventListeners();
}

const characterTable = $('#character-management');
const editModal = $('#character-edit-modal');
const editModalTitle = $('#character-edit-modal-label');
const confirmModal = $('#character-confirm-modal');
const confirmModalTitle = $('#character-confirm-modal-label');
const confirmModalBody = confirmModal.find('.modal-body');
const confirmButton = $('#character-confirm');
const saveButton = $('#character-save');


function prepareCharacterEventListeners() {
    // Inactive Toggle
    characterTable.on(
        'click',
        '.mark-character-inactive',
        function(event) {
            const character = event.currentTarget.dataset;
            confirmModalTitle.html('Confirm Deactivation');
            confirmModalBody.html('<p>Are you sure you want to deactivate the character <b>' + character.name + '</b>?</p>');
            confirmButton.attr('data-character', JSON.stringify(character));
            confirmButton.attr('data-action', 'deactivate');
            confirmModal.modal('show');
        }
    );

    // Active Toggle
    characterTable.on(
        'click',
        '.mark-character-active',
        function(event) {
            const character = event.currentTarget.dataset;
            confirmModalTitle.html('Confirm Activation');
            confirmModalBody.html('<p>Are you sure you want to activate the character <b>' + character.name + '</b>?</p>');
            confirmButton.attr('data-character', JSON.stringify(character));
            confirmButton.attr('data-action', 'activate');
            confirmModal.modal('show');
        }
    );

    // Confirm
    confirmButton.on('click', function(event) {
        const characterData = JSON.parse(event.currentTarget.dataset.character);
        const action = event.currentTarget.dataset.action;
        if (action === 'deactivate') {
            deactivateCharacter(characterData);
        } else if (action === 'activate') {
            activateCharacter(characterData);
        }
    });

    // Edit Toggle
    characterTable.on(
        'click',
        '.edit-character-details',
        function(event) {
            const character = JSON.parse(event.currentTarget.dataset.character);
            editModalTitle.html('Edit ' + character.name + "'s Details");
            $('#name').val(character.name);
            $('#class').val(character.class);
            $('#role').val(character.role);
            $('#guild').val(character.guildId);
            saveButton.attr('data-action', 'edit');
            saveButton.attr('data-id', character.id);
            editModal.modal('show');
        }
    );

    editModal.on('click', '#character-save', function(event) {
        const formData = $('#character-form').serialize();
        const action = saveButton.attr('data-action');

        if (action === 'edit') {
            const id = saveButton.attr('data-id');
            editCharacter(id, formData);
        } else if (action === 'create') {
            createCharacter(formData);
        }
    });

    $('#add-character').on('click', function(event) {
        clearCharacterForm();
        saveButton.attr('data-action', 'create');
        editModal.modal('show');
    });
}

/**
 * Clears the character form of data.
 */
function clearCharacterForm() {
    editModalTitle.html('Add Character');

    $('#name').val('');
    $('#class').val([]);
    $('#role').val([]);
    $('#guild').val([]);
}

/**
 * Deactivates a character
 * @param character
 */
function deactivateCharacter(character) {
    $.post(
        '/characters/deactivate',
        {
            "character_id": character.id
        },
        function (data) {
            if (data.success) {
                let row = $('.mark-character-inactive[data-id="' + character.id + '"]').closest('tr');
                let table = new $.fn.dataTable.Api('#character-management');
                table.row(row).remove().draw();
                confirmModal.modal('hide');
            } else {
                confirmModal.modal('hide');
                alert('Something went wrong, ' + character.name + ' has not been deactivated.');
            }
        },
        'json'
    );
}

/**
 * Activates a character
 * @param character
 */
function activateCharacter(character) {
    $.post(
        '/characters/activate',
        {
            "character_id": character.id
        },
        function (data) {
            if (data.success) {
                let row = $('.mark-character-active[data-id="' + character.id + '"]').closest('tr');
                let table = new $.fn.dataTable.Api('#character-management');
                table.row(row).remove().draw();
                confirmModal.modal('hide');
            } else {
                confirmModal.modal('hide');
                alert('Something went wrong, ' + character.name + ' has not been activated.');
            }
        },
        'json'
    );
}

function createCharacter(character) {
    $.post(
        '/characters/create',
        {
            "character": character
        },
        function (data) {
            if (data.success) {
                let newRow = '<tr>' +
                    '  <td>' + data.character.name + '</td>' +
                    '  <td class="' + data.character.class.toLowerCase() + '">' + data.character.class + '</td>' +
                    '  <td>' + data.character.role + '</td>' +
                    '  <td>' + data.character.guild + '</td>' +
                    '  <td>' +
                    '   <button data-name="' + data.character.name + '" data-id="' + data.character.id + '" type="button" data-toggle="tooltip" title="Mark ' + data.character.name + ' as Inactive" class="btn btn-sm btn-outline-danger mark-character-inactive"><i class="fas fa-user-slash"></i></button>' +
                    '   <button data-id="' + data.character.id + '" data-character=\'' + JSON.stringify(data.character) + '\' type="button" data-toggle="tooltip" title="Edit ' + data.character.name + '\'s Details" class="btn btn-sm btn-outline-primary edit-character-details"><i class="fas fa-edit"></i></button>' +
                    '  </td>' +
                    '</tr>';
                let table = new $.fn.dataTable.Api('#character-management');
                table.row.add($(newRow)).draw();
            } else alert('Something went wrong, ' + data.character.name + 'has not been saved.');
            editModal.modal('hide');
        },
        'json'
    );
}

function editCharacter(id, character) {
    $.post(
        '/characters/edit',
        {
            "character_id": id,
            "character": character
        },
        function (data) {
            if (data.success) {
                let row = $('.edit-character-details[data-id="' + id + '"]').closest('tr');
                let newRow = '<tr>' +
                    '  <td>' + data.character.name + '</td>' +
                    '  <td class="' + data.character.class.toLowerCase() + '">' + data.character.class + '</td>' +
                    '  <td>' + data.character.role + '</td>' +
                    '  <td>' + data.character.guild + '</td>' +
                    '  <td>' +
                    '   <button data-name="' + data.character.name + '" data-id="' + data.character.id + '" type="button" data-toggle="tooltip" title="Mark ' + data.character.name + ' as Inactive" class="btn btn-sm btn-outline-danger mark-character-inactive"><i class="fas fa-user-slash"></i></button>' +
                    '   <button data-id="' + data.character.id + '" data-character=\'' + JSON.stringify(data.character) + '\' type="button" data-toggle="tooltip" title="Edit ' + data.character.name + '\'s Details" class="btn btn-sm btn-outline-success edit-character-details"><i class="fas fa-edit"></i></button>' +
                    '  </td>' +
                    '</tr>';
                let table = new $.fn.dataTable.Api('#character-management');
                table.row(row).remove();
                table.row.add($(newRow)).draw();
            } else alert('Something went wrong, ' + data.character.name + 'has not been saved.');
            editModal.modal('hide');
        },
        'json'
    );
}
