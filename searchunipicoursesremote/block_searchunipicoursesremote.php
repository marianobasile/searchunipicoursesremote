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
 * Admin Bookmarks Block page.
 *
 * @package    blocks
 * @subpackage searchunipicoursesremote
 * @copyright  University Of Pisa
 * @author     Mariano Basile
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

/**
 * The Search Courses Autocomplete block class
 */


class block_searchunipicoursesremote extends block_base {
    
    public function init() {
        $this->title = get_string('plugin_name', 'block_searchunipicoursesremote');
    }

    public function get_content() {

        global $CFG;
        $this->content = new stdClass();

        $my_block = "";
        $params = array();
        $module = array(
            'name' => 'ricerca_corsi_unipi_in_tutto_ateneo',
            'fullpath' => '/blocks/searchunipicoursesremote/js/module.js'
        );
        $my_block .= $this->page->requires->js_init_call('M.search_courses_remote.init', array(
            $params
        ), false, $module);

        $my_block .= "<div id=\"ricerca_corsi_unipi\">";
        $my_block .= "<form name = \"searchCourse\" id = \"searchCourse\" action = \"/blocks/searchunipicoursesremote/result.php\" target = \"myNewWindow\" method = \"POST\" onsubmit = \"window.open('','myNewWindow')\">";
        $my_block .= "<span style=\"font-size:80%; font-style:italic; display:block;\">( Ammesse solo lettere e lettere accentate.)</span><br>";
        $my_block .= "<label for=\"corso\">" . get_string('course_label', 'block_searchunipicoursesremote') . "</label>";
        $my_block .= "<!--[if gte IE 7 or Opera]><input id=\"corso\" name=\"corso\"  type = \"text\" title = \"Inserire il corso da ricercare\" placeholder = \"Nome del Corso\" onkeydown= \"var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; if( keyCode == 13 ) document.getElementById('searchCourse').submit();\"></input><![endif]-->";
        $my_block .= "<input id=\"corso\" name=\"corso\"  type = \"text\" title = \"Inserire il corso da ricercare\" placeholder = \"Nome del Corso\" ></input>";
        $my_block .= "<label for=\"docente\">" . get_string('teacher_label', 'block_searchunipicoursesremote') . "</label>";
        $my_block .= "<!--[if gte IE 7 or Opera]><input id=\"docente\" name=\"docente\" type = \"text\" title = \"Inserire il cognome del docente titolare del corso da ricercare\" placeholder = \"Cognome del Docente\" onkeydown= \"var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode; if( keyCode == 13 ) document.getElementById('searchCourse').submit();\"></input><![endif]-->";
        $my_block .= "<input id=\"docente\" name=\"docente\" type = \"text\" title = \"Inserire il cognome del docente titolare del corso da ricercare\" placeholder = \"Cognome del Docente\" ></input>";
        $my_block .= "<p id=\"errorDescription\"></p>";
        $my_block .= "<input type=\"submit\" name=\"cerca\" id=\"cerca\" value= \"Cerca\" style=\"display:none;\">";
        $my_block .= "</div>";      

        $this->content->text = $my_block;
        
        return $this->content;
    }

    public function applicable_formats() {
        return array(
            'site-index' => true,
            'site' => true,
            'course-view' => true,
            'my' => true
        );
    }
}


