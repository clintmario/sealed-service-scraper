<?php

namespace App\Modules\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\Core\Entities\Log;

class CMSSeedModel extends CMSModel
{
    public function getTagGroups()
    {
        $result = DB::select("SELECT id, tag_group
            FROM cms_tag_groups
            WHERE is_deleted = 0
            AND tag_group NOT IN ('Product', 'Topic', 'Subtopic', 'Digital Skills', 'Live Learning Group')
            ORDER BY tag_group ASC");

        $tagGroups = [];
        foreach ($result as $tagGroup) {
            array_push($tagGroups, $tagGroup);
        }

        return $tagGroups;
    }

    public function getTagsInTagGroups($tagGroupId)
    {
        $result = DB::select("SELECT ct.id, ct.tag, ct.slug, ct.weight, ct.description, ct.short_description, ct.cms_asset_name
              , ct.tag_group_id, ct.seo_meta_title, ct.seo_meta_description, ct.seo_meta_keywords
            FROM cms_tag_groups ctg
            JOIN cms_tags ct ON ct.tag_group_id = ctg.id
            WHERE ct.is_deleted = 0
            AND ctg.is_deleted = 0
            AND ctg.id = ?
            ORDER BY ct.tag ASC, ct.id ASC", [$tagGroupId]);

        $tags = [];
        foreach ($result as $tag) {
            array_push($tags, $tag);
        }

        return $tags;
    }

    public function getLessons()
    {
        $result = DB::select("SELECT cl.id
              , cqu.id AS question_id
              , cqa.id AS answer_id
              , cq.id AS quiz_id
              , cl.display_name AS LessonName
              , cl.description AS LessonDescription
              , cl.short_description AS LessonShortDescription
              , cl.release_date AS LessonReleaseDate
              , cl.slug AS LessonSlug
              , cl.filename AS VideoFileName
              , cl.duration AS VideoDuration
              , cl.transcript AS LessonTranscript
              , clo.seo_meta_title AS SEOTitle
              , clo.seo_meta_description AS SEODescription
              , clo.seo_meta_keywords AS SEOKeyWords
              , cqu.question_json AS QuestionStem
              , cqa.answer_json AS AnswerStem
              , cqa.is_correct AS IsAnswerCorrect
            FROM cms_lessons cl 
            JOIN cms_learning_objects clo ON clo.id = cl.id AND clo.is_deleted = 0 AND cl.is_deleted = 0
            JOIN cms_lo_lt_sat_map cllsm ON cllsm.learning_object_id = clo.id AND cllsm.is_deleted = 0
            LEFT JOIN cms_quizzes cq ON cq.id = cl.quiz_id AND cq.is_deleted = 0
            LEFT JOIN cms_quiz_questions cqq ON cqq.quiz_id = cq.id AND cqq.is_deleted = 0
            LEFT JOIN cms_questions cqu ON cqu.id = cqq.question_id AND cqu.is_deleted = 0
            LEFT JOIN cms_question_answers cqa ON cqa.question_id = cqu.id AND cqa.is_deleted = 0
            WHERE clo.cms_id IS NOT NULL
            GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18
            ORDER BY cl.display_name ASC, cl.id ASC, cqq.question_order ASC, cqa.answer_order ASC");

        $lessons = [];
        foreach ($result as $lesson) {
            array_push($lessons, $lesson);
        }

        return $lessons;
    }

    public function getTagsForLesson($lessonId)
    {
        $result = DB::select("SELECT ct.id
            FROM cms_tags ct
            JOIN cms_tag_lo_map ctlm ON ctlm.tag_id = ct.id AND ctlm.is_deleted = 0 AND ct.is_deleted = 0
            WHERE ctlm.learning_object_id = ?
            ORDER BY ct.tag ASC", [$lessonId]);

        $tags = [];
        foreach ($result as $tag) {
            array_push($tags, $tag);
        }

        return $tags;
    }

    public function getTracks()
    {
        $result = DB::select("SELECT clt.id
              , clt.display_name AS TrackName
              , clt.description AS TrackDescription
              , clt.short_description AS TrackShortDescription
              , clt.slug AS TrackSlug
              , clt.seo_meta_title AS SEOTitle
              , clt.seo_meta_description AS SEODescription
              , clt.seo_meta_keywords AS SEOKeyWords
            FROM cms_learning_tracks clt
            WHERE clt.cms_id IS NOT NULL
            AND clt.is_deleted = 0
            ORDER BY clt.display_name ASC, clt.id ASC");

        $tracks = [];
        foreach ($result as $track) {
            array_push($tracks, $track);
        }

        return $tracks;
    }

    public function getLessonsInTrack($trackId)
    {
        $result = DB::select("SELECT cl.id
            FROM cms_lessons cl
            JOIN cms_learning_objects clo ON clo.id = cl.id AND clo.is_deleted = 0 AND cl.is_deleted = 0
            JOIN cms_learning_track_items clti ON clti.learning_object_id = clo.id AND clti.is_deleted = 0
            JOIN cms_learning_tracks clt ON clt.id = clti.learning_track_id AND clt.is_deleted = 0
            WHERE clt.id = ?
            ORDER BY clti.track_item_order ASC", [$trackId]);

        $lessons = [];
        foreach ($result as $lesson) {
            array_push($lessons, $lesson);
        }

        return $lessons;
    }
}
