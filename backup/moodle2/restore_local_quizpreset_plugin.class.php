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
 * Grid Information
 *
 * @package    local_quizpreset
 * @version    1.0
 * @copyright  2020 info@devlion.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 */
defined('MOODLE_INTERNAL') || die();
/**
 * Restore plugin class that provides the necessary information
 * needed to restore enemyquestions
 */
class restore_local_quizpreset_plugin extends restore_local_plugin {
    /**
     * Returns the paths to be handled by the plugin at course level.
     */
    protected function define_module_plugin_structure() {
        $paths = array();
        $elename = 'plugin_local_quizpreset_module'; // This defines the postfix of 'process_*' below.
        $elepath = $this->get_pathfor('/');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths.
    }

    public function process_plugin_local_quizpreset_module($data) {
        global $DB, $CFG;
        $data = (object)$data;

        if ($hasoldinstance = $DB->get_record('course_modules', array('id' => $data->cmid))) {
            $oldcontext = \context_module::instance($data->cmid); // save old context
            $data->cmid = $this->task->get_moduleid();            // add new cm (instance) id to local_quizpreset DB

            $DB->insert_record('local_quizpreset', $data);
        }


    }
}

