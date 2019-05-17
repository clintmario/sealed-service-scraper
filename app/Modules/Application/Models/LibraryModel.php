<?php

namespace App\Modules\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LibraryModel extends Model
{
    //
    public function getTagsInTagGroupByName($tagGroupName)
    {
        $result = DB::select("SELECT tg.name AS tag_group_name
                , t.name AS tag_name
                , t.id AS tag_id
                , rtg.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            WHERE tg.name = :tag_group_name
            ORDER BY rtg.item_order ASC", ['tag_group_name' => $tagGroupName]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsInTagGroupById($tagGroupId)
    {
        $result = DB::select("SELECT tg.name AS tag_group_name
                , t.name AS tag_name
                , t.id AS tag_id
                , rtg.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            WHERE tg.id = :tag_group_id
            ORDER BY rtg.item_order ASC", ['tag_group_id' => $tagGroupId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getSubjectStatistics($subjectName)
    {
        $result = DB::select("SELECT ltg.name AS subject_name
                , ltg.id AS subject_id
                , COUNT(DISTINCT rtc.to_id) AS num_courses
                , COUNT(DISTINCT rta.from_id) AS num_assignments
                , COUNT(DISTINCT rttr.from_id) AS num_tracks
                , COUNT(DISTINCT rtl.from_id) AS num_lessons
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtt
            JOIN lib_obj_tags lt ON rtt.to_id = lt.id AND rtt.is_deleted = 0 AND lt.is_deleted = 0
            JOIN lib_obj_tag_groups ltg ON rtt.from_id = ltg.id AND ltg.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtc ON rtc.from_id = ltg.id AND rtc.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags rta ON rta.to_id = rtc.to_id AND rta.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags rttr ON rttr.to_id = rtc.to_id AND rttr.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rtl ON rtl.to_id = rtc.to_id AND rtl.is_deleted = 0
            WHERE ltg.name = :subject_name
            GROUP BY ltg.id, ltg.name", ['subject_name' => $subjectName]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getCourseStatistics($subjectName, $courseName)
    {
        $result = DB::select("SELECT lt.name AS course_name
                , lt.id AS course_id
                , COUNT(DISTINCT rta.from_id) AS num_assignments
                , COUNT(DISTINCT rttr.from_id) AS num_tracks
                , COUNT(DISTINCT rtl.from_id) AS num_lessons
            FROM lib_obj_tags lt
            JOIN lib_obj_tag_groups ltg ON ltg.id = lt.tag_group_id AND lt.is_deleted = 0 AND ltg.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags rta ON rta.to_id = lt.id AND rta.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags rttr ON rttr.to_id = lt.id AND rttr.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rtl ON rtl.to_id = lt.id AND rtl.is_deleted = 0
            WHERE lt.name = :course_name
            AND ltg.name = :subject_name
            GROUP BY lt.id, lt.name", ['subject_name' => $subjectName, 'course_name' => $courseName]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getAssignmentsInSubject($subjectName)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS assignment_name
                , la.id AS assignment_id
                , rtg2.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags raht ON raht.to_id = t.id AND raht.is_deleted = 0
            JOIN lib_obj_assignments la ON la.id = raht.from_id AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags raht2 ON raht2.from_id = la.id AND raht2.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = raht2.to_id AND lot.is_deleted = 0
            JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg2 ON rtg2.to_id = lot.id AND rtg2.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE tg.name = :subject_name
            AND lotg.name = 'Browse Category'
            ORDER BY rtg2.item_order ASC", ['subject_name' => $subjectName]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getAssignmentsByCourseId($courseId)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS assignment_name
                , la.id AS assignment_id
                , rtg2.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags raht ON raht.to_id = t.id AND raht.is_deleted = 0
            JOIN lib_obj_assignments la ON la.id = raht.from_id AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags raht2 ON raht2.from_id = la.id AND raht2.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = raht2.to_id AND lot.is_deleted = 0
            JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg2 ON rtg2.to_id = lot.id AND rtg2.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE t.id = :course_id
            AND lotg.name = 'Browse Category'
            ORDER BY rtg2.item_order ASC", ['course_id' => $courseId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getAssignmentStatistics($subjectName, $assignmentId)
    {
        $result = DB::select("SELECT la.name AS assignment_name
                , la.id AS assignment_id
                , COUNT(DISTINCT rttr.to_id) AS num_tracks
                , COUNT(DISTINCT rtl.to_id) AS num_lessons
            FROM lib_obj_tags lt
            JOIN lib_obj_tag_groups ltg ON ltg.id = lt.tag_group_id AND lt.is_deleted = 0 AND ltg.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags raht ON raht.to_id = lt.id AND raht.is_deleted = 0
            JOIN lib_obj_assignments la ON la.id = raht.from_id AND la.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rttr ON rttr.from_id = la.id AND rttr.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = rttr.to_id AND rtl.is_deleted = 0
            WHERE ltg.name = :subject_name
            AND la.id = :assignment_id
            GROUP BY la.id, la.name", ['subject_name' => $subjectName, 'assignment_id' => $assignmentId]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getTracksInSubject($subjectName)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS track_name
                , la.id AS track_id
                , rtg2.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags raht ON raht.to_id = t.id AND raht.is_deleted = 0
            JOIN lib_obj_tracks la ON la.id = raht.from_id AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks ratt ON ratt.to_id = la.id AND ratt.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags raht2 ON raht2.from_id = la.id AND raht2.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = raht2.to_id AND lot.is_deleted = 0
            JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg2 ON rtg2.to_id = lot.id AND rtg2.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE tg.name = :subject_name
            AND lotg.name = 'Browse Category'
            ORDER BY rtg2.item_order ASC, ratt.item_order ASC", ['subject_name' => $subjectName]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTracksByAssignmentId($assignmentId)
    {
        $result = DB::select("SELECT lot.name AS subject
                , la.name AS track_name
                , la.id AS track_id
                , rtg.item_order
            FROM lib_rel_lib_obj_assignments_has_lib_obj_tracks rtg
            JOIN lib_obj_tracks la ON la.id = rtg.to_id AND rtg.is_deleted = 0 AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags rtt ON rtt.from_id = la.id AND rtt.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = rtt.to_id AND lot.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE rtg.from_id = :assignment_id
            AND lotg.name = 'Broad Objective'
            ORDER BY rtg.item_order ASC", ['assignment_id' => $assignmentId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTrackStatistics($subjectName, $trackId)
    {
        $result = DB::select("SELECT la.name AS track_name
                , la.id AS track_id
                , COUNT(DISTINCT rtl.to_id) AS num_lessons
            FROM lib_obj_tags lt
            JOIN lib_obj_tag_groups ltg ON ltg.id = lt.tag_group_id AND lt.is_deleted = 0 AND ltg.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_tags raht ON raht.to_id = lt.id AND raht.is_deleted = 0
            JOIN lib_obj_tracks la ON la.id = raht.from_id AND la.is_deleted = 0
            LEFT JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = la.id AND rtl.is_deleted = 0
            WHERE ltg.name = :subject_name
            AND la.id = :track_id
            GROUP BY la.id, la.name", ['subject_name' => $subjectName, 'track_id' => $trackId]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getLessonsInSubject($subjectName)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS lesson_name
                , la.id AS lesson_id
                , rtg2.item_order
                , rtl.item_order
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg
            JOIN lib_obj_tag_groups tg ON rtg.from_id = tg.id AND rtg.is_deleted = 0 AND tg.is_deleted = 0
            JOIN lib_obj_tags t ON rtg.to_id = t.id AND t.is_deleted = 0
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags raht ON raht.to_id = t.id AND raht.is_deleted = 0
            JOIN lib_obj_lessons la ON la.id = raht.from_id AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.to_id = la.id AND rtl.is_deleted = 0
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags raht2 ON raht2.from_id = la.id AND raht2.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = raht2.to_id AND lot.is_deleted = 0
            JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtg2 ON rtg2.to_id = lot.id AND rtg2.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE tg.name = :subject_name
            AND lotg.name = 'Browse Category'
            ORDER BY rtg2.item_order ASC, rtl.item_order ASC", ['subject_name' => $subjectName]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getLessonsByTrackId($trackId)
    {
        $result = DB::select("SELECT lot.name AS subject
                , la.name AS lesson_name
                , la.id AS lesson_id
                , rtg.item_order
            FROM lib_rel_lib_obj_tracks_has_lib_obj_lessons rtg
            JOIN lib_obj_lessons la ON la.id = rtg.to_id AND rtg.is_deleted = 0 AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rtt ON rtt.from_id = la.id AND rtt.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = rtt.to_id AND lot.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot.tag_group_id AND lotg.is_deleted = 0
            WHERE rtg.from_id = :track_id
            AND lotg.name = 'Broad Objective'
            ORDER BY rtg.item_order ASC", ['track_id' => $trackId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTags()
    {
        $result = DB::select("SELECT ltg.name AS tag_group_name
                , lt.name AS tag_name
                , lt.id AS tag_id
            FROM lib_rel_lib_obj_tag_groups_has_lib_obj_tags rtt
            JOIN lib_obj_tag_groups ltg ON rtt.from_id = ltg.id AND rtt.is_deleted = 0 AND ltg.is_deleted = 0
            JOIN lib_obj_tags lt ON lt.id = rtt.to_id AND lt.is_deleted = 0
            WHERE ltg.name IN ('Physics', 'Chemistry', 'Mathematics', 'Broad Objective', 'Browse Category', 'Primary Skill')
            ORDER BY ltg.name ASC, lt.name ASC"
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagStatistics($tagId)
    {
        $result = DB::select("SELECT COUNT(DISTINCT raht.from_id) AS num_lessons
            FROM lib_rel_lib_obj_lessons_has_lib_obj_tags raht
            JOIN lib_obj_lessons l ON l.id = raht.from_id AND l.is_deleted = 0 AND raht.is_deleted = 0
            JOIN lib_obj_tags t ON t.id = raht.to_id AND t.is_deleted = 0
            WHERE raht.to_id = :tag_id", ['tag_id' => $tagId]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getLessonsByTagId($tagId)
    {
        $result = DB::select("SELECT lot2.name AS subject
                , la.name AS lesson_name
                , la.id AS lesson_id
                , ltgt.item_order
                , ratr.item_order
                , rtl.item_order
            FROM lib_obj_tag_groups ltg
            JOIN lib_rel_lib_obj_tag_groups_has_lib_obj_tags ltgt ON ltg.id = ltgt.from_id AND ltg.is_deleted = 0 AND ltgt.is_deleted = 0 AND ltg.name = 'Browse Category'
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tags rat ON rat.is_deleted = 0 AND rat.to_id = ltgt.to_id
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks ratr ON ratr.is_deleted = 0 AND ratr.from_id = rat.from_id
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.is_deleted = 0 AND rtl.from_id = ratr.to_id
            JOIN lib_obj_lessons la ON la.id = rtl.to_id AND la.is_deleted = 0
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rtt ON rtt.from_id = la.id AND rtt.is_deleted = 0
            JOIN lib_obj_tags lot ON lot.id = rtt.to_id AND lot.is_deleted = 0
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rtt2 ON rtt2.from_id = la.id AND rtt2.is_deleted = 0
            JOIN lib_obj_tags lot2 ON lot2.id = rtt2.to_id AND lot2.is_deleted = 0
            JOIN lib_obj_tag_groups lotg ON lotg.id = lot2.tag_group_id AND lotg.is_deleted = 0 AND lotg.name = 'Broad Objective'
            WHERE lot.id = :tag_id
            ORDER BY ltgt.item_order ASC, ratr.item_order ASC, rtl.item_order ASC", ['tag_id' => $tagId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }
}
