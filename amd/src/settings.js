// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Javascript settings handler.
 *
 * @module     local_quizpreset/settings
 * @package    local_quizpreset
 * @copyright  2019 Devlionco <info@devlion.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      3.6
 */

define([
    'jquery', 'local_quizpreset/jsoneditor'
], function ($, JSONEditor) {

    var Selector = {
        BODY_QUIZ: 'body#page-admin-setting-local_quizpreset',
        NUM_OF_TYPES: '#id_s_local_quizpreset_numberoftypes',
        QUIZ_PRESET_SRC: '#id_s_local_quizpreset_quizpreset_',
        JSON_FIELD1: '#admin-quizpreset_',
        JSON_FIELD2: ' .form-textarea',
        QUIZ_PRESET_SRC_DEF: '#id_s_local_quizpreset_quizdefaultsettings',
        JSON_FIELD_DEF: '#admin-quizdefaultsettings .form-textarea',
    };

    return {
        init: function () {
            for (var i = 1; i <= 6; i++) {
                if ($(Selector.QUIZ_PRESET_SRC + i).val() != undefined) {
                    var json = $(Selector.QUIZ_PRESET_SRC + i).val().trim();
                    if (!$(Selector.QUIZ_PRESET_SRC + i).val().trim()) {
                        $(Selector.QUIZ_PRESET_SRC + i).text(json);
                    }
                    var options = {
                        mode: 'code',
                        modes: ['code', 'form', 'text', 'tree'],
                        presetnumber: i,
                        onChangeText: function (jsonString) {
                            $(Selector.QUIZ_PRESET_SRC + this.presetnumber).text(jsonString);
                        }
                    };
                    var divEditorId = 'jsoneditor_' + i;
                    $(Selector.QUIZ_PRESET_SRC + i).hide();
                    $(Selector.JSON_FIELD1 + i + Selector.JSON_FIELD2).attr('id', divEditorId);
                    $('#' + divEditorId).css({'width': '100%', 'height': '400px'});

                    if (json.length == 0) {
                        json = '{}';
                    }

                    new JSONEditor(document.getElementById(divEditorId), options, JSON.parse(json));
                }
            }

        }
    };
});
