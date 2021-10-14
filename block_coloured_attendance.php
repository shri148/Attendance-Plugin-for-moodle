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
 * Form for editing HTML block instances.
 *
 * @package   block_coloured_attendance
 */

class block_coloured_attendance extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_coloured_attendance');
    }

    
    function get_content() {

        global $DB;
        
        if ($this->content !== NULL) {
            return $this->content;
        }
        $userstring='';
        $grades= $DB->get_records('grade_grades');
        $users= $DB->get_records('user');
        $courses=$DB->get_records('course');
        $items=$DB->get_records('grade_items');
        $courseid=0;
        $itemid=0;
        $userid=0;
        $grade1=-1;
        $grade2=-1;
        $grade3=-1;
        $first=-1;
        $second=-1;
        $third=-1;

        $gr;

        foreach($courses as $course){

            if($course->category > 0){
                
                $courseid=$course->id;

                $userstring.='<b style="color:blue">'. $course->fullname . '</b><br>' ;
            }
            else
            {

                continue;
            }

            foreach($items as $item){

                
                if($item->courseid== $courseid && $item->itemname == "Attendance"){

                    $itemid=$item->id;
                    break;

                }
            }
            foreach($grades as $grade){

                if($grade->itemid == $itemid ){
                    $gr[$grade->userid] =$grade->finalgrade;
                }  

            }
                
            arsort($gr);
            $seq=1;
            foreach ($gr as $grad=> $att){ 
                foreach($users as $user){
                  
                    if($user->id == $grad){
                        if($att>=75)
                            $userstring.='<br>' . '<b style="color:darkgreen;">' .$seq++ . '.' . $user->lastname . ' ' . $att  .'</b>' ;
                        else
                        $userstring.='<br>' . '<b style="color:red;">' .$seq++ . '.' . $user->lastname . ' ' . $att  .'</b>' ;

                        }
                    }
            }                 
            
        }

       $this->content = new stdClass;
       
        $this->content->text = $userstring;
        return $this->content;

        
    }

   
}
