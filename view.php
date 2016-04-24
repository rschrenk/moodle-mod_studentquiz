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
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_studentquiz
 * @copyright  2016 HSR (http://www.hsr.ch)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__) . '/viewlib.php');

$cmid = optional_param('id', 0, PARAM_INT);
if(!$cmid){
    $cmid = required_param('cmid', PARAM_INT);
}

$view = new studentquiz_view($cmid);
require_login($view->get_course(), true, $view->get_coursemodule());

if (data_submitted()) {
    if(optional_param('startquiz', null, PARAM_BOOL)){
        $view->start_quiz((array) data_submitted());
        if($view->has_questiond_ids()){
            redirect($view->get_attempturl());
        }
    }
    if(optional_param('startfilteredquiz', null, PARAM_RAW)){
        $ids = required_param('filtered_question_ids', PARAM_RAW);
        $view->start_filtered_quiz($ids);
        if($view->has_questiond_ids()){
            redirect($view->get_attempturl());
        }
    }
}
if(optional_param('retryquiz', null, PARAM_BOOL)) {
    $sessionId = required_param('sessionid' , PARAM_INT);
    $view->retry_quiz($sessionId);

    if($view->has_questiond_ids()){
        redirect($view->get_attempturl());
    }
}


$output = $PAGE->get_renderer('mod_studentquiz');

$view->create_questionbank();
$PAGE->set_url($view->get_pageurl());
// TODO log this page view.
$PAGE->set_title($view->get_title());
$PAGE->set_heading($COURSE->fullname);

echo $OUTPUT->header();

$output->display_questionbank($view);

echo $OUTPUT->footer();
