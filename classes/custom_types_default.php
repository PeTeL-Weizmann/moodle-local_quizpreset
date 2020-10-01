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

//define('QUIZ_TYPE_1', 1);
//define('QUIZ_TYPE_2', 2);
//define('QUIZ_TYPE_3', 3);
//define('QUIZ_TYPE_4', 4);
//define('QUIZ_TYPE_5', 5);
//define('QUIZ_TYPE_6', 6);


/**
 * Admin settings class for the quiz browser security option.
 *
 * Just so we can lazy-load the choices.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class custom_types_default {

    private $type;
    private $expanded;
    private $values;
    private $globalname;
    private $settypes;
    private $instancename;
    private $coursecat;

    private $cmid;
    private $pagestate;
    private $viewall;
    private $ifchangestate;
    private $urlparams;

    public function __construct($instancename) {
        $this->set_types($instancename);
    }

    public function get_types() {
        return $this->settypes;
    }

    public function set_types($instancename) {

        switch ($instancename) {
            case 'physics':
                $this->settypes = array(2,3,4,1);
                $this->instancename = $instancename;
                break;
            case 'chemistry':
                $this->settypes = array(3,4,1,6,2);
                $this->instancename = $instancename;
                break;
            case 'biology':
                $this->settypes = array(2,3,4,1);
                $this->instancename = $instancename;
                break;
            default:
                $this->settypes = array(2,3,4,1);
                $this->instancename = 'physics';
        }
    }

    public function predefine_setting($defaulttype) {
        global $CFG, $DB, $USER;

        $savedvaluetype = null;
        $savedvalueview = null;

        // Set relevant type in class.
        if (in_array($defaulttype, $this->settypes)){
            $this->type = $defaulttype;
            $this->ifchangestate = ($savedvaluetype == $defaulttype) ? false : true;
        } else {
            return false;
        }

        $this->setState();
        return true;
    }

    public function predefine($cmid, $defaulttype, $viewall, $pagestate, $urlparams) {
        global $CFG, $DB, $USER;

        $this->cmid = $cmid;
        $this->pagestate = $pagestate;
        $this->urlparams = json_decode($urlparams, true);

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
            $this->type = (!empty($savedvaluetype)) ? $savedvaluetype : $this->settypes[0];
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
        global $DB;

        switch ($this->type) {
            case QUIZ_TYPE_1:
                $this->expanded = $this->expandedType1();
                $this->values = $this->mergeWithDefault($this->valuesType1());
                $this->globalname = $this->globalNameType1();
                break;
            case QUIZ_TYPE_2:
                $this->expanded = $this->expandedType2();
                $this->values = $this->mergeWithDefault($this->valuesType2());
                $this->globalname = $this->globalNameType2();
                break;
            case QUIZ_TYPE_3:
                $this->expanded = $this->expandedType3();
                $this->values = $this->mergeWithDefault($this->valuesType3());
                $this->globalname = $this->globalNameType3();
                break;
            case QUIZ_TYPE_4:
                $this->expanded = $this->expandedType4();
                $this->values = $this->mergeWithDefault($this->valuesType4());
                $this->globalname = $this->globalNameType4();
                break;
            case QUIZ_TYPE_5:
                $this->expanded = array();
                $this->values = array();
                $this->globalname = array();
                break;
            case QUIZ_TYPE_6:
                $this->expanded = $this->expandedType2();
                $this->values = $this->mergeWithDefault($this->valuesType6());
                $this->globalname = $this->globalNameType6();
                break;
            default:
                $this->expanded = array();
                $this->values = array();
                $this->globalname = array();
        }
    }

    public function expandedType1(){
        return array(
                'id_general' => true,
                'id_timing' => true,
                'id_modstandardgrade' => false,
                'id_layouthdr' => false,
                'id_interactionhdr' => false,
                'id_reviewoptionshdr' => false,
                'id_display' => false,
                'id_security' => false,
                'id_overallfeedbackhdr' => false,
                'id_modstandardelshdr' => false,
                'id_availabilityconditionsheader' => true,
                'id_activitycompletionheader' => true,
                'id_tagshdr' => false,
                'id_competenciessection' => true,
                'id_seb'=> false,
        );
    }

    public function expandedType2(){
        return array(
                'id_general' => true,
                'id_timing' => true,
                'id_modstandardgrade' => false,
                'id_layouthdr' => false,
                'id_interactionhdr' => false,
                'id_reviewoptionshdr' => false,
                'id_display' => false,
                'id_security' => false,
                'id_overallfeedbackhdr' => false,
                'id_modstandardelshdr' => false,
                'id_availabilityconditionsheader' => true,
                'id_activitycompletionheader' => true,
                'id_tagshdr' => false,
                'id_competenciessection' => true,
                'id_seb'=> false,
        );

    }

    public function expandedType3(){
        return array(
                'id_general' => true,
                'id_timing' => true,
                'id_modstandardgrade' => false,
                'id_layouthdr' => false,
                'id_interactionhdr' => false,
                'id_reviewoptionshdr' => false,
                'id_display' => false,
                'id_security' => false,
                'id_overallfeedbackhdr' => false,
                'id_modstandardelshdr' => false,
                'id_availabilityconditionsheader' => true,
                'id_activitycompletionheader' => true,
                'id_tagshdr' => false,
                'id_competenciessection' => true,
                'id_seb'=> false,
        );
    }

    public function expandedType4(){
        return array(
                'id_general' => true,
                'id_timing' => true,
                'id_modstandardgrade' => false,
                'id_layouthdr' => false,
                'id_interactionhdr' => false,
                'id_reviewoptionshdr' => false,
                'id_display' => false,
                'id_security' => false,
                'id_overallfeedbackhdr' => false,
                'id_modstandardelshdr' => false,
                'id_availabilityconditionsheader' => true,
                'id_activitycompletionheader' => true,
                'id_tagshdr' => false,
                'id_competenciessection' => true,
                'id_seb'=> false,
        );
    }

    public function globalNameType1(){
        switch ($this->instancename) {
            case 'physics':
                $name = get_string('name_physics_1', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_physics_1', 'local_quizpreset');
                break;
            case 'chemistry':
                $name = get_string('name_chemistry_1', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_chemistry_1', 'local_quizpreset');
                break;
            case 'biology':
                $name = get_string('name_biology_1', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_biology_1', 'local_quizpreset');
                break;
        }

        return array(
            'name' => $name,
            'intro' => $introtext,
            'introformat' => 1,
            'introeditor' => array
            (
                'text' => $introtext,
                'format' => 1,
                //'itemid' => 482482910
            ),
        );
    }

    public function valuesType1(){
        $data =  array(
            //timing
            'timelimit' => 0,
            'overduehandling' => 'autosubmit',
            'graceperiod' => 0,

            //modstandardgrade
            'gradecat' => 0,
            'gradepass' => 55.00,
            'attempts' => 1,
            'grademethod' => 4,

            //layouthdr
            'questionsperpage' => 0,
            'repaginatenow' => 0,
            'navmethod' => 'free',

            //interactionhdr
            'shuffleanswers' => 1,
            'preferredbehaviour' => 'deferredfeedback',
            'canredoquestions' => 0,
            'attemptonlast' => 0,

            //reviewoptionshdr
            'area_checkboxes' => array(
                'attemptduring' => true,
                'correctnessduring' => false,
                'marksduring' => false,
                'specificfeedbackduring' => false,
                'generalfeedbackduring' => false,
                'rightanswerduring' => false,
                'overallfeedbackduring' => false,

                'attemptimmediately' => false,
                'correctnessimmediately' => false,
                'marksimmediately' => false,
                'specificfeedbackimmediately' => false,
                'generalfeedbackimmediately' => false,
                'rightanswerimmediately' => false,
                'overallfeedbackimmediately' => false,

                'attemptopen' => false,
                'correctnessopen' => false,
                'marksopen' => false,
                'specificfeedbackopen' => false,
                'generalfeedbackopen' => false,
                'rightansweropen' => false,
                'overallfeedbackopen' => false,

                'attemptclosed' => true,
                'correctnessclosed' => true,
                'marksclosed' => true,
                'specificfeedbackclosed' => true,
                'generalfeedbackclosed' => true,
                'rightanswerclosed' => true,
                'overallfeedbackclosed' => true,
            ),

            //display
            'showuserpicture' => 0,
            'decimalpoints' => 0,
            'questiondecimalpoints' => 1,
            'showblocks' => 1,
        );

        switch ($this->instancename) {
            case 'physics':
                break;
            case 'chemistry':
                $data = $this->chemistry_activity_with_score();
                $data['attempts'] = 1;
                $data['preferredbehaviour'] = 'adaptive';
                $data['preferredbehaviour'] = 'interactive';
                $data['canredoquestions'] = 0;

//                if($this->coursecat) {
//                    $data['gradecat'] = $this->coursecat;
//                }

                $data['area_checkboxes'] = array(
                        'attemptduring' => true,
                        'correctnessduring' => true,
                        'marksduring' => true,
                        'specificfeedbackduring' => true,
                        'generalfeedbackduring' => true,
                        'rightanswerduring' => true,
                        'overallfeedbackduring' => false,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => true,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => false,
                        'rightanswerimmediately' => false,
                        'overallfeedbackimmediately' => true,

                        'attemptopen' => false,
                        'correctnessopen' => false,
                        'marksopen' => true,
                        'specificfeedbackopen' => false,
                        'generalfeedbackopen' => false,
                        'rightansweropen' => false,
                        'overallfeedbackopen' => true,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => true,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                );
                break;
            case 'biology':
                break;
        }

        return $data;
    }

    public function globalNameType2(){
        switch ($this->instancename) {
            case 'physics':
                $name = get_string('name_physics_2', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_physics_2', 'local_quizpreset');
                break;
            case 'chemistry':
                $name = get_string('name_chemistry_2', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_chemistry_2', 'local_quizpreset');
                break;
            case 'biology':
                $name = get_string('name_biology_2', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_biology_2', 'local_quizpreset');
                break;
        }

        return array(
            'name' => $name,
            'intro' => $introtext,
            'introformat' => 1,
            'introeditor' => array
            (
                'text' => $introtext,
                'format' => 1,
                //'itemid' => 482482910
            ),
        );
    }

    public function valuesType2(){
        $data = array(
            //modstandardgrade
            'gradecat' => 0,
            'gradepass' => 0,
            'attempts' => 0,
            'grademethod' => 1,

            //interactionhdr
            'shuffleanswers' => 1,
            'preferredbehaviour' => 'interactive',
            'canredoquestions' => 1,
            'attemptonlast' => 1,

            //reviewoptionshdr
            'area_checkboxes' => array(
                'attemptduring' => true,
                'correctnessduring' => true,
                'marksduring' => false,
                'specificfeedbackduring' => true,
                'generalfeedbackduring' => true,
                'rightanswerduring' => true,
                'overallfeedbackduring' => false,

                'attemptimmediately' => true,
                'correctnessimmediately' => true,
                'marksimmediately' => false,
                'specificfeedbackimmediately' => true,
                'generalfeedbackimmediately' => true,
                'rightanswerimmediately' => true,
                'overallfeedbackimmediately' => true,

                'attemptopen' => true,
                'correctnessopen' => true,
                'marksopen' => false,
                'specificfeedbackopen' => true,
                'generalfeedbackopen' => true,
                'rightansweropen' => true,
                'overallfeedbackopen' => true,

                'attemptclosed' => true,
                'correctnessclosed' => true,
                'marksclosed' => false,
                'specificfeedbackclosed' => true,
                'generalfeedbackclosed' => true,
                'rightanswerclosed' => true,
                'overallfeedbackclosed' => true,
            ),
        );

        switch ($this->instancename) {
            case 'physics':
                break;
            case 'chemistry':
                $data = $this->chemistry_activity_with_score();

                $data['attempts'] = 1;
                $data['preferredbehaviour'] = 'deferredfeedback';
                $data['questionsperpage'] = 10;
                $data['shuffleanswers'] = 1;

//                if($this->coursecat) {
//                    $data['gradecat'] = $this->coursecat;
//                }

                $data['area_checkboxes'] = array(
                    //'attemptduring' => true,
                    //'correctnessduring' => true,
                    //'marksduring' => true,
                    //'specificfeedbackduring' => true,
                    //'generalfeedbackduring' => true,
                    //'rightanswerduring' => true,
                    //'overallfeedbackduring' => true,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => true,
                        'specificfeedbackimmediately' => false,
                        'generalfeedbackimmediately' => false,
                        'rightanswerimmediately' => false,
                        'overallfeedbackimmediately' => false,

                        'attemptopen' => false,
                        'correctnessopen' => false,
                        'marksopen' => true,
                        'specificfeedbackopen' => false,
                        'generalfeedbackopen' => false,
                        'rightansweropen' => false,
                        'overallfeedbackopen' => false,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => true,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                );
                break;
            case 'biology':
                break;
        }

        return $data;
    }

    public function globalNameType3(){
        switch ($this->instancename) {
            case 'physics':
                $name = get_string('name_physics_3', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_physics_3', 'local_quizpreset');
                break;
            case 'chemistry':
                $name = get_string('name_chemistry_3', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_chemistry_3', 'local_quizpreset');
                break;
            case 'biology':
                $name = get_string('name_biology_3', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_biology_3', 'local_quizpreset');
                break;
        }

        return array(
            'name' => $name,
            'intro' => $introtext,
            'introformat' => 1,
            'introeditor' => array
            (
                'text' => $introtext,
                'format' => 1,
                //'itemid' => 482482910
            ),
        );
    }

    public function valuesType3(){
        $data = array(
            //timing
            'timelimit' => 0,
            'overduehandling' => 'autosubmit',
            'graceperiod' => 0,

            //modstandardgrade
            'gradecat' => 0,
            'gradepass' => 55.00,
            'attempts' => 0,
            'grademethod' => 1,

            //interactionhdr
            'shuffleanswers' => 1,
            'preferredbehaviour' => 'adaptivenopenalty',
            'canredoquestions' => 1,
            'attemptonlast' => 0,

            //reviewoptionshdr
            'area_checkboxes' => array(
                'attemptduring' => true,
                'correctnessduring' => true,
                'marksduring' => false,
                'specificfeedbackduring' => true,
                'generalfeedbackduring' => false,
                'rightanswerduring' => false,
                'overallfeedbackduring' => false,

                'attemptimmediately' => true,
                'correctnessimmediately' => true,
                'marksimmediately' => false,
                'specificfeedbackimmediately' => true,
                'generalfeedbackimmediately' => true,
                'rightanswerimmediately' => true,
                'overallfeedbackimmediately' => true,

                'attemptopen' => true,
                'correctnessopen' => true,
                'marksopen' => false,
                'specificfeedbackopen' => true,
                'generalfeedbackopen' => true,
                'rightansweropen' => true,
                'overallfeedbackopen' => true,

                'attemptclosed' => true,
                'correctnessclosed' => true,
                'marksclosed' => false,
                'specificfeedbackclosed' => true,
                'generalfeedbackclosed' => true,
                'rightanswerclosed' => true,
                'overallfeedbackclosed' => true,
            ),
        );

        switch ($this->instancename) {
            case 'physics':
                break;
            case 'chemistry':
                $data = $this->chemistry_activity_without_score();

                $data['attempts'] = 0;

//                if($this->coursecat) {
//                    $data['gradecat'] = $this->coursecat;
//                }

                $data['area_checkboxes'] = array(
                    'attemptduring' => true,
                    'correctnessduring' => true,
                    'marksduring' => true,
                    'specificfeedbackduring' => true,
                    'generalfeedbackduring' => true,
                    'rightanswerduring' => true,
                    'overallfeedbackduring' => true,

                    'attemptimmediately' => true,
                    'correctnessimmediately' => true,
                    'marksimmediately' => true,
                    'specificfeedbackimmediately' => true,
                    'generalfeedbackimmediately' => true,
                    'rightanswerimmediately' => true,
                    'overallfeedbackimmediately' => true,

                    'attemptopen' => true,
                    'correctnessopen' => true,
                    'marksopen' => true,
                    'specificfeedbackopen' => true,
                    'generalfeedbackopen' => true,
                    'rightansweropen' => true,
                    'overallfeedbackopen' => true,

                    'attemptclosed' => true,
                    'correctnessclosed' => true,
                    'marksclosed' => true,
                    'specificfeedbackclosed' => true,
                    'generalfeedbackclosed' => true,
                    'rightanswerclosed' => true,
                    'overallfeedbackclosed' => true,
                );
                $data['preferredbehaviour'] = 'deferredfeedback';
                $data['questionsperpage'] = 10;
                $data['shuffleanswers'] = 1;
                $data['attemptonlast'] = 1;

                break;
            case 'biology':
                break;
        }

        return $data;
    }

    public function globalNameType4(){
        switch ($this->instancename) {
            case 'physics':
                $name = get_string('name_physics_4', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_physics_4', 'local_quizpreset');
                break;
            case 'chemistry':
                $name = get_string('name_chemistry_4', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_chemistry_4', 'local_quizpreset');
                break;
            case 'biology':
                $name = get_string('name_biology_4', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_biology_4', 'local_quizpreset');
                break;
        }

        return array(
            'name' => $name,
            'intro' => $introtext,
            'introformat' => 1,
            'introeditor' => array
            (
                'text' => $introtext,
                'format' => 1,
                //'itemid' => 482482910
            ),
        );
    }

    public function valuesType4(){
        $data = array(
            //timing
            'timelimit' => 0,
            'overduehandling' => 'autosubmit',
            'graceperiod' => 0,

            //modstandardgrade
            'gradecat' => 0,
            'gradepass' => 55.00,
            'attempts' => 0,
            'grademethod' => 3,

            //interactionhdr
            'shuffleanswers' => 1,
            'preferredbehaviour' => 'adaptive',
            'canredoquestions' => 1,
            'attemptonlast' => 1,

            //reviewoptionshdr
            'area_checkboxes' => array(
                'attemptduring' => true,
                'correctnessduring' => true,
                'marksduring' => false,
                'specificfeedbackduring' => true,
                'generalfeedbackduring' => false,
                'rightanswerduring' => false,
                'overallfeedbackduring' => false,

                'attemptimmediately' => true,
                'correctnessimmediately' => true,
                'marksimmediately' => true,
                'specificfeedbackimmediately' => true,
                'generalfeedbackimmediately' => true,
                'rightanswerimmediately' => true,
                'overallfeedbackimmediately' => true,

                'attemptopen' => true,
                'correctnessopen' => true,
                'marksopen' => true,
                'specificfeedbackopen' => true,
                'generalfeedbackopen' => true,
                'rightansweropen' => true,
                'overallfeedbackopen' => true,

                'attemptclosed' => true,
                'correctnessclosed' => true,
                'marksclosed' => true,
                'specificfeedbackclosed' => true,
                'generalfeedbackclosed' => true,
                'rightanswerclosed' => true,
                'overallfeedbackclosed' => true,
            ),
        );

        switch ($this->instancename) {
            case 'physics':
                break;
            case 'chemistry':
                $data = $this->chemistry_activity_with_score();

                $data['attempts'] = 3;
                $data['grademethod'] = 1;
                $data['preferredbehaviour'] = 'deferredfeedback';
                $data['questionsperpage'] = 10;
                $data['shuffleanswers'] = 1;

//                if($this->coursecat) {
//                    $data['gradecat'] = $this->coursecat;
//                }

                $data['area_checkboxes'] = array(
                        //'attemptduring' => true,
                        //'correctnessduring' => true,
                        //'marksduring' => true,
                        //'specificfeedbackduring' => true,
                        //'generalfeedbackduring' => true,
                        //'rightanswerduring' => true,
                        //'overallfeedbackduring' => true,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => true,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => false,
                        'rightanswerimmediately' => false,
                        'overallfeedbackimmediately' => false,

                        'attemptopen' => false,
                        'correctnessopen' => false,
                        'marksopen' => true,
                        'specificfeedbackopen' => false,
                        'generalfeedbackopen' => false,
                        'rightansweropen' => false,
                        'overallfeedbackopen' => false,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => true,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                );

                break;
            case 'biology':
                break;
        }

        return $data;
    }

    public function globalNameType6(){
        switch ($this->instancename) {
            case 'physics':
                $name = get_string('name_physics_1', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_physics_1', 'local_quizpreset');
                break;
            case 'chemistry':
                $name = get_string('name_chemistry_6', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_chemistry_6', 'local_quizpreset');
                break;
            case 'biology':
                $name = get_string('name_biology_1', 'local_quizpreset').' '.date('d-m-Y');
                $introtext = get_string('intro_biology_1', 'local_quizpreset');
                break;
        }

        return array(
                'name' => $name,
                'intro' => $introtext,
                'introformat' => 1,
                'introeditor' => array
                (
                        'text' => $introtext,
                        'format' => 1,
                    //'itemid' => 482482910
                ),
        );
    }

    public function valuesType6(){
        $data = array(
            //modstandardgrade
                'gradecat' => 17,
                'gradepass' =>0,
                'attempts' => 0,
                'grademethod' => 1,

            //interactionhdr
                'shuffleanswers' => 1,
                'preferredbehaviour' => 'interactive',
                'canredoquestions' => 1,
                'attemptonlast' => 1,

            //reviewoptionshdr
                'area_checkboxes' => array(
                        'attemptduring' => true,
                        'correctnessduring' => true,
                        'marksduring' => false,
                        'specificfeedbackduring' => true,
                        'generalfeedbackduring' => true,
                        'rightanswerduring' => true,
                        'overallfeedbackduring' => false,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => false,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => true,
                        'rightanswerimmediately' => true,
                        'overallfeedbackimmediately' => true,

                        'attemptopen' => true,
                        'correctnessopen' => true,
                        'marksopen' => false,
                        'specificfeedbackopen' => true,
                        'generalfeedbackopen' => true,
                        'rightansweropen' => true,
                        'overallfeedbackopen' => true,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => false,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                ),
        );

        switch ($this->instancename) {
            case 'physics':
                break;
            case 'chemistry':
                $data = $this->chemistry_activity_with_score();

                $data['attempts'] = 1;
                $data['preferredbehaviour'] = 'deferredfeedback';
                $data['questionsperpage'] = 10;
                $data['shuffleanswers'] = 1;

//                if($this->coursecat) {
//                    $data['gradecat'] = $this->coursecat;
//                }

                $data['area_checkboxes'] = array(
                        'attemptduring' => true,
                        'correctnessduring' => true,
                        'marksduring' => false,
                        'specificfeedbackduring' => true,
                        'generalfeedbackduring' => true,
                        'rightanswerduring' => true,
                        'overallfeedbackduring' => false,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => true,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => false,
                        'rightanswerimmediately' => false,
                        'overallfeedbackimmediately' => false,

                        'attemptopen' => false,
                        'correctnessopen' => false,
                        'marksopen' => true,
                        'specificfeedbackopen' => false,
                        'generalfeedbackopen' => true,
                        'rightansweropen' => true,
                        'overallfeedbackopen' => false,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => true,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                );
                break;
            case 'biology':
                break;
        }

        return $data;
    }

    public function chemistry_activity_with_score(){
        $data = array(
            //timing
                'timelimit' => 0,
                'overduehandling' => 'autosubmit',
                'graceperiod' => 0,

            //modstandardgrade
                'gradecat' => 0,
                'gradepass' => 55.00,
                'attempts' => 0,
                'grademethod' => 1,

            //interactionhdr
                'shuffleanswers' => 1,
                'preferredbehaviour' => 'adaptive',
                'canredoquestions' => 1,
                'attemptonlast' => 1,

            //reviewoptionshdr
                'area_checkboxes' => array(
                        'attemptduring' => true,
                        'correctnessduring' => true,
                        'marksduring' => false,
                        'specificfeedbackduring' => true,
                        'generalfeedbackduring' => false,
                        'rightanswerduring' => false,
                        'overallfeedbackduring' => false,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => true,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => true,
                        'rightanswerimmediately' => true,
                        'overallfeedbackimmediately' => true,

                        'attemptopen' => true,
                        'correctnessopen' => true,
                        'marksopen' => true,
                        'specificfeedbackopen' => true,
                        'generalfeedbackopen' => true,
                        'rightansweropen' => true,
                        'overallfeedbackopen' => true,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => true,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                ),
        );

        return $data;
    }

    public function chemistry_activity_without_score(){
        global $DB, $COURSE;

        // TODO: convert hardcoded 'fullname = 'פעילויות ללא ציון' to admin settings or get_string. (nadavkav)
        // we have only one "activity with no grades" category in the course. PTL-1560
        $nogradescat = $DB->get_record('grade_categories', ['courseid' => $COURSE->id, 'fullname' => get_string('activitieswithoutgrade', 'local_petel')]);

        $data = array(
            //timing
                'timelimit' => 0,
                'overduehandling' => 'autosubmit',
                'graceperiod' => 0,

            //modstandardgrade
                'gradecat' => isset($nogradescat->id) ? $nogradescat->id : 0,
                'gradepass' => 55.00,
                'attempts' => 0,
                'grademethod' => 1,

            //interactionhdr
                'shuffleanswers' => 1,
                'preferredbehaviour' => 'adaptivenopenalty',
                'canredoquestions' => 1,
                'attemptonlast' => 0,

            //reviewoptionshdr
                'area_checkboxes' => array(
                        'attemptduring' => true,
                        'correctnessduring' => true,
                        'marksduring' => false,
                        'specificfeedbackduring' => true,
                        'generalfeedbackduring' => false,
                        'rightanswerduring' => false,
                        'overallfeedbackduring' => false,

                        'attemptimmediately' => true,
                        'correctnessimmediately' => true,
                        'marksimmediately' => false,
                        'specificfeedbackimmediately' => true,
                        'generalfeedbackimmediately' => true,
                        'rightanswerimmediately' => true,
                        'overallfeedbackimmediately' => true,

                        'attemptopen' => true,
                        'correctnessopen' => true,
                        'marksopen' => false,
                        'specificfeedbackopen' => true,
                        'generalfeedbackopen' => true,
                        'rightansweropen' => true,
                        'overallfeedbackopen' => true,

                        'attemptclosed' => true,
                        'correctnessclosed' => true,
                        'marksclosed' => false,
                        'specificfeedbackclosed' => true,
                        'generalfeedbackclosed' => true,
                        'rightanswerclosed' => true,
                        'overallfeedbackclosed' => true,
                ),
        );

        return $data;
    }

    public function defaultValues()
    {
        return array(
            //'name' => 'default',
            //'introeditor' => array
            //(
            //    'text' => 'default',
            //    'format' => 1,
            //    'itemid' => 664245605,
            //),

            //'showdescription' => 0,
            'timeopen' => 0,
           // 'timeclose' => 0,
            'timelimit' => 0,
            'overduehandling' => 'autosubmit',
            'graceperiod' => 0,
            'gradecat' => 17,
            'gradepass' => 0,
            //'grade' => 10,
            'attempts' => 0,
            'grademethod' => 1,
            'questionsperpage' => 0,
            'navmethod' => 'free',
            'shuffleanswers' => 1,
            'preferredbehaviour' => 'deferredfeedback',
            'canredoquestions' => 0,
            'attemptonlast' => 0,

//            'attemptduring' => 1,
//            'correctnessduring' => 1,
//            'marksduring' => 1,
//            'specificfeedbackduring' => 1,
//            'generalfeedbackduring' => 1,
//            'rightanswerduring' => 1,
//            'attemptimmediately' => 1,
//            'correctnessimmediately' => 1,
//            'marksimmediately' => 1,
//            'specificfeedbackimmediately' => 1,
//            'generalfeedbackimmediately' => 1,
//            'rightanswerimmediately' => 1,
//            'overallfeedbackimmediately' => 1,
//            'attemptopen' => 1,
//            'correctnessopen' => 1,
//            'marksopen' => 1,
//            'specificfeedbackopen' => 1,
//            'generalfeedbackopen' => 1,
//            'rightansweropen' => 1,
//            'overallfeedbackopen' => 1,
//            'attemptclosed' => 1,
//            'correctnessclosed' => 1,
//            'marksclosed' => 1,
//            'specificfeedbackclosed' => 1,
//            'generalfeedbackclosed' => 1,
//            'rightanswerclosed' => 1,
//            'overallfeedbackclosed' => 1,

            'showuserpicture' => 0,
            'decimalpoints' => 2,
            'questiondecimalpoints' => -1,
            'showblocks' => 0,
            'quizpassword' => '',
            'subnet' => '',
            'delay1' => 0,
            'delay2' => 0,
            'browsersecurity' => '-',
            'allowofflineattempts' => 0,
            'boundary_repeats' => 0,
//            'feedbacktext' => array
//            (
//                '0' => array
//                (
//                    'text' => '',
//                    'format' => 1,
//                    'itemid' => 435760498,
//                )
//            ),

            //'visible' => 1,
            'visibleoncoursepage' => 1,
            'cmidnumber' => '',
            'groupmode' => 0,
            'groupingid' => 0,
            'availabilityconditionsjson' => '',
//            'completionunlocked' => 0,
//            'completion' => 1,
//            'completionview' => 1,
            'completionusegrade' => '',
            'completionexpected' => 0,
//            'tags' => array
//            (
//            ),
//            'course' => 19,
//            'coursemodule' => 1596,
//            'section' => 1,
//            'module' => 16,
//            'modulename' => 'quiz',
//            'instance' => 567,
//            'add' => '',
//            'update' => 1596,
//            'return' => 1,
//            'sr' => 0,
//            'submitbutton2' => 'שמירת שינויים וחזרה לקורס',
        );
    }

    public function mergeWithDefault($datatype){
        $default = $this->defaultValues();

        foreach($datatype as $name=>$item){
            $default[$name] = $item;
        }

        return $default;
    }

    public function getDetails(){
        global $DB;

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
        if($this->pagestate == 'update' && $this->type == QUIZ_TYPE_1) {

            $enablegardes = true;
            $cm = $DB->get_record('course_modules', array('id' => $this->cmid));

            if (!empty($cm)) {
                $quiz = $DB->get_record('quiz', array('id'=> $cm->instance));

                if($quiz->userexposure == 1){
                    $userexposure = true;
                }
            }
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
                'instancename' => $this->instancename,
                'ifchangestate' => $this->ifchangestate,
                'viewall' => $this->viewall,
                'url_viewall' => $url,
                'enablegardes' => $enablegardes,
                'userexposure' => $userexposure,
        );
    }

    public function getSelector($isstudent = 0){
        global $PAGE, $DB, $CFG;

        $items = array();
        $activedescribe = '';

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

            switch ($this->instancename) {
                case 'physics':
                    $tmp['typeDescribe'] = get_string('describe_physics_'.$num, 'local_quizpreset');
                    $tmp['typeName'] = get_string('name_physics_'.$num, 'local_quizpreset');
                    break;
                case 'chemistry':
                    $tmp['typeDescribe'] = get_string('describe_chemistry_'.$num, 'local_quizpreset');
                    $tmp['typeName'] = get_string('name_chemistry_'.$num, 'local_quizpreset');
                    break;
                case 'biology':
                    $tmp['typeDescribe'] = get_string('describe_biology_'.$num, 'local_quizpreset');
                    $tmp['typeName'] = get_string('name_biology_'.$num, 'local_quizpreset');
                    break;
            }

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
