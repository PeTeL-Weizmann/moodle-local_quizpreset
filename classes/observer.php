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
 * Event observers.
 *
 * @package    local_quizpreset
 * @copyright  2016 onwards - Davidson institute (Weizmann institute)
 * @author     Nadav Kavalerchik <nadav.kavalerchik@weizmann.ac.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_quizpreset;

defined('MOODLE_INTERNAL') || die();


class observer {

    /**
     * @param \core\event\course_module_updated $event
     * @return bool
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \file_reference_exception
     * @throws \stored_file_creation_exception
     */
    public static function course_module_updated(\core\event\course_module_updated $event): bool {
        global $DB;

        $sql = '
            SELECT * 
            FROM {local_quizpreset}
            WHERE cmid = ?
            ORDER BY id DESC 
            LIMIT 1
        ';
        $qp = $DB->get_record_sql($sql, array($event->contextinstanceid));

        if(!empty($qp)){
            unset($qp->id);
            $qp->userid = $event->userid;
            $qp->status = 1;
            $qp->timemodified = time();

            $DB->delete_records('local_quizpreset', array('cmid' => $event->contextinstanceid));

            $DB->insert_record('local_quizpreset', $qp);
        }

        return true;
    }

    /**
     * @param \core\event\course_module_updated $event
     * @return bool
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \file_reference_exception
     * @throws \stored_file_creation_exception
     */
    public static function course_module_created(\core\event\course_module_created $event): bool {
        global $DB;

        //echo '<pre>';print_r($event->contextinstanceid);exit;

        $sql = "
            SELECT * 
            FROM {local_quizpreset}
            WHERE userid = ? AND state = 'new' 
            ORDER BY id DESC 
            LIMIT 1
        ";
        $qp = $DB->get_record_sql($sql, array($event->userid));

        if(!empty($qp)){
            unset($qp->id);
            $qp->cmid = $event->contextinstanceid;
            $qp->userid = $event->userid;
            $qp->state = 'update';
            $qp->status = 1;
            $qp->timemodified = time();

            $DB->delete_records('local_quizpreset', array('userid' => $event->userid, 'state' => 'new'));

            $DB->insert_record('local_quizpreset', $qp);
        }

        return true;
    }

    /**
     * @param \core\event\course_module_updated $event
     * @return bool
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \file_reference_exception
     * @throws \stored_file_creation_exception
     */
    public static function course_module_deleted(\core\event\course_module_deleted $event): bool {
        global $DB;

        $DB->delete_records('local_quizpreset', array('cmid' => $event->contextinstanceid));

        return true;
    }
}