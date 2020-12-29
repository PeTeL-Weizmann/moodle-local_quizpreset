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
 * Code to be executed after the plugin's database scheme has been installed is defined here.
 *
 * @package     local_quizpreset
 * @category    upgrade
 * @copyright   2017 nadavkav@gmail.com
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/local/quizpreset/classes/preset.php');

/**
 * Custom code to be run on installing the plugin.
 */
function xmldb_local_quizpreset_install() {

    global $DB;
    $dbman = $DB->get_manager();

    $table = new xmldb_table('local_quizpreset');

    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('state', XMLDB_TYPE_CHAR, '10', null, null, null, null);
    $table->add_field('type', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('viewall', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('status', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    $dbman->create_table($table);

    // Transfer data for petel.
    $table = new xmldb_table('quiz');
    $field = new xmldb_field('type');

    if ($dbman->field_exists($table, $field)) {
        $sql = "
            SELECT cm.id AS cmid, q.type
            FROM {quiz} AS q
            LEFT JOIN {modules} AS m ON (m.name = 'quiz')
            LEFT JOIN {course_modules} AS cm ON (cm.module = m.id AND cm.instance = q.id)
            WHERE q.type != 0
        ";

        $res = $DB->get_records_sql($sql);
        foreach ($res as $item){

            if(ceil(log10($item->type)) == 2){
                $type = $item->type / 10;
                $viewall = 1;
            }else{
                $type = $item->type;
                $viewall = 0;
            }

            $ins = new \StdClass();
            $ins->cmid = $item->cmid;
            $ins->userid = 0;
            $ins->state = 'update';
            $ins->type = $type;
            $ins->viewall = $viewall;
            $ins->status = 1;
            $ins->timemodified = time();

            $DB->insert_record('local_quizpreset', $ins);
        }
    }

    preset::fill_settings();

    return true;
}
