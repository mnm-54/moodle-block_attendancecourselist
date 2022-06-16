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

    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }
        global $DB, $CFG;
        $courses = $DB->get_records("course");
        array_shift($courses);

        $this->content         =  new stdClass;
        $this->content->text   = '<h1 style="display: flex; justify-content: center;font-size: 1.5em; align-items: center;">Available courses</h1><hr><br><br>';
        foreach ($courses as $course) {
            $course_img_url = $CFG->wwwroot . '/local/participant_image_upload/manage.php?cid=' . $course->id;
            $this->content->text .= "<div>" . $course->fullname
                . '<button type="button" style="float: right;border-radius:5px; padding:5px" class="btn-primary" onclick="location.href=\'' . $course_img_url . '\'">Student-list</button>'
                . "</div><br><br>";
        }
        $this->content->footer = get_string('footer', 'block_attendancecourselist');

        return $this->content;
    }
}
