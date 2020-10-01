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
 * Admin settings class for the quiz browser security option.
 *
 * @package   mod_quiz
 * @copyright 2008 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

define('QUIZ_TYPE_1', 1);
define('QUIZ_TYPE_2', 2);
define('QUIZ_TYPE_3', 3);
define('QUIZ_TYPE_4', 4);
define('QUIZ_TYPE_5', 5);
define('QUIZ_TYPE_6', 6);


/**
 * Admin settings class for the quiz browser security option.
 *
 * Just so we can lazy-load the choices.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class custom_types {

    private $type;
    private $expanded;
    private $values;
    private $globalname;
    private $settypes;

    private $cmid;
    private $pagestate;
    private $viewall;
    private $ifchangestate;
    private $urlparams;

    public function __construct($cmid, $defaulttype, $viewall, $pagestate, $urlparams) {
        global $DB, $USER;

        $config = get_config('local_quizpreset');

        $this->cmid = $cmid;
        $this->pagestate = $pagestate;
        $this->urlparams = json_decode($urlparams, true);
        $this->settypes = range(1, $config->numberoftypes);

        // If POST
        if(empty($this->urlparams)){

            if($this->pagestate == 'update') {
                $sql = "
                    SELECT * 
                    FROM {local_quizpreset}
                    WHERE cmid = ? AND status = 0 
                    ORDER BY id DESC 
                    LIMIT 1
                ";
                $qp = $DB->get_record_sql($sql, array($cmid));
                $qp->viewall = 1;
            }

            if($this->pagestate == 'new') {
                $sql = "
                    SELECT * 
                    FROM {local_quizpreset}
                    WHERE userid = ? AND state = 'new' 
                    ORDER BY id DESC 
                    LIMIT 1
                ";
                $qp = $DB->get_record_sql($sql, array($USER->id));
                $qp->viewall = 1;
            }

        }else{
            $qp = $DB->get_record('local_quizpreset', array('cmid' => $cmid, 'status' => 1));
        }

        if (!empty($qp)) {
            $savedvaluetype = $qp->type;
            $savedvalueview = $qp->viewall;
        }else{
            $savedvaluetype = null;
            $savedvalueview = null;
        }

        // Set relevant type in class.
        if(in_array($defaulttype, $this->settypes)){
            $this->type = $defaulttype;
            $this->ifchangestate = ($savedvaluetype == $defaulttype) ? false : true;
        }else{
            $this->type = (!empty($savedvaluetype)) ? $savedvaluetype : $config->defaulttype;
            $this->ifchangestate = false;
        }

        // Set relevant viewall in class.
        if($viewall == 100){
            $this->viewall = (!empty($savedvalueview)) ?  $savedvalueview : 0;
        }else{
            $this->viewall = $viewall;
        }

        // Define values, expanded, global name.
        $this->setState();
    }

    public function setState(){
        $config = get_config('local_quizpreset');

        $variable = 'quizpreset_'.$this->type;
        $preset = json_decode($config->$variable);

        $this->expanded = (array)$preset->sections;
        $this->values = (array)$preset->fields;

        $variablename = 'quiztypename_'.$this->type;
        $this->globalname = array(
                'name' => $config->$variablename.' '.date('d-m-Y'),
                'intro' => '',
                'introformat' => 1,
                'introeditor' => array
                (
                        'text' => '',
                        'format' => 1,
                    //'itemid' => 482482910
                ),
        );

    }

    public function getDetails(){
        global $DB;

        $config = get_config('local_quizpreset');
        $variable = 'quizpreset_'.$this->type;
        $preset = json_decode($config->$variable);

        // Prepare url.
        $viewall = ($this->viewall == 1) ? 0 : 1;

        $param = $this->urlparams;
        switch ($this->pagestate) {
            case 'view':
                $param['viewall'] = $viewall;
                $obj = new \moodle_url('/mod/quiz/view.php', $param);
                $url = $obj->out(false);
                break;
            case 'update':
                $param['viewall'] = $viewall;
                $obj = new \moodle_url('/course/modedit.php', $param);
                $url = $obj->out(false);
                break;

            case 'new':
                $param['viewall'] = $viewall;
                $obj = new \moodle_url('/course/modedit.php', $param);
                $url = $obj->out(false);
                break;
        }

        // If POST
        if(empty($this->urlparams)){
            $url = false;
        }

        // Enable/Disable grades.
        $enablegardes = false;
        $userexposure = false;
        $viewdescription = false;

        if($this->pagestate == 'update' && $preset->functionality->exposure_grades) {

            $enablegardes = true;
            $cm = $DB->get_record('course_modules', array('id' => $this->cmid));

            if (!empty($cm)) {
                $quiz = $DB->get_record('quiz', array('id'=> $cm->instance));

                if($quiz->userexposure == 1){
                    $userexposure = true;
                }
            }
        }

        if($this->pagestate != 'view' && $preset->functionality->view_description) {
            $viewdescription = true;
        }

        // Button MORE/LESS must be just in setting mode.
        if($this->pagestate == 'view') {
            $cm = $DB->get_record('course_modules', array('id' => $this->cmid));
            if (!empty($cm)) {
                $quiz = $DB->get_record('quiz', array('id'=> $cm->instance));
                if($quiz->timelimit > 0){
                    $url = false;
                }
            }
        }

        return array(
                'cmid' => $this->cmid,
                'type' => $this->type,
                'pagestate' => $this->pagestate,
                'instancename' => '',
                'ifchangestate' => $this->ifchangestate,
                'viewall' => $this->viewall,
                'url_viewall' => $url,
                'enablegardes' => $enablegardes,
                'userexposure' => $userexposure,
                'viewdescription' => $viewdescription,
        );
    }

    public function getSelector($isstudent = 0){
        global $PAGE, $DB, $CFG;

        $items = array();
        $activedescribe = '';

        $config = get_config('local_quizpreset');

        foreach($this->settypes as $num){
            $tmp = array();
            $tmp['typeId'] = $num;

            // Prepare url.
            $param = $this->urlparams;
            switch ($this->pagestate) {
                case 'view':
                    $url = new \moodle_url('/course/modedit.php', array(
                            'update' => $this->cmid,
                            'return' => 1,
                            'defaulttype' => $num,
                            'viewall' => $this->viewall
                    ));
                    break;
                case 'update':
                    $param['update'] = $this->cmid;
                    $param['return'] = 1;
                    $param['defaulttype'] = $num;
                    $param['viewall'] = $this->viewall;
                    $url = new \moodle_url('/course/modedit.php', $param);
                    break;

                case 'new':
                    $param['defaulttype'] = $num;
                    $param['viewall'] = $this->viewall;
                    $url = new \moodle_url('/course/modedit.php', $param);
                    break;
            }

            $tmp['typeUrl'] = $url->out(false);

            // If POST return error.
            if(empty($this->urlparams)){
                $tmp['typeUrl'] = 'javascript:void(0)';
            }

            $variablename = 'quiztypename_'.$num;
            $tmp['typeName'] = $config->$variablename;

            $variabledescription = 'quiztypedescription_'.$num;
            $tmp['typeDescribe'] = $config->$variabledescription;

            // Prepare active tab.
            if($this->type == $num){
                $tmp['active'] = true;
                $activedescribe = $tmp['typeDescribe'];
            }else{
                $tmp['active'] = false;
            }

            $items[] = $tmp;
        }

        if($isstudent == 1){
            $items = array();
        }

        return array('items' => $items, 'activeDescribe' => $activedescribe);
    }

    public function getExpanded(){
        return ($this->viewall != 1) ? $this->expanded : array();
    }

    public function getValues(){
        return $this->values;
    }

    public function getGlobalName(){
        return $this->globalname;
    }

}
