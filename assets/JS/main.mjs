import * as Tables from './Modules/Tables.mjs';
import * as Tooltips from './Modules/Tooltips.mjs';
import * as Characters from './Modules/CharacterForms.mjs';

function init() {
    Tables.init();
    Tooltips.init();
    Characters.init();
}

$(document).ready(function() {
    init();
});
