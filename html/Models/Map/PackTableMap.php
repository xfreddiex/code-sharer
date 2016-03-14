<?php

namespace Models\Map;

use Models\Pack;
use Models\PackQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'pack' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PackTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.PackTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'pack';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\Pack';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.Pack';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'pack.id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'pack.name';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'pack.description';

    /**
     * the column name for the private field
     */
    const COL_PRIVATE = 'pack.private';

    /**
     * the column name for the owner_id field
     */
    const COL_OWNER_ID = 'pack.owner_id';

    /**
     * the column name for the deleted_at field
     */
    const COL_DELETED_AT = 'pack.deleted_at';

    /**
     * the column name for the tags field
     */
    const COL_TAGS = 'pack.tags';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'pack.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'pack.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Name', 'Description', 'Private', 'OwnerId', 'DeletedAt', 'Tags', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'name', 'description', 'private', 'ownerId', 'deletedAt', 'tags', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PackTableMap::COL_ID, PackTableMap::COL_NAME, PackTableMap::COL_DESCRIPTION, PackTableMap::COL_PRIVATE, PackTableMap::COL_OWNER_ID, PackTableMap::COL_DELETED_AT, PackTableMap::COL_TAGS, PackTableMap::COL_CREATED_AT, PackTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'name', 'description', 'private', 'owner_id', 'deleted_at', 'tags', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Name' => 1, 'Description' => 2, 'Private' => 3, 'OwnerId' => 4, 'DeletedAt' => 5, 'Tags' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'private' => 3, 'ownerId' => 4, 'deletedAt' => 5, 'tags' => 6, 'createdAt' => 7, 'updatedAt' => 8, ),
        self::TYPE_COLNAME       => array(PackTableMap::COL_ID => 0, PackTableMap::COL_NAME => 1, PackTableMap::COL_DESCRIPTION => 2, PackTableMap::COL_PRIVATE => 3, PackTableMap::COL_OWNER_ID => 4, PackTableMap::COL_DELETED_AT => 5, PackTableMap::COL_TAGS => 6, PackTableMap::COL_CREATED_AT => 7, PackTableMap::COL_UPDATED_AT => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'name' => 1, 'description' => 2, 'private' => 3, 'owner_id' => 4, 'deleted_at' => 5, 'tags' => 6, 'created_at' => 7, 'updated_at' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('pack');
        $this->setPhpName('Pack');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\Pack');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 32, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 256, null);
        $this->addColumn('private', 'Private', 'BOOLEAN', true, 1, null);
        $this->addForeignKey('owner_id', 'OwnerId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addColumn('deleted_at', 'DeletedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('tags', 'Tags', 'ARRAY', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Owner', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':owner_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('PackPermission', '\\Models\\PackPermission', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':pack_id',
    1 => ':id',
  ),
), null, null, 'PackPermissions', false);
        $this->addRelation('File', '\\Models\\File', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':pack_id',
    1 => ':id',
  ),
), null, null, 'Files', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
            'validate' => array('rule1' => array ('column' => 'name','validator' => 'Length','options' => array ('max' => 32,'maxMessage' => 'Maximal pack name length is {{ limit }} characters.',),), 'rule2' => array ('column' => 'name','validator' => 'Regex','options' => array ('pattern' => '/^[^\\s]*$/','match' => true,'message' => 'Pack name should not contain whitespaces.',),), 'rule3' => array ('column' => 'name','validator' => 'NotBlank','options' => array ('message' => 'Pack name should not be blank.',),), 'rule4' => array ('column' => 'description','validator' => 'Length','options' => array ('max' => 256,'maxMessage' => 'Maximal pack description length is {{ limit }} characters.',),), ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? PackTableMap::CLASS_DEFAULT : PackTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Pack object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PackTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PackTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PackTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PackTableMap::OM_CLASS;
            /** @var Pack $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PackTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PackTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PackTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Pack $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PackTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PackTableMap::COL_ID);
            $criteria->addSelectColumn(PackTableMap::COL_NAME);
            $criteria->addSelectColumn(PackTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(PackTableMap::COL_PRIVATE);
            $criteria->addSelectColumn(PackTableMap::COL_OWNER_ID);
            $criteria->addSelectColumn(PackTableMap::COL_DELETED_AT);
            $criteria->addSelectColumn(PackTableMap::COL_TAGS);
            $criteria->addSelectColumn(PackTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PackTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.private');
            $criteria->addSelectColumn($alias . '.owner_id');
            $criteria->addSelectColumn($alias . '.deleted_at');
            $criteria->addSelectColumn($alias . '.tags');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PackTableMap::DATABASE_NAME)->getTable(PackTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PackTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PackTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PackTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Pack or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Pack object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\Pack) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PackTableMap::DATABASE_NAME);
            $criteria->add(PackTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PackQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PackTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PackTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the pack table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PackQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Pack or Criteria object.
     *
     * @param mixed               $criteria Criteria or Pack object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Pack object
        }

        if ($criteria->containsKey(PackTableMap::COL_ID) && $criteria->keyContainsValue(PackTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PackTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PackQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PackTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PackTableMap::buildTableMap();
