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
 * Javascript main event handler.
 *
 * @module     local_quizpreset/main
 * @package    local_quizpreset
 * @copyright  2019 Devlionco <info@devlion.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      3.6
 */

define([
  'jquery',
  'local_quizpreset/preset',
], function($, Preset) {

    var Selector = {
        MODULE_NAME: '#region-main .mform input[name="modulename"]',
        ADD_INPUT: '#region-main .mform input[name="add"]',
        UPDATE_INPUT: '#region-main .mform input[name="update"]',
        TOP_PAGE_ELEMENT: 'body',
        MOD_QUIZ_VIEW: '#page-mod-quiz-view',
    };
    var Class = {
        QUIZ: 'path-mod-quiz',
        VISIBLE: '',
        HIDDEN: 'hidden',
    };

    return {
        init: function() {
            if ($(Selector.TOP_PAGE_ELEMENT).hasClass(Class.QUIZ)) {
                var modulename = $(Selector.MODULE_NAME).val();

                if ($(Selector.TOP_PAGE_ELEMENT).is(Selector.MOD_QUIZ_VIEW)) {
                    Preset.configureViewQuizPage();
                }

                if (modulename == 'quiz') {
                    var addinput = $(Selector.ADD_INPUT).val();
                    var updateinput = $(Selector.UPDATE_INPUT).val();

                    if (addinput == 'quiz' && Number(updateinput) == 0) {
                        Preset.configureAddQuizPage();
                    }

                    if ((!addinput || addinput == 0 || addinput == '') && Number(updateinput) > 0) {
                        Preset.configureUpdateQuizPage();
                    }
                }
            }
        }
    };
});
