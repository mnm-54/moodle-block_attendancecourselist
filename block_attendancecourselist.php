<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * This is a one-line short description of the file.
 *
 * @package    block_attendancecourselist
 * @copyright  2022 munem
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_attendancecourselist extends block_base
{
    public function init()
    {
        $this->title = get_string('attendancecourselist', 'block_attendancecourselist');
    }
    public function can_view() {
        global $DB, $USER;
        $teacherroleid = $DB->get_field('role', 'id', ['shortname' => 'editingteacher']);
        $coursecreatorroleid = $DB->get_field('role', 'id', ['shortname' => 'coursecreator']);
        $managerroleid = $DB->get_field('role', 'id', ['shortname' => 'manager']);

        $teachercap = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $teacherroleid]);
        $coursecreatorcap =  $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $coursecreatorroleid]);
        $managercap = $DB->record_exists('role_assignments', ['userid' => $USER->id, 'roleid' => $managerroleid]);

        if(is_siteadmin() || $teachercap || $managercap || $coursecreatorcap) {
            return true;
        } else {
            false;
        }
    }
    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }
        global $DB, $CFG;
        $courses = $DB->get_records_select("course", "visible = :visible", array('visible' => 1));
        if(!$this->can_view()) {

            $this->content = get_string('no_permission', 'block_attendancecourselist');
            return $this->content;
        }

        array_shift($courses);

        $this->content = new stdClass;
        $this->content->text = "<hr>";

        foreach ($courses as $course) {
            $course_img_url = new moodle_url('/local/participant_image_upload/manage.php', array('cid' => $course->id));
            $buttontext = get_string('students_text', 'block_attendancecourselist');
            $this->content->text .= "
            <div class='d-flex justify-content-between mb-3'>
                <div class='d-flex align-items-center'>" . $course->fullname . "</div>
                <div>
                    <a href='" . $course_img_url . "' class='btn btn-primary'>
                      " . $buttontext . "     
                    </a>
                </div>
            </div>
            ";
        }

        return $this->content;
    }
}
