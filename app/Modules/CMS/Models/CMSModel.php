<?php

namespace App\Modules\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Core\Entities\Log;
use Illuminate\Support\Facades\Schema;

class CMSModel extends Model
{
    const CMS_EXCLUDED_FIELDS = ['id', 'meta1_id', 'meta2_id', 'is_dirty', 'created_at', 'updated_at',
        'created_by', 'updated_by', 'revision', 'revision_at'];
    //const CMS_INSERT_EXCLUDED_FIELDS = ['id', 'created_at', 'updated_at'];
    private $tmpTableName;

    public function createView($sql)
    {
        DB::statement($sql);
    }

    public function existsObjectById($objectId, $schema)
    {
        $result = DB::select("SELECT *
            FROM {$schema}
            WHERE id = :object_id", ['object_id' => $objectId]);

        return !empty($result);
    }

    public function insertObjectPart($parts, $object, $schema)
    {
        if (empty($parts)) {
            return null;
        }

        $schemaArray = [];
        array_map(function($element) use (&$schemaArray) {
            if (!in_array($element, self::CMS_EXCLUDED_FIELDS)) {
                array_push($schemaArray, $element);
            }
        }, $parts);
        $schemaString = implode(", ", $schemaArray);
        $paramArray = [];
        array_map(function($element) use (&$paramArray) {
            array_push($paramArray, ":" . $element);
        }, $schemaArray);
        $paramString = implode(", ", $paramArray);
        $objectArray = [];
        array_map(function($element) use (&$objectArray, $object) {
                $objectArray[$element] = $object->$element;
        }, $schemaArray);
        $objectArray['created_by'] = Auth::user()->id ?? 2;
        $objectArray['updated_by'] = Auth::user()->id ?? 2;

        $insertSQL = "INSERT INTO {$schema}({$schemaString}, is_dirty, created_by, updated_by, created_at, updated_at)" .
            " VALUES ({$paramString}, 1, :created_by, :updated_by, NOW(), NOW())";

        DB::insert($insertSQL, $objectArray);
        $objectId = DB::getPdo()->lastInsertId();

        return $objectId;
    }

    public function updateObjectPart($parts, $object, $schema, $index)
    {
        if (empty($parts) || empty($object->id)) {
            return null;
        }

        $schemaArray = [];
        array_map(function($element) use (&$schemaArray) {
            if (!in_array($element, self::CMS_EXCLUDED_FIELDS)) {
                array_push($schemaArray, $element);
            }
        }, $parts);
        $paramArray = [];
        array_map(function($element) use (&$paramArray) {
            array_push($paramArray, $element . " = ?");
        }, $schemaArray);
        $paramString = implode("\n, ", $paramArray);
        $objectArray = [];
        array_map(function($element) use (&$objectArray, $object) {
            $objectArray[$element] = $object->$element;
        }, $schemaArray);

        $valuesArray = array_values($objectArray);
        if ($index == 0) {
            array_push($valuesArray, $object->id);
        }
        else {
            $field = "meta" . $index . "_id";
            $fieldId = $object->$field;
            if (empty($fieldId)) {
                return null;
            }
            array_push($valuesArray, $fieldId);
        }
        $paramString = "updated_by = ?\n, " . $paramString;
        array_unshift($valuesArray, Auth::user()->id ?? 2);

        if ($index == 0) {
            DB::update("UPDATE {$schema}
                SET {$paramString}
                  , is_dirty = 1
                  , updated_at = NOW()
                WHERE id = ?", $valuesArray);
        }
        else {
            DB::update("UPDATE {$schema}
                SET {$paramString}
                  , is_dirty = 1
                  , updated_at = NOW()
                WHERE meta{$index}_id = ?", $valuesArray);
        }

        return $object->id;
    }

    public function deleteObject($object, $schema)
    {
        if (empty($object->id)) {
            return null;
        }

        DB::update("UPDATE {$schema}
            SET is_deleted = 1
              , is_dirty = 1
              , deleted_at = NOW()
              , updated_at = NOW()
            WHERE id = ?", [$object->id]);

        return $object->id;
    }

    public function updateObjectMeta($schema, $objectId, $partObjectId, $index)
    {
        if (empty($objectId)) {
            return;
        }

        $currentUserId = Auth::user()->id ?? 2;

        DB::update("UPDATE {$schema}
            SET meta{$index}_id = ?
              , is_dirty = 1
              , updated_by = ?
              , updated_at = NOW()
            WHERE id = ?", [$partObjectId, $currentUserId, $objectId]);
    }

    public function existsRelation($objectId1, $objectId2, $type, $schema)
    {
        if (empty($objectId1) || empty($objectId2)) {
            return null;
        }

        $result = DB::select("SELECT *
            FROM {$schema}
            WHERE from_id = :from_id
            AND to_id = :to_id
            AND type = :type
            AND is_deleted = 0", ['from_id' => $objectId1, 'to_id' => $objectId2, 'type' => $type]);

        return !empty($result);
    }

    public function saveRelation($objectId1, $objectId2, $type, $schema, $order = NULL)
    {
        if (empty($objectId1) || empty($objectId2)) {
            return null;
        }

        DB::insert("INSERT INTO {$schema}(from_id, to_id, type, item_order, is_deleted, is_dirty, created_at, updated_at)
          VALUES(:from_id, :to_id, :type, :item_order, 0, 1, NOW(), NOW())
          ON DUPLICATE KEY UPDATE item_order=VALUES(item_order), is_deleted = 0, is_dirty = 1, updated_at = NOW()",
            ['from_id' => $objectId1, 'to_id' => $objectId2, 'type' => $type, 'item_order' => $order]);
    }

    public function deleteRelation($objectId1, $objectId2, $type, $schema)
    {
        if (empty($objectId1) || empty($objectId2)) {
            return null;
        }

        DB::update("UPDATE {$schema}
            SET is_deleted = 1
              , is_dirty = 1
              , deleted_at = NOW()
              , updated_at = NOW()
            WHERE from_id = :from_id
            AND to_id = :to_id
            AND type = :type", ['from_id' => $objectId1, 'to_id' => $objectId2, 'type' => $type]);
    }

    public function removeRelationsNotInIds($objectId, $idsString, $type, $schema)
    {
        if (empty($objectId)) {
            return;
        }

        DB::update("UPDATE {$schema}
            SET is_deleted = 1
                , is_dirty = 1
                , deleted_at = NOW()
                , updated_at = NOW()
            WHERE from_id = :from_id
            AND to_id NOT IN ({$idsString})
            AND type = :type", ['from_id' => $objectId, 'type' => $type]);
    }

    public function saveField($objectId, $fieldName, $hasId, $schema)
    {
        if (empty($objectId)) {
            return;
        }

        $currentUserId = Auth::user()->id ?? 2;

        DB::update("UPDATE {$schema}
            SET $fieldName = :field
            WHERE id = :id", ['field' => $hasId, 'id' => $objectId]);

        DB::update("UPDATE {$schema}
            SET is_dirty = 1
              , updated_at = NOW()
              , updated_by = :updated_by
            WHERE id = :id", ['id' => $objectId, 'updated_by' => $currentUserId]);
    }

    public function existsLookupById($lookupId, $lookupTable)
    {
        $result = DB::select("SELECT *
            FROM {$lookupTable}
            WHERE id = :lookup_id", ['lookup_id' => $lookupId]);

        return !empty($result);
    }

    public function existsLookupByName($lookupName, $lookupTable)
    {
        $result = DB::select("SELECT *
            FROM {$lookupTable}
            WHERE name = :lookup_name", ['lookup_name' => $lookupName]);

        return !empty($result);
    }

    public function getLookupById($lookupId, $lookupTable)
    {
        $result = DB::select("SELECT *
            FROM {$lookupTable}
            WHERE id = :lookup_id", ['lookup_id' => $lookupId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getLookupByName($lookupName, $lookupTable)
    {
        $result = DB::select("SELECT *
            FROM {$lookupTable}
            WHERE name = :lookup_name", ['lookup_name' => $lookupName]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function addLookupById($lookupId, $lookupName, $lookupTable)
    {
        DB::insert("INSERT INTO {$lookupTable}(id, name, created_at, updated_at)
          VALUES(:lookup_id, :lookup_name, NOW(), NOW())", ['lookup_id' => $lookupId, 'lookup_name' => $lookupName]);
    }

    public function addLookupByName($lookupName, $lookupTable)
    {
        DB::insert("INSERT INTO {$lookupTable}(name, created_at, updated_at)
          VALUES(:lookup_name, NOW(), NOW())", ['lookup_name' => $lookupName]);
    }

    public function getObjectTypeById($objectId)
    {
        $result = DB::select("SELECT attr1sint
            FROM cms_objects
            WHERE id = :object_id", ['object_id' => $objectId]);

        if (!empty($result[0]->attr1sint)) {
            return $result[0]->attr1sint;
        }

        return null;
    }

    public function getObjectById($objectId, $schema)
    {
        $result = DB::select("SELECT *
            FROM {$schema} co
            JOIN {$schema}_meta1 cm1 ON cm1.meta1_id = co.meta1_id
            JOIN {$schema}_meta2 cm2 ON cm2.meta2_id = co.meta2_id
            WHERE co.id = :object_id", ['object_id' => $objectId]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;
    }

    public function getObjects($schema, $start, $limit)
    {
        $result = DB::select("SELECT *
            FROM {$schema} co
            JOIN {$schema}_meta1 cm1 ON cm1.meta1_id = co.meta1_id
            JOIN {$schema}_meta2 cm2 ON cm2.meta2_id = co.meta2_id
            ORDER BY co.updated_at DESC
            LIMIT {$start}, {$limit}");

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getActiveObjectsLean($schema, $selectedFilters, $start, $limit)
    {
        $relationSchema = "cms_rel_" . $schema . "_has_cms_obj_tags";
        $searchKeywords = $selectedFilters['search_keywords'] ?? '';
        unset($selectedFilters['search_keywords']);

        $joinSQL = "";
        if (!empty($selectedFilters) && Schema::hasTable($relationSchema)) {
            $joinIndex = 0;
            foreach(array_values($selectedFilters) as $tagId) {
                $joinIndex++;
                $joinSQL .= "JOIN " . $relationSchema . " ot{$joinIndex} ON ot{$joinIndex}.from_id = co.id
                    AND ot{$joinIndex}.to_id = {$tagId} AND ot{$joinIndex}.is_deleted = 0\n";
            }
        }

        /*$searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            $searchJoinSQL = "JOIN (SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.meta1_id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.meta2_id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.meta1_id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.meta2_id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
			) cos1 ON cos1.id = co.id";
        }*/

        $searchAndSQL = "";
        $searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            $this->tmpTableName = "tmp_searches_" . uniqid();
            DB::statement("SET SESSION group_concat_max_len = 1000000000");
            DB::statement("SET @cms_search_ids := (SELECT COALESCE(CONCAT_WS(','
                            , COALESCE(GROUP_CONCAT(DISTINCT co1.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co2.id), 0)
                            , COALESCE(GROUP_CONCAT(DISTINCT co3.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co4.id), 0)
                            , COALESCE(GROUP_CONCAT(DISTINCT co5.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co6.id), 0)), 0) AS cms_search_ids
                FROM(SELECT cos.id
                    FROM cms_objects cos
                    WHERE MATCH(cos.name, cos.description, cos.attr1str, cos.attr2str, cos.attr1text) AGAINST('{$searchKeywords}')
                    AND (EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.meta1_id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.meta2_id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = cr.attr2int
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = co.meta1_id
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = co.meta2_id
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                    )
                    LIMIT 100
                ) AS cos
                LEFT JOIN {$schema} co1 ON co1.id = cos.id AND co1.is_deleted = 0
                LEFT JOIN {$schema} co2 ON co2.meta1_id = cos.id AND co2.is_deleted = 0
                LEFT JOIN {$schema} co3 ON co3.meta2_id = cos.id AND co3.is_deleted = 0
                LEFT JOIN cms_relations cr ON cr.attr2int = cos.id
                LEFT JOIN {$schema} co4 ON co4.id = cr.attr1int AND co4.is_deleted = 0
                LEFT JOIN {$schema} co5 ON co5.meta1_id = cos.id AND co5.id = co4.id AND co5.is_deleted = 0
                LEFT JOIN {$schema} co6 ON co6.meta1_id = cos.id AND co6.id = co4.id AND co6.is_deleted = 0
                )");
            DB::statement("CREATE TEMPORARY TABLE {$this->tmpTableName} (id INT(10), PRIMARY KEY(id)) AS
                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(@cms_search_ids, ',', n.n), ',', -1) AS id
                FROM(
                  SELECT a.N + b.N * 10 + 1 n
                  FROM 
                    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
                ) n
                WHERE n.n <= 1 + (LENGTH(@cms_search_ids) - LENGTH(REPLACE(@cms_search_ids, ',', '')))
                GROUP BY id");
            $searchJoinSQL = "JOIN {$this->tmpTableName} ts ON ts.id = co.id";
            //$searchAndSQL = "AND FIND_IN_SET(co.id, @cms_search_ids)";
        }

        $result = DB::select("SELECT co.id
              , co.name
              , co.slug
              , co.revision AS rev
              , u.email AS updated_by
              , co.updated_at
            FROM {$schema} co
            JOIN users u ON co.updated_by = u.id
            {$joinSQL}
            {$searchJoinSQL}
            WHERE co.is_deleted = 0
            ORDER BY co.updated_at DESC
            LIMIT {$start}, {$limit}");

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getActiveObjectsLeanCount($schema, $selectedFilters)
    {
        $relationSchema = "cms_rel_" . $schema . "_has_cms_obj_tags";
        $searchKeywords = $selectedFilters['search_keywords'] ?? '';
        unset($selectedFilters['search_keywords']);

        $joinSQL = "";
        if (!empty($selectedFilters) && Schema::hasTable($relationSchema)) {
            $joinIndex = 0;
            foreach(array_values($selectedFilters) as $tagId) {
                $joinIndex++;
                $joinSQL .= "JOIN " . $relationSchema . " ot{$joinIndex} ON ot{$joinIndex}.from_id = co.id
                    AND ot{$joinIndex}.to_id = {$tagId} AND ot{$joinIndex}.is_deleted = 0\n";
            }
        }

        /*$searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            $searchJoinSQL = "JOIN (SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.meta1_id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN {$schema} co1 ON co1.meta2_id = cos1.id
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.meta1_id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
				UNION
				SELECT co1.id
				FROM cms_objects cos1
				JOIN cms_relations cr1 ON cr1.attr2int = cos1.id
				JOIN {$schema} co1 ON co1.meta2_id = cr1.attr1int
				WHERE MATCH(cos1.name, cos1.description, cos1.attr1str, cos1.attr2str, cos1.attr1text) AGAINST('{$searchKeywords}')
			) cos1 ON cos1.id = co.id";
        }*/

        $searchAndSQL = "";
        $searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            /*$searchAndSQL = "SELECT cos.id
                    FROM cms_objects cos
                    WHERE MATCH(cos.name, cos.description, cos.attr1str, cos.attr2str, cos.attr1text) AGAINST('{$searchKeywords}')
                    AND (EXISTS(SELECT co.id
                        FROM {$schema} co
                        WHERE co.id = cos.id
                        AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                        FROM {$schema} co
                        WHERE co.meta1_id = cos.id
                        AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                        FROM {$schema} co
                        WHERE co.meta2_id = cos.id
                        AND co.is_deleted = 0
                        ) OR EXISTS(SELECT co.id
                        FROM {$schema} co
                        JOIN cms_relations cr ON cr.attr1int = co.id
                        JOIN cms_objects cos2 ON cos2.id = cr.attr2int
                        WHERE cos2.id = cos.id
                        AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                        FROM {$schema} co
                        JOIN cms_relations cr ON cr.attr1int = co.id
                        JOIN cms_objects cos2 ON cos2.id = co.meta1_id
                        WHERE cos2.id = cos.id
                        AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                        FROM {$schema} co
                        JOIN cms_relations cr ON cr.attr1int = co.id
                        JOIN cms_objects cos2 ON cos2.id = co.meta2_id
                        WHERE cos2.id = cos.id
                        AND co.is_deleted = 0
                        )
                    )";*/
            //$searchAndSQL = "AND FIND_IN_SET(co.id, @cms_search_ids)";
            $searchJoinSQL = "JOIN {$this->tmpTableName} ts ON ts.id = co.id";
        }

        $result = DB::select("SELECT COUNT(DISTINCT co.id) AS num_objects
            FROM {$schema} co
            JOIN users u ON co.updated_by = u.id
            {$joinSQL}
            {$searchJoinSQL}
            WHERE co.is_deleted = 0");

        if (!empty($result[0]->num_objects)) {
            return $result[0]->num_objects;
        }

        return 0;
    }

    public function getDeletedObjectsLean($schema, $selectedFilters, $start, $limit)
    {
        $relationSchema = "cms_rel_" . $schema . "_has_cms_obj_tags";
        $searchKeywords = $selectedFilters['search_keywords'] ?? '';
        unset($selectedFilters['search_keywords']);

        $joinSQL = "";
        if (!empty($selectedFilters) && Schema::hasTable($relationSchema)) {
            $joinIndex = 0;
            foreach(array_values($selectedFilters) as $tagId) {
                $joinIndex++;
                $joinSQL .= "JOIN " . $relationSchema . " ot{$joinIndex} ON ot{$joinIndex}.from_id = co.id
                    AND ot{$joinIndex}.to_id = {$tagId} AND ot{$joinIndex}.is_deleted = 0\n";
            }
        }

        $searchAndSQL = "";
        $searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            $this->tmpTableName = "tmp_searches_" . uniqid();
            DB::statement("SET SESSION group_concat_max_len = 1000000000");
            DB::statement("SET @cms_search_ids := (SELECT COALESCE(CONCAT_WS(','
                            , COALESCE(GROUP_CONCAT(DISTINCT co1.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co2.id), 0)
                            , COALESCE(GROUP_CONCAT(DISTINCT co3.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co4.id), 0)
                            , COALESCE(GROUP_CONCAT(DISTINCT co5.id), 0), COALESCE(GROUP_CONCAT(DISTINCT co6.id), 0)), 0) AS cms_search_ids
                FROM(SELECT cos.id
                    FROM cms_objects cos
                    WHERE MATCH(cos.name, cos.description, cos.attr1str, cos.attr2str, cos.attr1text) AGAINST('{$searchKeywords}')
                    AND (EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.meta1_id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            WHERE co.meta2_id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = cr.attr2int
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = co.meta1_id
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                        OR EXISTS(SELECT co.id
                            FROM {$schema} co
                            {$joinSQL}
                            JOIN cms_relations cr ON cr.attr1int = co.id
                            JOIN cms_objects cos2 ON cos2.id = co.meta2_id
                            WHERE cos2.id = cos.id
                            AND co.is_deleted = 0
                        )
                    )
                    LIMIT 100
                ) AS cos
                LEFT JOIN {$schema} co1 ON co1.id = cos.id AND co1.is_deleted = 0
                LEFT JOIN {$schema} co2 ON co2.meta1_id = cos.id AND co2.is_deleted = 0
                LEFT JOIN {$schema} co3 ON co3.meta2_id = cos.id AND co3.is_deleted = 0
                LEFT JOIN cms_relations cr ON cr.attr2int = cos.id
                LEFT JOIN {$schema} co4 ON co4.id = cr.attr1int AND co4.is_deleted = 0
                LEFT JOIN {$schema} co5 ON co5.meta1_id = cos.id AND co5.id = co4.id AND co5.is_deleted = 0
                LEFT JOIN {$schema} co6 ON co6.meta1_id = cos.id AND co6.id = co4.id AND co6.is_deleted = 0
                )");
            DB::statement("CREATE TEMPORARY TABLE {$this->tmpTableName} (id INT(10), PRIMARY KEY(id)) AS
                SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(@cms_search_ids, ',', n.n), ',', -1) AS id
                FROM(
                  SELECT a.N + b.N * 10 + 1 n
                  FROM 
                    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
					,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
					ORDER BY n
                ) n
                WHERE n.n <= 1 + (LENGTH(@cms_search_ids) - LENGTH(REPLACE(@cms_search_ids, ',', '')))
                GROUP BY id");
            $searchJoinSQL = "JOIN {$this->tmpTableName} ts ON ts.id = co.id";
        }

        $result = DB::select("SELECT co.id
              , co.name
              , co.slug
              , co.revision AS rev
              , u.email AS updated_by
              , co.updated_at
            FROM {$schema} co
            JOIN users u ON co.updated_by = u.id
            {$joinSQL}
            {$searchJoinSQL}
            WHERE co.is_deleted = 1
            ORDER BY co.updated_at DESC
            LIMIT {$start}, {$limit}");

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getDeletedObjectsLeanCount($schema, $selectedFilters)
    {
        $relationSchema = "cms_rel_" . $schema . "_has_cms_obj_tags";
        $searchKeywords = $selectedFilters['search_keywords'] ?? '';
        unset($selectedFilters['search_keywords']);

        $joinSQL = "";
        if (!empty($selectedFilters) && Schema::hasTable($relationSchema)) {
            $joinIndex = 0;
            foreach(array_values($selectedFilters) as $tagId) {
                $joinIndex++;
                $joinSQL .= "JOIN " . $relationSchema . " ot{$joinIndex} ON ot{$joinIndex}.from_id = co.id
                    AND ot{$joinIndex}.to_id = {$tagId} AND ot{$joinIndex}.is_deleted = 0\n";
            }
        }

        $searchAndSQL = "";
        $searchJoinSQL = "";
        if (!empty($searchKeywords)) {
            $searchJoinSQL = "JOIN {$this->tmpTableName} ts ON ts.id = co.id";
        }

        $result = DB::select("SELECT COUNT(DISTINCT co.id) AS num_objects
            FROM {$schema} co
            JOIN users u ON co.updated_by = u.id
            {$joinSQL}
            {$searchJoinSQL}
            WHERE co.is_deleted = 1");

        if (!empty($result[0]->num_objects)) {
            return $result[0]->num_objects;
        }

        return 0;
    }

    public function getTagsInTagGroup($tagGroupId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
            FROM cms_obj_tags t
            WHERE t.is_deleted = 0
            AND t.tag_group_id = :tag_group_id
            ORDER BY t.name ASC", ['tag_group_id' => $tagGroupId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsInTagGroupByName($tagGroup)
    {
        if ($tagGroup == 'SAT') {
            $tagGroups = "(tg.name LIKE 'Sites and Apps%' OR tg.name LIKE 'Topics%')";
        }
        else {
            $tagGroups = "tg.name LIKE '" . $tagGroup . "%'";
        }

        $result = DB::select("SELECT t.id
              , t.name AS tag
            FROM cms_obj_tags t
            JOIN cms_obj_tag_groups tg ON tg.id = t.tag_group_id
            WHERE t.is_deleted = 0
            AND tg.is_deleted = 0
            AND {$tagGroups}
            ORDER BY t.name ASC");

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForLesson($lessonId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_lessons_has_cms_obj_tags lt ON lt.to_id = t.id AND lt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND lt.from_id = :lesson_id
            ORDER BY lt.item_order ASC", ['lesson_id' => $lessonId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForQuiz($quizId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_quizzes_has_cms_obj_tags lt ON lt.to_id = t.id AND lt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND lt.from_id = :quiz_id
            ORDER BY lt.item_order ASC", ['quiz_id' => $quizId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForTagGroup($tagGroupId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_tag_groups_has_cms_obj_tags lt ON lt.to_id = t.id AND lt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND lt.from_id = :tag_group_id
            ORDER BY lt.item_order ASC", ['tag_group_id' => $tagGroupId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getQuizQuestions($quizId)
    {
        $result = DB::select("SELECT q.id
              , q.description
            FROM cms_rel_cms_obj_quizzes_has_cms_obj_questions qq
            JOIN cms_obj_questions q ON q.id = qq.to_id AND qq.is_deleted = 0
            WHERE q.is_deleted = 0
            AND qq.from_id = :quiz_id
            ORDER BY qq.item_order ASC", ['quiz_id' => $quizId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getQuestionAnswers($questionId)
    {
        $result = DB::select("SELECT a.id
              , a.description
            FROM cms_rel_cms_obj_questions_has_cms_obj_answers qa
            JOIN cms_obj_answers a ON a.id = qa.to_id AND qa.is_deleted = 0
            WHERE a.is_deleted = 0
            AND qa.from_id = :question_id
            ORDER BY qa.item_order ASC", ['question_id' => $questionId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTrackLessons($trackId)
    {
        $result = DB::select("SELECT l.id
              , l.name
              , l.description
            FROM cms_rel_cms_obj_tracks_has_cms_obj_lessons tl
            JOIN cms_obj_lessons l ON l.id = tl.to_id AND tl.is_deleted = 0
            WHERE l.is_deleted = 0
            AND tl.from_id = :track_id
            ORDER BY tl.item_order ASC", ['track_id' => $trackId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForTrack($trackId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_tracks_has_cms_obj_tags tt ON tt.to_id = t.id AND tt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND tt.from_id = :track_id
            ORDER BY tt.item_order ASC", ['track_id' => $trackId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getPrimaryTagForLesson($lessonId)
    {
        $result = DB::select("SELECT t.id
            FROM cms_rel_cms_obj_lessons_has_cms_obj_tags lt
            JOIN cms_obj_tags t ON t.id = lt.to_id AND t.is_deleted = 0 AND lt.is_deleted = 0
            JOIN cms_obj_tag_groups tg ON tg.id = t.tag_group_id AND tg.is_deleted = 0
            WHERE lt.from_id = :lesson_id
            AND (tg.name LIKE 'Sites and Apps%' OR tg.name LIKE 'Topics%')", ['lesson_id' => $lessonId]);

        if (!empty($result[0]->id)) {
            return $result[0]->id;
        }

        return null;
    }

    public function getPrimaryTagGroupForTag($tagId)
    {
        $result = DB::select("SELECT t.tag_group_id
            FROM cms_obj_tags t
            WHERE t.is_deleted = 0
            AND t.id = :tag_id", ['tag_id' => $tagId]);

        if (!empty($result[0]->tag_group_id)) {
            return $result[0]->tag_group_id;
        }

        return null;
    }

    public function getPrimaryTagForTrack($trackId)
    {
        $result = DB::select("SELECT t.id
            FROM cms_rel_cms_obj_tracks_has_cms_obj_tags tt
            JOIN cms_obj_tags t ON t.id = tt.to_id AND t.is_deleted = 0 AND tt.is_deleted = 0
            JOIN cms_obj_tag_groups tg ON tg.id = t.tag_group_id AND tg.is_deleted = 0
            WHERE tt.from_id = :track_id
            AND (tg.name LIKE 'Sites and Apps%' OR tg.name LIKE 'Topics%')", ['track_id' => $trackId]);

        if (!empty($result[0]->id)) {
            return $result[0]->id;
        }

        return null;
    }

    public function getPrimaryTagForQuiz($quizId)
    {
        $result = DB::select("SELECT t.id
            FROM cms_rel_cms_obj_quizzes_has_cms_obj_tags qt
            JOIN cms_obj_tags t ON t.id = qt.to_id AND t.is_deleted = 0 AND qt.is_deleted = 0
            JOIN cms_obj_tag_groups tg ON tg.id = t.tag_group_id AND tg.is_deleted = 0
            WHERE qt.from_id = :quiz_id
            AND (tg.name LIKE 'Sites and Apps%' OR tg.name LIKE 'Topics%')", ['quiz_id' => $quizId]);

        if (!empty($result[0]->id)) {
            return $result[0]->id;
        }

        return null;
    }

    public function getLessonsInTag($tagId)
    {
        $lessons = [];
        $result = DB::select("SELECT l.id
              , l.name
            FROM cms_obj_lessons l
            JOIN cms_rel_cms_obj_lessons_has_cms_obj_tags lt ON lt.to_id = :tag_id AND lt.is_deleted = 0
            WHERE l.is_deleted = 0
            AND lt.from_id = l.id
            ORDER BY l.name ASC", ['tag_id' => $tagId]);

        if (!empty($result) && is_array($result)) {
            foreach ($result as $res) {
                $lessons[$res->id] = $res;
            }
        }

        return $lessons;
    }

    public function getAssignmentTracks($assignmentId)
    {
        $result = DB::select("SELECT t.id
              , t.name
              , t.description
            FROM cms_rel_cms_obj_assignments_has_cms_obj_tracks at
            JOIN cms_obj_tracks t ON t.id = at.to_id AND at.is_deleted = 0
            WHERE t.is_deleted = 0
            AND at.from_id = :assignment_id
            ORDER BY at.item_order ASC", ['assignment_id' => $assignmentId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForAssignment($assignmentId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_assignments_has_cms_obj_tags at ON at.to_id = t.id AND at.is_deleted = 0
            WHERE t.is_deleted = 0
            AND at.from_id = :assignment_id
            ORDER BY at.item_order ASC", ['assignment_id' => $assignmentId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTracksInTag($tagId)
    {
        $tracks = [];
        $result = DB::select("SELECT t.id
              , t.name
            FROM cms_obj_tracks t
            JOIN cms_rel_cms_obj_tracks_has_cms_obj_tags tt ON tt.to_id = :tag_id AND tt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND tt.from_id = t.id
            ORDER BY t.name ASC", ['tag_id' => $tagId]);

        if (!empty($result) && is_array($result)) {
            foreach ($result as $res) {
                $tracks[$res->id] = $res;
            }
        }

        return $tracks;
    }

    public function getTestQuizzes($testId)
    {
        $result = DB::select("SELECT q.id
              , q.name
              , q.description
            FROM cms_rel_cms_obj_tests_has_cms_obj_quizzes tq
            JOIN cms_obj_quizzes q ON q.id = tq.to_id AND tq.is_deleted = 0
            WHERE q.is_deleted = 0
            AND tq.from_id = :test_id
            ORDER BY tq.item_order ASC", ['test_id' => $testId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getLessonQuizzes($lessonId)
    {
        $result = DB::select("SELECT q.id
              , q.name
              , q.description
            FROM cms_rel_cms_obj_lessons_has_cms_obj_quizzes lq
            JOIN cms_obj_quizzes q ON q.id = lq.to_id AND lq.is_deleted = 0
            WHERE q.is_deleted = 0
            AND lq.from_id = :lesson_id
            ORDER BY lq.item_order ASC", ['lesson_id' => $lessonId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getTagsForTest($testId)
    {
        $result = DB::select("SELECT t.id
              , t.name AS tag
              , t.tag_group_id
            FROM cms_obj_tags t
            JOIN cms_rel_cms_obj_tests_has_cms_obj_tags tt ON tt.to_id = t.id AND tt.is_deleted = 0
            WHERE t.is_deleted = 0
            AND tt.from_id = :test_id
            ORDER BY tt.item_order ASC", ['test_id' => $testId]);

        if (!empty($result) && is_array($result)) {
            return $result;
        }

        return [];
    }

    public function getQuizzesInTag($tagId)
    {
        $quizzes = [];
        $result = DB::select("SELECT q.id
              , q.name
            FROM cms_obj_quizzes q
            JOIN cms_rel_cms_obj_quizzes_has_cms_obj_tags qt ON qt.to_id = :tag_id AND qt.is_deleted = 0
            WHERE q.is_deleted = 0
            AND qt.from_id = q.id
            ORDER BY q.name ASC", ['tag_id' => $tagId]);

        if (!empty($result) && is_array($result)) {
            foreach ($result as $res) {
                $quizzes[$res->id] = $res;
            }
        }

        return $quizzes;
    }
}
