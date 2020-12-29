<?php

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
 * Web service external functions and service definitions.
 *
 * @package    local_quizpreset
 * @copyright  2020 Devlion.co
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = array(
    'local_quizpreset_get_buttonsbar' => array(
            'classname'   => 'local_quizpreset_external',
            'methodname'  => 'get_buttonsbar',
            'classpath'   => 'local/quizpreset/externallib.php',
            'description' => 'Get quiz top buttons bar',
            'type'          => 'read',
            'ajax'          => true,
            'capabilities'  => '',
            'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),

        'local_quizpreset_get_pagedata' => array(
                'classname'   => 'local_quizpreset_external',
                'methodname'  => 'get_pagedata',
                'classpath'   => 'local/quizpreset/externallib.php',
                'description' => 'Get quiz page data',
                'type'          => 'read',
                'ajax'          => true,
                'capabilities'  => '',
                'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
        ),

        'local_quizpreset_savedata' => array(
                'classname'   => 'local_quizpreset_external',
                'methodname'  => 'savedata',
                'classpath'   => 'local/quizpreset/externallib.php',
                'description' => 'Save data',
                'type'          => 'read',
                'ajax'          => true,
                'capabilities'  => '',
                'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
        ),
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
    'Quizpreset services' => array(
            'functions' => array (
                'local_quizpreset_get_buttonsbar',
                'local_quizpreset_get_pagedata',
                'local_quizpreset_savedata',
            ),
            'enabled'=>1,
            'shortname'=>'quizpreset'
    )
);
