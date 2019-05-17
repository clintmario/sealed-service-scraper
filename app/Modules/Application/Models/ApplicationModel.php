<?php

namespace App\Modules\Application\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicationModel extends Model
{
    //
    public function getPendingAssignmentsInSubject($subjectName, $userId)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS assignment_name
                , la.id AS assignment_id
                , COALESCE(cae.percent_progress, 0) AS progress
                , IF(cae.assignment_id IS NULL, 0, 1) AS is_started
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
            LEFT JOIN com_assignment_enrollments cae ON cae.assignment_id = la.id AND cae.user_id = :user_id
            WHERE tg.name = :subject_name
            AND lotg.name = 'Browse Category'
            AND (cae.is_completed IS NULL OR cae.is_completed = 0)
            ORDER BY rtg2.item_order ASC", ['subject_name' => $subjectName, 'user_id' => $userId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getCompletedAssignmentsInSubject($subjectName, $userId)
    {
        $result = DB::select("SELECT tg.name AS subject
                , la.name AS assignment_name
                , la.id AS assignment_id
                , COALESCE(cae.percent_progress, 0) AS progress
                , IF(cae.assignment_id IS NULL, 0, 1) AS is_started
                , cae.is_completed
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
            JOIN com_assignment_enrollments cae ON cae.assignment_id = la.id AND cae.user_id = :user_id AND cae.is_completed = 1
            WHERE tg.name = :subject_name
            AND lotg.name = 'Browse Category'
            ORDER BY rtg2.item_order ASC", ['subject_name' => $subjectName, 'user_id' => $userId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getAssignmentTracksAndLessons($assignmentId, $userId)
    {
        $result = DB::select("SELECT la.name AS assignment_name
                , la.id AS assignment_id
                , ta.id AS track_id
                , ta.name AS track_name
                , le.id AS lesson_id
                , le.name AS lesson_name
                , COALESCE(cae.percent_progress, 0) AS progress
            FROM lib_obj_assignments la
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rat ON rat.from_id = la.id AND la.is_deleted = 0 AND rat.is_deleted = 0
            JOIN lib_obj_tracks ta ON ta.id = rat.to_id AND ta.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = ta.id AND rtl.is_deleted = 0
            JOIN lib_obj_lessons le ON le.id = rtl.to_id AND le.is_deleted = 0
            LEFT JOIN com_assignment_enrollments cae ON cae.assignment_id = la.id AND cae.user_id = :user_id
            WHERE la.id = :assignment_id
            ORDER BY rat.item_order ASC, rtl.item_order ASC", ['assignment_id' => $assignmentId, 'user_id' => $userId]
        );

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getLesson($lessonId)
    {
        $result = DB::select("SELECT le.id AS lesson_id
                , le.name AS lesson_name
                , le.description AS lesson_description
                , vi.file_name
            FROM lib_obj_lessons le
            JOIN lib_obj_videos vi ON le.primary_element_id = vi.id AND le.is_deleted = 0 AND vi.is_deleted = 0
            WHERE le.id = :lesson_id", ['lesson_id' => $lessonId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getLessonTags($lessonId)
    {
        $result = DB::select("SELECT le.id AS lesson_id
                , le.name AS lesson_name
                , le.description AS lesson_description
                , tg.name AS tag_group
                , ta.name AS tag
                , ta.id AS tag_id
            FROM lib_obj_lessons le
            JOIN lib_rel_lib_obj_lessons_has_lib_obj_tags rlt ON rlt.from_id = le.id AND rlt.is_deleted = 0 AND le.is_deleted = 0
            JOIN lib_obj_tags ta ON ta.id = rlt.to_id AND ta.is_deleted = 0
            JOIN lib_obj_tag_groups tg ON tg.id = ta.tag_group_id AND tg.is_deleted = 0
            WHERE le.id = :lesson_id", ['lesson_id' => $lessonId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function hasUserWatchedLesson($lessonId, $userId)
    {
        $result = DB::select("SELECT id
            FROM com_lesson_views
            WHERE lesson_id = :lesson_id
            AND user_id = :user_id
            LIMIT 1", ['lesson_id' => $lessonId, 'user_id' => $userId]);

        return !empty($result[0]->id);
    }

    public function insertLessonView($lessonId, $userId)
    {
        $result = DB::insert("INSERT INTO com_lesson_views(lesson_id, user_id, created_at, updated_at)
            VALUES(:lesson_id, :user_id, NOW(), NOW())", ['lesson_id' => $lessonId, 'user_id' => $userId]
        );
    }

    public function getAssignmentsForLesson($lessonId)
    {
        $result = DB::select("SELECT la.id AS assignment_id
            FROM lib_obj_assignments la
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rat ON rat.from_id = la.id AND rat.is_deleted = 0 AND la.is_deleted = 0
            JOIN lib_obj_tracks ta ON ta.id = rat.to_id AND ta.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = ta.id AND rtl.is_deleted = 0
            JOIN lib_obj_lessons le ON le.id = rtl.to_id AND le.is_deleted = 0
            WHERE le.id = :lesson_id
            GROUP BY la.id", ['lesson_id' => $lessonId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getAssignmentProgress($assignmentId, $userId)
    {
        $result = DB::select("SELECT la.id AS assignment_id
              , COUNT(DISTINCT le.id) AS total_lessons
              , COUNT(DISTINCT clv.lesson_id) AS num_lessons_watched
            FROM lib_obj_assignments la
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rat ON rat.from_id = la.id AND rat.is_deleted = 0 AND la.is_deleted = 0
            JOIN lib_obj_tracks ta ON ta.id = rat.to_id AND ta.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = ta.id AND rtl.is_deleted = 0
            JOIN lib_obj_lessons le ON le.id = rtl.to_id AND le.is_deleted = 0
            LEFT JOIN com_lesson_views clv ON clv.lesson_id = le.id AND clv.user_id = :user_id
            WHERE la.id = :assignment_id
            GROUP BY la.id", ['assignment_id' => $assignmentId, 'user_id' => $userId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getAssignmentEnrollment($assignmentId, $userId)
    {
        $result = DB::select("SELECT assignment_id
                , user_id
                , percent_progress
                , is_completed
                , completed_at
                , created_at
            FROM com_assignment_enrollments
            WHERE assignment_id = :assignment_id
            AND user_id = :user_id", ['assignment_id' => $assignmentId, 'user_id' => $userId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function insertAssignmentEnrollment($assignmentId, $userId)
    {
        $result = DB::insert("INSERT IGNORE INTO com_assignment_enrollments(assignment_id, user_id, percent_progress, is_completed, created_at, updated_at)
            VALUES(:assignment_id, :user_id, 0, 0, NOW(), NOW())", ['assignment_id' => $assignmentId, 'user_id' => $userId]);
    }

    public function markAssignmentAsCompleted($assignmentId, $userId)
    {
        $result = DB::update("UPDATE com_assignment_enrollments
            SET is_completed = 1
              , completed_at = NOW()
            WHERE assignment_id = :assignment_id
            AND user_id = :user_id", ['assignment_id' => $assignmentId, 'user_id' => $userId]);
    }

    public function updateAssignmentEnrollmentProgress($assignmentId, $userId, $percentProgress)
    {
        $result = DB::update("UPDATE com_assignment_enrollments
            SET percent_progress = :percent_progress
            WHERE assignment_id = :assignment_id
            AND user_id = :user_id", ['assignment_id' => $assignmentId, 'user_id' => $userId, 'percent_progress' => $percentProgress]);
    }

    public function getAssignmentById($assignmentId)
    {
        $result = DB::select("SELECT id
                , name
            FROM lib_obj_assignments
            WHERE id = :assignment_id
            AND is_deleted = 0", ['assignment_id' => $assignmentId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getFirstUnwatchedLessonInAssignment($assignmentId, $userId)
    {
        $result = DB::select("SELECT la.name AS assignment_name
                , la.id AS assignment_id
                , ta.id AS track_id
                , ta.name AS track_name
                , le.id AS lesson_id
                , le.name AS lesson_name
            FROM lib_obj_assignments la
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rat ON rat.from_id = la.id AND la.is_deleted = 0 AND rat.is_deleted = 0
            JOIN lib_obj_tracks ta ON ta.id = rat.to_id AND ta.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = ta.id AND rtl.is_deleted = 0
            JOIN lib_obj_lessons le ON le.id = rtl.to_id AND le.is_deleted = 0
            WHERE la.id = :assignment_id
            AND NOT EXISTS(SELECT clv.id
              FROM com_lesson_views clv
              WHERE clv.lesson_id = le.id
              AND clv.user_id = :user_id
            )
            ORDER BY rat.item_order ASC, rtl.item_order ASC
            LIMIT 1", ['assignment_id' => $assignmentId, 'user_id' => $userId]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getFirstLessonInAssignment($assignmentId)
    {
        $result = DB::select("SELECT la.name AS assignment_name
                , la.id AS assignment_id
                , ta.id AS track_id
                , ta.name AS track_name
                , le.id AS lesson_id
                , le.name AS lesson_name
            FROM lib_obj_assignments la
            JOIN lib_rel_lib_obj_assignments_has_lib_obj_tracks rat ON rat.from_id = la.id AND la.is_deleted = 0 AND rat.is_deleted = 0
            JOIN lib_obj_tracks ta ON ta.id = rat.to_id AND ta.is_deleted = 0
            JOIN lib_rel_lib_obj_tracks_has_lib_obj_lessons rtl ON rtl.from_id = ta.id AND rtl.is_deleted = 0
            JOIN lib_obj_lessons le ON le.id = rtl.to_id AND le.is_deleted = 0
            WHERE la.id = :assignment_id
            ORDER BY rat.item_order ASC, rtl.item_order ASC
            LIMIT 1", ['assignment_id' => $assignmentId]
        );

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function addMessage($name, $email, $subject, $message)
    {
        DB::insert("INSERT INTO contacts(name, email, subject, message, created_at, updated_at)
          VALUES(:name, :email, :subject, :message, NOW(), NOW())", [ 'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message]);
    }
}
