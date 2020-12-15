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
 * Local plugin quizpreset custom services - Upgrade plugin tasks
 *
 * @package    local_quizpreset
 * @copyright  2020 Nadav Kavalerchik <nadav.kavalerchik@weizmann.ac.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/local/quizpreset/classes/preset.php');

/**
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_quizpreset_upgrade($oldversion) {

    global $DB, $CFG;
    $dbman = $DB->get_manager();

    // Fetch documents from documents directory and put them into the new documents filearea.
    if ($oldversion < 2019072716) {

       if (!$dbman->table_exists('local_quizpreset')) {
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

                   if ($CFG->instancename == 'chemestry') {
                       switch ($type) {
                           case 1:
                               $type = 3;
                               break;
                           case 2:
                               $type = 5;
                               break;
                           case 3:
                               $type = 1;
                               break;
                           case 4:
                               $type = 2;
                               break;
                           case 6:
                               $type = 4;
                               break;
                           default:
                               $type = 99;
                       }
                   }

                   if ($CFG->instancename == 'chemistry') {
                       switch ($type) {
                           case 1:
                               $type = 3;
                               break;
                           case 2:
                               $type = 5;
                               break;
                           case 3:
                               $type = 1;
                               break;
                           case 4:
                               $type = 2;
                               break;
                           case 6:
                               $type = 4;
                               break;
                           default:
                               $type = 99;
                       }
                   }

                   if ($CFG->instancename == 'biology') {
                       switch ($type) {
                           case 1:
                               $type = 4;
                               break;
                           case 2:
                               $type = 1;
                               break;
                           case 3:
                               $type = 2;
                               break;
                           case 4:
                               $type = 3;
                               break;
                           default:
                               $type = 99;
                       }
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
       }

        upgrade_plugin_savepoint(true, 2019072716, 'local', 'quizpreset');
    }

    if ($oldversion < 2019072718) {

        preset::fill_settings();

        upgrade_plugin_savepoint(true, 2019072718, 'local', 'quizpreset');
    }

    return true;
}
