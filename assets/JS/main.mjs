import * as Tables from './Modules/Tables.mjs';
import * as Tooltips from './Modules/Tooltips.mjs';
import * as Characters from './Modules/CharacterForms.mjs';
import * as Guilds from './Modules/GuildForms.mjs';
import * as Locations from './Modules/LocationForms.mjs';
import * as Bosses from './Modules/BossForms.mjs';

function init() {
    Tables.init();
    Tooltips.init();
    Characters.init();
    Guilds.init();
    Locations.init();
    Bosses.init();
}

$(document).ready(function() {
    init();
});
