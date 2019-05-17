<?php

namespace App\Modules\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Core\Entities\Log;

class CMSRevisioningModel extends Model
{
    public function getAllDirtyObjects()
    {
        $result = DB::select("SELECT co.id
              , co.name
              , lot.name AS type
              , co.revision AS rev
              , u.email AS updated_by
            FROM cms_objects co
            JOIN users u ON u.id = co.updated_by
            JOIN cms_lu_object_types lot ON lot.id = co.attr1sint
            WHERE co.is_dirty = 1");

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getMyDirtyObjects()
    {
        $currentUserId = Auth::user()->id;
        $result = DB::select("SELECT co.id
              , co.name
              , lot.name AS type
              , co.revision AS rev
              , u.email AS updated_by 
            FROM cms_objects co
            JOIN users u ON u.id = co.updated_by
            JOIN cms_lu_object_types lot ON lot.id = co.attr1sint
            WHERE co.is_dirty = 1
            AND co.updated_by = :updated_by", ['updated_by' => $currentUserId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function insertCommit()
    {
        $committedByUserId = Auth::user()->id;
        $insertSQL = "INSERT INTO cms_commits(comment, is_published, created_by, updated_by, created_at, updated_at)" .
            " VALUES ('commit', 0, :created_by, :updated_by, NOW(), NOW())";

        DB::insert($insertSQL, [
            'created_by' => $committedByUserId,
            'updated_by' => $committedByUserId,
        ]);
        $objectId = DB::getPdo()->lastInsertId();

        return $objectId;
    }

    public function saveCommits($commitId, $committedObjectIds)
    {
        if (empty($committedObjectIds) || !is_array($committedObjectIds)) {
            return;
        }

        $insertSQL = "INSERT IGNORE INTO cms_commit_objects(commit_id, object_id, created_at, updated_at) VALUES";
        foreach($committedObjectIds as $committedObjectId) {
            $insertSQL .= "({$commitId}, {$committedObjectId}, NOW(), NOW()),";
        }
        $insertSQL = rtrim($insertSQL, ",");
        DB::insert($insertSQL);
    }

    public function markAsClean($committedObjectIds)
    {
        $committedIdsString = implode(",", $committedObjectIds);
        if (empty($committedIdsString)) {
            return;
        }

        $committedByUserId = Auth::user()->id;

        DB::update("UPDATE cms_objects co
            JOIN cms_commit_objects cco ON cco.object_id = co.id
            JOIN cms_commits cc ON cc.id = cco.commit_id
            SET co.is_dirty = 0
                , co.revision = co.revision + 1
                , co.revision_at = NOW()
                , co.updated_by = :updated_by
                , co.updated_at = NOW()
            WHERE co.id IN ({$committedIdsString})
            AND cc.is_published = 0", ['updated_by' => $committedByUserId]);

        DB::update("UPDATE cms_objects co
            JOIN cms_objects co2 ON co2.attr1int = co.id
            SET co.is_dirty = 0
                , co.revision = co.revision + 1
                , co.revision_at = NOW()
                , co.updated_by = :updated_by
                , co.updated_at = NOW()
            WHERE co2.id IN ({$committedIdsString})", ['updated_by' => $committedByUserId]);

        DB::update("UPDATE cms_objects co
            JOIN cms_objects co2 ON co2.attr2int = co.id
            SET co.is_dirty = 0
                , co.revision = co.revision + 1
                , co.revision_at = NOW()
                , co.updated_by = :updated_by
                , co.updated_at = NOW()
            WHERE co2.id IN ({$committedIdsString})", ['updated_by' => $committedByUserId]);
    }

    public function getNumUnpublishedObjects()
    {
        $result = DB::select("SELECT COUNT(DISTINCT co.id) AS num_unpublished_objects
            FROM cms_objects co
            JOIN cms_lu_object_types lot ON lot.id = co.attr1sint
            JOIN cms_commit_objects cco ON cco.object_id = co.id
            JOIN cms_commits cc ON cc.id = cco.commit_id
            WHERE cc.is_published = 0");

        if (!empty($result[0]->num_unpublished_objects)) {
            return $result[0]->num_unpublished_objects;
        }

        return 0;
    }

    public function publishObjects()
    {
        $publishedByUserId = Auth::user()->id;

        DB::statement("REPLACE INTO lib_objects
          SELECT co.*
          FROM cms_objects co
          JOIN cms_commit_objects cco ON cco.object_id = co.id
          JOIN cms_commits cc ON cc.id = cco.commit_id
          WHERE cc.is_published = 0");

        DB::statement("REPLACE INTO lib_objects
          SELECT co2.*
          FROM cms_objects co
          JOIN cms_objects co2 ON co2.id = co.attr1int
          JOIN cms_commit_objects cco ON cco.object_id = co.id
          JOIN cms_commits cc ON cc.id = cco.commit_id
          WHERE cc.is_published = 0");

        DB::statement("REPLACE INTO lib_objects
          SELECT co2.*
          FROM cms_objects co
          JOIN cms_objects co2 ON co2.id = co.attr2int
          JOIN cms_commit_objects cco ON cco.object_id = co.id
          JOIN cms_commits cc ON cc.id = cco.commit_id
          WHERE cc.is_published = 0");

        /*DB::statement("REPLACE INTO lib_relations
          SELECT cr.*
          FROM cms_relations cr
          JOIN cms_objects co ON co.id = cr.attr1int
          JOIN cms_commit_objects cco ON cco.object_id = co.id
          JOIN cms_commits cc ON cc.id = cco.commit_id
          WHERE cc.is_published = 0");

        DB::statement("REPLACE INTO lib_relations
          SELECT cr.*
          FROM cms_relations cr
          JOIN cms_objects co ON co.id = cr.attr2int
          JOIN cms_commit_objects cco ON cco.object_id = co.id
          JOIN cms_commits cc ON cc.id = cco.commit_id
          WHERE cc.is_published = 0");*/

        DB::statement("REPLACE INTO lib_relations
          SELECT cr.*
          FROM cms_relations cr
          JOIN cms_objects co1 ON co1.id = cr.attr1int
          JOIN cms_objects co2 ON co2.id = cr.attr2int
          WHERE cr.is_dirty = 1
          AND co1.is_dirty = 0
          AND co2.is_dirty = 0
          ");

        DB::update("UPDATE lib_relations
            SET is_dirty = 0
            WHERE is_dirty = 1");

        DB::update("UPDATE cms_relations cr
            JOIN cms_objects co1 ON co1.id = cr.attr1int
            JOIN cms_objects co2 ON co2.id = cr.attr2int
            SET cr.is_dirty = 0
            WHERE cr.is_dirty = 1
            AND co1.is_dirty = 0
            AND co2.is_dirty = 0");

        DB::update("UPDATE cms_commits cc
            SET cc.is_published = 1
                , cc.published_by = :published_by
                , cc.published_at = NOW()
                , cc.updated_by = :updated_by
                , cc.updated_at = NOW()
            WHERE cc.is_published = 0", [
                'published_by' => $publishedByUserId,
                'updated_by' => $publishedByUserId,
        ]);
    }

    public function createLibraryViews()
    {
        $views = DB::select("SELECT table_schema, table_name
            FROM information_schema.tables
            WHERE table_type LIKE 'VIEW'
            AND table_name LIKE 'cms_%';");

        function getViews($views)
        {
            foreach ($views as $view) {
                yield $view->table_name;
            }
        }

        foreach (getViews($views) as $viewName) {
            $viewStatements = DB::select("SHOW CREATE VIEW " . $viewName);
            foreach ($viewStatements as $viewStatement) {
                $searchArray = ['cms_obj_', 'cms_rel_'];
                $replaceArray = ['lib_obj_', 'lib_rel_'];
                $viewCreateFragment = preg_replace('/.*?DEFINER VIEW .*? AS (.*$)/', '$1', $viewStatement->{"Create View"});
                $viewName = str_replace($searchArray, $replaceArray, $viewName);
                $viewDefinition = str_replace($searchArray, $replaceArray, $viewCreateFragment);
                $viewSQL = "CREATE OR REPLACE VIEW " . $viewName . " AS " . $viewDefinition;
                DB::statement($viewSQL);
            }
        };
    }
}
