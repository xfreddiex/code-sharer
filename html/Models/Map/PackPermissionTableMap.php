<?php

namespace Models\Map;

use Models\PackPermission;
use Models\PackPermissionQuery;
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
 * This class defines the structure of the 'pack_permission' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PackPermissionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.PackPermissionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'pack_permission';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\PackPermission';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.PackPermission';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the id field
     */
    const COL_ID = 'pack_permission.id';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'pack_permission.type';

    /**
     * the column name for the belonger_id field
     */
    const COL_BELONGER_ID = 'pack_permission.belonger_id';

    /**
     * the column name for the belonger_type field
     */
    const COL_BELONGER_TYPE = 'pack_permission.belonger_type';

    /**
     * the column name for the pack_id field
     */
    const COL_PACK_ID = 'pack_permission.pack_id';

    /**
     * the column name for the deleted_at field
     */
    const COL_DELETED_AT = 'pack_permission.deleted_at';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'pack_permission.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'pack_permission.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /** The enumerated values for the type field */
    const COL_TYPE_VIEW = 'view';
    const COL_TYPE_EDIT = 'edit';
    const COL_TYPE_REMOVE = 'remove';

    /** The enumerated values for the belonger_type field */
    const COL_BELONGER_TYPE_GROUP = 'group';
    const COL_BELONGER_TYPE_USER = 'user';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'Type', 'BelongerId', 'BelongerType', 'PackId', 'DeletedAt', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'type', 'belongerId', 'belongerType', 'packId', 'deletedAt', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(PackPermissionTableMap::COL_ID, PackPermissionTableMap::COL_TYPE, PackPermissionTableMap::COL_BELONGER_ID, PackPermissionTableMap::COL_BELONGER_TYPE, PackPermissionTableMap::COL_PACK_ID, PackPermissionTableMap::COL_DELETED_AT, PackPermissionTableMap::COL_CREATED_AT, PackPermissionTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'type', 'belonger_id', 'belonger_type', 'pack_id', 'deleted_at', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Type' => 1, 'BelongerId' => 2, 'BelongerType' => 3, 'PackId' => 4, 'DeletedAt' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'type' => 1, 'belongerId' => 2, 'belongerType' => 3, 'packId' => 4, 'deletedAt' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(PackPermissionTableMap::COL_ID => 0, PackPermissionTableMap::COL_TYPE => 1, PackPermissionTableMap::COL_BELONGER_ID => 2, PackPermissionTableMap::COL_BELONGER_TYPE => 3, PackPermissionTableMap::COL_PACK_ID => 4, PackPermissionTableMap::COL_DELETED_AT => 5, PackPermissionTableMap::COL_CREATED_AT => 6, PackPermissionTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'type' => 1, 'belonger_id' => 2, 'belonger_type' => 3, 'pack_id' => 4, 'deleted_at' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /** The enumerated values for this table */
    protected static $enumValueSets = array(
                PackPermissionTableMap::COL_TYPE => array(
                            self::COL_TYPE_VIEW,
            self::COL_TYPE_EDIT,
            self::COL_TYPE_REMOVE,
        ),
                PackPermissionTableMap::COL_BELONGER_TYPE => array(
                            self::COL_BELONGER_TYPE_GROUP,
            self::COL_BELONGER_TYPE_USER,
        ),
    );

    /**
     * Gets the list of values for all ENUM and SET columns
     * @return array
     */
    public static function getValueSets()
    {
      return static::$enumValueSets;
    }

    /**
     * Gets the list of values for an ENUM or SET column
     * @param string $colname
     * @return array list of possible values for the column
     */
    public static function getValueSet($colname)
    {
        $valueSets = self::getValueSets();

        return $valueSets[$colname];
    }

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
        $this->setName('pack_permission');
        $this->setPhpName('PackPermission');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\PackPermission');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('type', 'Type', 'ENUM', true, null, null);
        $this->getColumn('type')->setValueSet(array (
  0 => 'view',
  1 => 'edit',
  2 => 'remove',
));
        $this->addForeignKey('belonger_id', 'BelongerId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('belonger_id', 'BelongerId', 'INTEGER', 'group', 'id', true, null, null);
        $this->addColumn('belonger_type', 'BelongerType', 'ENUM', true, null, null);
        $this->getColumn('belonger_type')->setValueSet(array (
  0 => 'group',
  1 => 'user',
));
        $this->addForeignKey('pack_id', 'PackId', 'INTEGER', 'pack', 'id', true, null, null);
        $this->addColumn('deleted_at', 'DeletedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('User', '\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':belonger_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Group', '\\Models\\Group', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':belonger_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Pack', '\\Models\\Pack', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':pack_id',
    1 => ':id',
  ),
), null, null, null, false);
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
        return $withPrefix ? PackPermissionTableMap::CLASS_DEFAULT : PackPermissionTableMap::OM_CLASS;
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
     * @return array           (PackPermission object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PackPermissionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PackPermissionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PackPermissionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PackPermissionTableMap::OM_CLASS;
            /** @var PackPermission $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PackPermissionTableMap::addInstanceToPool($obj, $key);
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
            $key = PackPermissionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PackPermissionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var PackPermission $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PackPermissionTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(PackPermissionTableMap::COL_ID);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_TYPE);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_BELONGER_ID);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_BELONGER_TYPE);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_PACK_ID);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_DELETED_AT);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(PackPermissionTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.belonger_id');
            $criteria->addSelectColumn($alias . '.belonger_type');
            $criteria->addSelectColumn($alias . '.pack_id');
            $criteria->addSelectColumn($alias . '.deleted_at');
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
        return Propel::getServiceContainer()->getDatabaseMap(PackPermissionTableMap::DATABASE_NAME)->getTable(PackPermissionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(PackPermissionTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(PackPermissionTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new PackPermissionTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a PackPermission or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or PackPermission object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PackPermissionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\PackPermission) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PackPermissionTableMap::DATABASE_NAME);
            $criteria->add(PackPermissionTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = PackPermissionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            PackPermissionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                PackPermissionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the pack_permission table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PackPermissionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a PackPermission or Criteria object.
     *
     * @param mixed               $criteria Criteria or PackPermission object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackPermissionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PackPermission object
        }

        if ($criteria->containsKey(PackPermissionTableMap::COL_ID) && $criteria->keyContainsValue(PackPermissionTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PackPermissionTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = PackPermissionQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // PackPermissionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PackPermissionTableMap::buildTableMap();
