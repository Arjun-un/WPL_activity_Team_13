<?php
/**
 * ============================================================================
 * Database Configuration & MongoDB Helper Functions
 * Connects directly to MongoDB Atlas cluster using PHP's native MongoDB driver.
 * ============================================================================
 */

// ============================================================================
// ON-SPOT MODIFICATION SPOT #1: DATABASE CONNECTION STRING & DATABASE NAME
// If the evaluator asks to connect to a local MongoDB or change database name,
// change $MONGO_URI or $DB_NAME below.
// ============================================================================
$MONGO_URI = "mongodb+srv://arjun:gxQKpEPUg7D31ewe@cluster0.92vxnio.mongodb.net/?retryWrites=true&w=majority";
$DB_NAME   = "tatacliq_db";

try {
    $GLOBALS['mongoManager'] = new MongoDB\Driver\Manager($MONGO_URI);
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}

/**
 * Execute a query on a collection
 * @param string $collection Collection name (e.g. 'products', 'users', 'orders')
 * @param array $filter Filter criteria, e.g. ['category' => 'Electronics']
 * @param array $options Sort, limit, projection, e.g. ['sort' => ['price' => 1]]
 * @return array Array of documents as associative arrays
 */
function get_db_records($collection, $filter = [], $options = []) {
    global $DB_NAME;
    try {
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $GLOBALS['mongoManager']->executeQuery("$DB_NAME.$collection", $query);
        $results = [];
        foreach ($cursor as $doc) {
            $array = json_decode(json_encode($doc), true);
            // Normalize _id to string for easy reference in HTML links
            if (isset($doc->_id) && is_object($doc->_id)) {
                $array['_id'] = (string)$doc->_id;
            }
            $results[] = $array;
        }
        return $results;
    } catch (Exception $e) {
        error_log("MongoDB Query Error: " . $e->getMessage());
        return [];
    }
}

/**
 * Fetch a single document from a collection
 */
function get_single_record($collection, $filter = [], $options = []) {
    $options['limit'] = 1;
    $results = get_db_records($collection, $filter, $options);
    return !empty($results) ? $results[0] : null;
}

/**
 * Insert a new document into a collection
 * @return string Inserted ID (string)
 */
function insert_db_record($collection, $document) {
    global $DB_NAME;
    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $id = $bulk->insert($document);
        $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.$collection", $bulk);
        return (string)$id;
    } catch (Exception $e) {
        error_log("MongoDB Insert Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update document(s) matching the filter
 * @param array $filter E.g. ['_id' => new MongoDB\BSON\ObjectId($id)]
 * @param array $update E.g. ['$set' => ['stock' => 10]]
 */
function update_db_record($collection, $filter, $update) {
    global $DB_NAME;
    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update($filter, $update, ['multi' => false, 'upsert' => false]);
        $result = $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.$collection", $bulk);
        return $result->getModifiedCount() > 0;
    } catch (Exception $e) {
        error_log("MongoDB Update Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete document(s) matching the filter
 */
function delete_db_record($collection, $filter) {
    global $DB_NAME;
    try {
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->delete($filter, ['limit' => 1]);
        $result = $GLOBALS['mongoManager']->executeBulkWrite("$DB_NAME.$collection", $bulk);
        return $result->getDeletedCount() > 0;
    } catch (Exception $e) {
        error_log("MongoDB Delete Error: " . $e->getMessage());
        return false;
    }
}
?>
