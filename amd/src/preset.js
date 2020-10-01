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
 * @module     local_quizpreset/preset
 * @package    local_quizpreset
 * @copyright  2019 Devlionco <info@devlion.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      3.6
 */

define([
  'jquery',
  'core/templates',
  'core/ajax',
  'core/str',
  'core/notification',
  'local_quizpreset/userexposure',
  'local_quizpreset/loading'
], function($, Templates, Ajax, Str, Notification, Userexposure, loadingIcon) {

    var cmid = '';
    var pageState = '';

    var Selector = {
        TOP_PAGE_ELEMENT: 'body',
        MODULE_NAME: '#region-main .mform input[name="modulename"]',
        ADD_INPUT: '#region-main .mform input[name="add"]',
        UPDATE_INPUT: '#region-main .mform input[name="update"]',
        TYPE_SELECTOR_ANCOR1: '#region-main .mform .collapsible-actions',
        TYPE_SELECTOR_ANCOR2: '#region-main .quizinfo',
        FORM: '#region-main .mform',
        SECTIONS_ALL: '#region-main .mform fieldset',
        SECTIONS_HIDDEN: '#region-main .mform fieldset.hidden',
        SECTIONS_VISIBLE: '#region-main .mform fieldset:not(.hidden)',
        SECTIONS_COLLAPSED: '#region-main .mform fieldset.collapsed',
        SECTIONS_EXPANDED: '#region-main .mform fieldset:not(.collapsed)',
        SECTIONS_DEFAULT_VISIBLE: '#region-main .mform fieldset.default-visible',
        SECTIONS_DEFAULT_HIDDEN: '#region-main .mform fieldset.default-hidden',
        SECTIONS_DEFAULT_NOT_HIDDEN: '#region-main .mform fieldset:not(.default-hidden)',
        BUTTON_EXPAND: '.collapsible-actions',
        BUTTON_SHOW_MORE: '#region-main .mform .moreless-toggler:not(.moreless-less)',
        BUTTON_VIEWALL: '.quiz-view-all',
        BUTTON_VIEWALL_ANCOR: '#fgroup_id_buttonar',
        BUTTON_SUBMIT: '#fgroup_id_buttonar #id_submitbutton',
        BUTTON_SUBMIT2: '#fgroup_id_buttonar #id_submitbutton2',
        BUTTON_NEWSUBMIT: '#fgroup_id_buttonar #new_id_submitbutton',
        BUTTON_NEWSUBMIT2: '#fgroup_id_buttonar #new_id_submitbutton2',
        QUIZ_TYPE_SELECTOR: '.quiz-type-selector',
        QUIZ_TYPE_SELECTOR_N: '#quiz-type-selector-',
        QUIZ_TYPE_DESCRIPTION: '.selector-type-description',
        QUIZ_INFO: '.quizinfo',
        QUIZ_CMID: 'input[name="cmid"]',
        QUIZ_CMID2: '#local-quizpreset-cmid',
        QUIZ_CMID3: 'input[name="coursemodule"]',
        QUIZ_CUSTOM_CMID: '#id_cmidnumber',
        DATA_QUIZTYPE: 'quiztype',
        INVALID: '#region-main .mform .is-invalid',
        DATA_URL: 'url',
    };
    var Class = {
        QUIZ: 'path-mod-quiz',
        VISIBLE: '',
        HIDDEN: 'hidden',
        COLLAPSED: 'collapsed',
        DEFAULT_VISIBLE: 'default-visible',
        DEFAULT_HIDDEN: 'default-hidden',
        NEW_PREFIX: 'new_',
    };

    // Get cmid.
    if ($(Selector.QUIZ_CMID).length) {
        cmid = $(Selector.QUIZ_CMID).val();
    } else if ($(Selector.QUIZ_CMID2).length) {
        cmid = $(Selector.QUIZ_CMID2).val();
    }else if ($(Selector.QUIZ_CMID3).length) {
        cmid = $(Selector.QUIZ_CMID3).val();
    }

    var preconfigurePage = function(response) {
        // Hide Expand all.
        $(Selector.BUTTON_EXPAND).hide();
        Templates.render("local_quizpreset/view_all_button", response)
        .done(function (html) {
            $(Selector.BUTTON_VIEWALL_ANCOR).before(html);
        });

        // Hide save buttons and add event for custom save buttons.
        var newSubmit = $(Selector.BUTTON_SUBMIT).clone().insertAfter(Selector.BUTTON_SUBMIT);
        newSubmit.attr('id', Class.NEW_PREFIX + newSubmit.attr('id'));
        newSubmit.attr('name', Class.NEW_PREFIX + newSubmit.attr('name'));
        newSubmit.attr('type', 'button');
        $(Selector.BUTTON_SUBMIT).hide();

        var saveData = function(callback) {
            var defaulttype_for_save = $("#defaulttype_for_save").val();
            var viewall_for_save = $("#viewall_for_save").val();

            Ajax.call([{
                methodname: 'local_quizpreset_savedata',
                args: {
                    'cmid': Number(cmid),
                    'pagestate': pageState,
                    'type': Number(defaulttype_for_save),
                    'viewall': Number(viewall_for_save),
                },
                done: function() {
                    if(callback) {
                        callback();
                    }
                },
                fail: Notification.exception
            }]);
        };

        var submitQuiz1 = function() {
            $(Selector.BUTTON_SUBMIT).click();
        };

        var submitQuiz2 = function() {
            $(Selector.BUTTON_SUBMIT2).click();
        };

        var newSubmit2 = $(Selector.BUTTON_SUBMIT2).clone().insertAfter(Selector.BUTTON_SUBMIT2);
        newSubmit2.attr('id', Class.NEW_PREFIX + newSubmit2.attr('id'));
        newSubmit2.attr('name', Class.NEW_PREFIX + newSubmit2.attr('name'));
        newSubmit2.attr('type', 'button');
        $(Selector.BUTTON_SUBMIT2).hide();

        $(document).on('click', Selector.BUTTON_NEWSUBMIT, function(){
            saveData(submitQuiz1);
        });

        $(document).on('click', Selector.BUTTON_NEWSUBMIT2, function(){
            saveData(submitQuiz2);
        });

    };

    var expandedFieldset = function(response) {

        if(response.details.pagestate == 'view'){ return;}

        $.each(response.expanded, function(key, value ) {
            if(!value) {
                $('#' + key).hide();
            }
        });

        if(response.details.instancename == 'chemistry'){
            $('#id_teacherremarks').parents('.form-group').hide();
        }

        if(!response.details.viewdescription) {
            $('#id_introeditor').parents('.form-group').hide();
            $('#id_showdescription').parents('.form-group').hide();
        }
    };

    var fillByType = function(name, val) {
        var type = $("#id_"+name).prop('type');

        if(type == 'text' || type == 'select-one'){
            $("#id_"+name).val(val);
        }

        if(type == 'checkbox'){
            if(val == 1){
                $("#id_"+name).prop('checked', true);
            }else{
                $("#id_"+name).prop('checked', false);
            }
        }

        return;
    };

    var fillValues = function(response) {

        if(response.details.pagestate == 'view'){ return;}
        if(response.details.ifchangestate != 1 && response.details.pagestate != 'new'){ return;}

        $.each(response.values, function(key, value ) {

            if(key != 'area_checkboxes'){
                fillByType(key, value);
            }else{
                $.each(response.values.area_checkboxes, function(key2, value2 ) {
                    fillByType(key2, value2);
                });
            }
        });
    };

    var fillGlobal = function(response) {

        if(response.details.pagestate == 'view' || response.details.pagestate == 'update'){ return;}
        //if(response.details.ifchangestate != 1 && response.details.pagestate != 'new'){ return;}

        fillByType('name', response.global.name);

        // Fill texteares id_teacherremarks.
        $('#id_teacherremarkseditable').html(response.global.intro);
    };

    var addTypeSelector = function() {

        loadingIcon.show();

        // Get url params.
        var urlparams={};
        window.location.search
            .replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
                urlparams[key] = value;
                }
            );

        var url = new URL(window.location.href);
        var defaulttype = url.searchParams.get("defaulttype");
        var viewall = url.searchParams.get("viewall");

        // If not set viewall in url.
        if(viewall === null){
            viewall = 100;
        }

        Ajax.call([{
            methodname: 'local_quizpreset_get_pagedata',
            args: {
                'cmid': Number(cmid),
                'defaulttype': Number(defaulttype),
                'viewall': Number(viewall),
                'pagestate': pageState,
                'urlparams': JSON.stringify(urlparams),
            },
            done: function(res) {

                var response = JSON.parse(res);

                Templates.render("local_quizpreset/selector", response)
                .done(function (html) {

                    // Insert to page.
                    if ($(Selector.TYPE_SELECTOR_ANCOR1).length) {
                        $(Selector.TYPE_SELECTOR_ANCOR1).before(html);
                    } else if ($(Selector.TYPE_SELECTOR_ANCOR2).length) {
                        $(Selector.TYPE_SELECTOR_ANCOR2).before(html);
                    }

                    // Fill global.
                    fillGlobal(response);

                    // Fill values.
                    fillValues(response);

                    // Add button and chanche submit buttons.
                    preconfigurePage(response);

                    // Expanded fieldsets.
                    expandedFieldset(response);

                    // Run button userexposure.
                    Userexposure.init();

                    // Collapse fieldset.
                    if(response.details.viewall == 1) {
                        $('.collapsible-actions').show();
                    }

                    // Tooltip.
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip({
                            delay: { show: 700, hide: 500 }
                        });
                    });

                    loadingIcon.remove();
                });
            },
            fail: Notification.exception
        }]);

    };

    // Add Buttons Bar to view page.
    var addButtonsBar = function(callback) {
        Ajax.call([{
            methodname: 'local_quizpreset_get_buttonsbar',
            args: {
                'cmid': Number(cmid)
            },
            done: function(res) {
                var response = JSON.parse(res);
                Templates.render("local_quizpreset/buttons_bar", response)
                .done(function (html) {
                    $(Selector.QUIZ_INFO).before(html);
                    callback();
                });
            },
            fail: Notification.exception
        }]);
    };

    return {

        configureAddQuizPage: function() {
            pageState = "new";
            addTypeSelector();
        },

        configureUpdateQuizPage: function() {
            pageState = "update";
            addTypeSelector();
        },

        configureViewQuizPage: function() {
            pageState = "view";
            addButtonsBar(function(){
                addTypeSelector();
            });
        }
    };
});
