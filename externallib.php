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
 * External functions backported.
 *
 * @package    local_quizpreset
 * @copyright  devlion.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . "/externallib.php");
require_once(__DIR__ . '/classes/preset.php');

class local_quizpreset_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_buttonsbar_parameters() {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'Module ID'),
            )
        );
    }
    /**
     * @return string result of submittion
     */
    public static function get_buttonsbar($cmid) {
        $params = self::validate_parameters(self::get_buttonsbar_parameters(),
            array(
                'cmid' => (int)$cmid,
            )
        );
        return preset::get_buttonsbar($params['cmid']);
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_buttonsbar_returns() {
        return new external_value(PARAM_RAW, 'Get quiz button topbar');
    }


    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_pagedata_parameters() {
        return new external_function_parameters(
                array(
                        'cmid' => new external_value(PARAM_INT, 'Module ID'),
                        'defaulttype' => new external_value(PARAM_INT, 'Default Type'),
                        'viewall' => new external_value(PARAM_INT, 'View All'),
                        'pagestate' => new external_value(PARAM_RAW, 'Page State'),
                        'urlparams' => new external_value(PARAM_RAW, 'Url Params'),
                )
        );
    }
    /**
     * @return string result of submittion
     */
    public static function get_pagedata($cmid, $defaulttype, $viewall, $pagestate, $urlparams) {
        $params = self::validate_parameters(self::get_pagedata_parameters(),
                array(
                        'cmid' => (int)$cmid,
                        'defaulttype' => (int)$defaulttype,
                        'viewall' => (int)$viewall,
                        'pagestate' => $pagestate,
                        'urlparams' => $urlparams,
                )
        );
        return preset::get_pagedata($params['cmid'], $params['defaulttype'], $params['viewall'], $params['pagestate'], $params['urlparams']);
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_pagedata_returns() {
        return new external_value(PARAM_RAW, 'Get page data');
    }

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function savedata_parameters() {
        return new external_function_parameters(
                array(
                        'cmid' => new external_value(PARAM_INT, 'Module ID'),
                        'pagestate' => new external_value(PARAM_RAW, 'Page State'),
                        'type' => new external_value(PARAM_INT, 'Type'),
                        'viewall' => new external_value(PARAM_INT, 'View All'),
                )
        );
    }
    /**
     * @return string result of submittion
     */
    public static function savedata($cmid, $pagestate, $type, $viewall) {
        $params = self::validate_parameters(self::savedata_parameters(),
                array(
                        'cmid' => (int)$cmid,
                        'pagestate' => $pagestate,
                        'type' => (int)$type,
                        'viewall' => (int)$viewall,
                )
        );
        return preset::savedata($params['cmid'], $params['pagestate'], $params['type'], $params['viewall']);
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function savedata_returns() {
        return new external_value(PARAM_RAW, 'Get saved result');
    }

}