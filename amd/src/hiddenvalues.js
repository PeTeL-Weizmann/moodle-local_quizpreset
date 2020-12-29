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
 * @module     local_quizpreset/hiddenvalues
 * @package    local_quizpreset
 * @copyright  2019 Devlionco <info@devlion.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      3.6
 */

define([
    'jquery',
], function ($) {

    var Selector = {
        TOP_PAGE_ELEMENT: 'body',
        HIDDEN_CMID: 'local-quizpreset-cmid',
    };
    var Class = {
        QUIZ: 'path-mod-quiz'
    };

    return {
        init: function (cmid, isstudent) {
            if ($(Selector.TOP_PAGE_ELEMENT).hasClass(Class.QUIZ)) {

                // Hidden input cmid.
                $('<input>').attr({
                    type: 'hidden',
                    id: Selector.HIDDEN_CMID,
                }).appendTo('body');

                $('#'+Selector.HIDDEN_CMID).val(cmid);
            }
        }
    };
});
