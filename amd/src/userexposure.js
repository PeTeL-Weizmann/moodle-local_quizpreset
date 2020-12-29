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
 * Javascript to initialise the myoverview block.
 *
 * @package    local_quizpreset
 * @copyright  2018 Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define([
  'jquery',
  'core/ajax',
  'core/notification',
  'core/str'
],
function($, Ajax, Notification, Str) {

    return {
        selector: {
            button: '#quiz-grade-button',
            hidden: '#quiz-grade',
        },

        /**
         * Initialise all of the modules for the overview block.
         *
         * @param {object} courseids The root element for the overview block.
         */
        init: function() {

            var root = $(this.selector.button),
                self = this;

            root.on('click', function(e) {
                self.changeGrades();
            });
        },

        changeGrades: function() {

            var self = this;
            Str.get_strings([
                {key: 'buttondisablegrades', component: 'local_quizpreset'},
                {key: 'buttonenablegrades', component: 'local_quizpreset'}
            ]).done(function(strings) {

                var grade = $(self.selector.hidden).val();
                if(grade == 0){
                    $(self.selector.hidden).val(1);
                    $(self.selector.button).html(strings[0]);
                    $(self.selector.button).removeClass("btn-primary").addClass("btn-secondary");

                    $("#id_marksduring").prop( "checked", true );
                    $("#id_marksimmediately").prop( "checked", true );
                    $("#id_marksopen").prop( "checked", true );
                    $("#id_marksclosed").prop( "checked", true );

                    $("#id_timeclose_day").prop("disabled", false);
                    $("#id_timeclose_month").prop("disabled", false);
                    $("#id_timeclose_year").prop("disabled", false);
                    $("#id_timeclose_hour").prop("disabled", false);
                    $("#id_timeclose_minute").prop("disabled", false);

                    if (!$("#id_timeclose_enabled").is(":checked")){
                        $("#id_timeclose_enabled").click();
                    }

                    self.setDateTime("timeclose");
                }

                if(grade == 1){
                    $(self.selector.hidden).val(0);
                    $(self.selector.button).html(strings[1]);
                    $(self.selector.button).removeClass("btn-secondary").addClass("btn-primary");

                    $("#id_marksduring").prop( "checked", false );
                    $("#id_marksimmediately").prop( "checked", false );
                    $("#id_marksopen").prop( "checked", false );
                    $("#id_marksclosed").prop( "checked", false );

                    if ($("#id_timeclose_enabled").is(":checked")){
                        $("#id_timeclose_enabled").click();
                    }
                }

            }).fail(Notification.exception);

        },

        setDateTime: function(name) {
        var dt = new Date();
        var month = dt.getMonth() + 1;

            $("[name=\""+name+"[hour]\"] option[value="+dt.getHours()+"]").prop("selected", "selected");
            $("[name=\""+name+"[minute]\"] option[value="+dt.getMinutes()+"]").prop("selected", "selected");
            $("[name=\""+name+"[year]\"] option[value="+dt.getFullYear()+"]").prop("selected", "selected");
            $("[name=\""+name+"[month]\"] option[value="+month+"]").prop("selected", "selected");
            $("[name=\""+name+"[day]\"] option[value="+dt.getDate()+"]").prop("selected", "selected");
        }

    };
});
