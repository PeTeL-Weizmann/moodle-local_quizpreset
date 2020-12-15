<?php

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
 * Plugin administration pages are defined here.
 *
 * @package     local_quizpreset
 * @category    admin
 * @copyright   2019 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage('quizpresetsettings', get_string('pluginname', 'local_quizpreset'),
                    $CFG->wwwroot . '/admin/settings.php?section=local_quizpreset', 'mod/quiz:manage'));

    $settings = new admin_settingpage('local_quizpreset', get_string('pluginname', 'local_quizpreset'));
    $ADMIN->add('localplugins', $settings);

    $numtypes = get_config('local_quizpreset', 'numberoftypes');

    $name = 'local_quizpreset/defaulttype';
    $title = get_string('defaulttype', 'local_quizpreset');
    $description = get_string('defaulttypedesc', 'local_quizpreset');
    $default = 1;
    $choices = array_combine(range(1, $numtypes), array_map(function($n) { return new lang_string('quiztype_title', 'local_quizpreset', $n); }, range(1, $numtypes)));
    $settings->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $name = 'local_quizpreset/enableadjustments';
    $title = get_string('enableadjustments', 'local_quizpreset');
    $description = get_string('enableadjustmentsdesc', 'local_quizpreset');
    $default = 1;
    $choices = array(0 => get_string('no'), 1 => get_string('yes'));
    $settings->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    $name = 'local_quizpreset/numberoftypes';
    $title = get_string('numberoftypes', 'local_quizpreset');
    $description = get_string('numberoftypesdesc', 'local_quizpreset');
    $default = 4;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
    );
    $settings->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    for ($i = 1; $i <= $numtypes; $i++) {

        $name = 'local_quizpreset/quiztypetitle_' . $i;
        $title = get_string('quiztype_title', 'local_quizpreset', $i);
        $description = get_string('quiztype_titledescr', 'local_quizpreset', $i);
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        $name = 'local_quizpreset/quiztypename_' . $i;
        $title = new lang_string('quiztype_name', 'local_quizpreset', $i);
        $description = '';
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $settings->add($setting);

        $name = 'local_quizpreset/quiztypedescription_' . $i;
        $title = new lang_string('quiztype_description', 'local_quizpreset', $i);
        $description = '';
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_TEXT);
        $settings->add($setting);

        $name = 'local_quizpreset/quizpreset_' . $i;
        $title = new lang_string('quiztype_preset', 'local_quizpreset', $i);
        $description = '';
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
        $settings->add($setting);
    }

    // Remove unnecessary types.
    $quizpreset = get_config('local_quizpreset');
    if (isset($quizpreset->numberoftypes) and $quizpreset->numberoftypes < 6) {
        $quiztypes = array();
        for ($i = $quizpreset->numberoftypes + 1; $i <= 6; $i++) {
            set_config('quiztypename_' . $i, '', 'local_quizpreset');
            set_config('quiztypedescription_' . $i, '', 'local_quizpreset');
            set_config('quizpreset_' . $i, '', 'local_quizpreset');
        }
    }

}
