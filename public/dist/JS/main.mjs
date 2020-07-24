import * as Tables from './Modules/Tables.mjs';
import * as Tooltips from './Modules/Tooltips.mjs';
import * as Characters from './Modules/CharacterForms.mjs';
import * as Guilds from './Modules/GuildForms.mjs'

function init() {
    Tables.init();
    Tooltips.init();
    Characters.init();
    Guilds.init();
}

$(document).ready(function() {
    init();
});
