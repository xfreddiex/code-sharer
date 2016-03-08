<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\PackPermission as ChildPackPermission;
use Models\PackPermissionQuery as ChildPackPermissionQuery;
use Models\Map\PackPermissionTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pack_permission' table.
 *
 *
 *
 * @method     ChildPackPermissionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPackPermissionQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildPackPermissionQuery orderByBelongerId($order = Criteria::ASC) Order by the belonger_id column
 * @method     ChildPackPermissionQuery orderByBelongerType($order = Criteria::ASC) Order by the belonger_type column
 * @method     ChildPackPermissionQuery orderByPackId($order = Criteria::ASC) Order by the pack_id column
 * @method     ChildPackPermissionQuery orderByDeletedAt($order = Criteria::ASC) Order by the deleted_at column
 * @method     ChildPackPermissionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPackPermissionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPackPermissionQuery groupById() Group by the id column
 * @method     ChildPackPermissionQuery groupByType() Group by the type column
 * @method     ChildPackPermissionQuery groupByBelongerId() Group by the belonger_id column
 * @method     ChildPackPermissionQuery groupByBelongerType() Group by the belonger_type column
 * @method     ChildPackPermissionQuery groupByPackId() Group by the pack_id column
 * @method     ChildPackPermissionQuery groupByDeletedAt() Group by the deleted_at column
 * @method     ChildPackPermissionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPackPermissionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPackPermissionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPackPermissionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPackPermissionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPackPermissionQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPackPermissionQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPackPermissionQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPackPermissionQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildPackPermissionQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildPackPermissionQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildPackPermissionQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildPackPermissionQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildPackPermissionQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildPackPermissionQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildPackPermissionQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildPackPermissionQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildPackPermissionQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     ChildPackPermissionQuery joinWithGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Group relation
 *
 * @method     ChildPackPermissionQuery leftJoinWithGroup() Adds a LEFT JOIN clause and with to the query using the Group relation
 * @method     ChildPackPermissionQuery rightJoinWithGroup() Adds a RIGHT JOIN clause and with to the query using the Group relation
 * @method     ChildPackPermissionQuery innerJoinWithGroup() Adds a INNER JOIN clause and with to the query using the Group relation
 *
 * @method     ChildPackPermissionQuery leftJoinPack($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pack relation
 * @method     ChildPackPermissionQuery rightJoinPack($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pack relation
 * @method     ChildPackPermissionQuery innerJoinPack($relationAlias = null) Adds a INNER JOIN clause to the query using the Pack relation
 *
 * @method     ChildPackPermissionQuery joinWithPack($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Pack relation
 *
 * @method     ChildPackPermissionQuery leftJoinWithPack() Adds a LEFT JOIN clause and with to the query using the Pack relation
 * @method     ChildPackPermissionQuery rightJoinWithPack() Adds a RIGHT JOIN clause and with to the query using the Pack relation
 * @method     ChildPackPermissionQuery innerJoinWithPack() Adds a INNER JOIN clause and with to the query using the Pack relation
 *
 * @method     \Models\UserQuery|\Models\GroupQuery|\Models\PackQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPackPermission findOne(ConnectionInterface $con = null) Return the first ChildPackPermission matching the query
 * @method     ChildPackPermission findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPackPermission matching the query, or a new ChildPackPermission object populated from the query conditions when no match is found
 *
 * @method     ChildPackPermission findOneById(int $id) Return the first ChildPackPermission filtered by the id column
 * @method     ChildPackPermission findOneByType(int $type) Return the first ChildPackPermission filtered by the type column
 * @method     ChildPackPermission findOneByBelongerId(int $belonger_id) Return the first ChildPackPermission filtered by the belonger_id column
 * @method     ChildPackPermission findOneByBelongerType(int $belonger_type) Return the first ChildPackPermission filtered by the belonger_type column
 * @method     ChildPackPermission findOneByPackId(int $pack_id) Return the first ChildPackPermission filtered by the pack_id column
 * @method     ChildPackPermission findOneByDeletedAt(string $deleted_at) Return the first ChildPackPermission filtered by the deleted_at column
 * @method     ChildPackPermission findOneByCreatedAt(string $created_at) Return the first ChildPackPermission filtered by the created_at column
 * @method     ChildPackPermission findOneByUpdatedAt(string $updated_at) Return the first ChildPackPermission filtered by the updated_at column *

 * @method     ChildPackPermission requirePk($key, ConnectionInterface $con = null) Return the ChildPackPermission by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOne(ConnectionInterface $con = null) Return the first ChildPackPermission matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPackPermission requireOneById(int $id) Return the first ChildPackPermission filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByType(int $type) Return the first ChildPackPermission filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByBelongerId(int $belonger_id) Return the first ChildPackPermission filtered by the belonger_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByBelongerType(int $belonger_type) Return the first ChildPackPermission filtered by the belonger_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByPackId(int $pack_id) Return the first ChildPackPermission filtered by the pack_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByDeletedAt(string $deleted_at) Return the first ChildPackPermission filtered by the deleted_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByCreatedAt(string $created_at) Return the first ChildPackPermission filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPackPermission requireOneByUpdatedAt(string $updated_at) Return the first ChildPackPermission filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPackPermission[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPackPermission objects based on current ModelCriteria
 * @method     ChildPackPermission[]|ObjectCollection findById(int $id) Return ChildPackPermission objects filtered by the id column
 * @method     ChildPackPermission[]|ObjectCollection findByType(int $type) Return ChildPackPermission objects filtered by the type column
 * @method     ChildPackPermission[]|ObjectCollection findByBelongerId(int $belonger_id) Return ChildPackPermission objects filtered by the belonger_id column
 * @method     ChildPackPermission[]|ObjectCollection findByBelongerType(int $belonger_type) Return ChildPackPermission objects filtered by the belonger_type column
 * @method     ChildPackPermission[]|ObjectCollection findByPackId(int $pack_id) Return ChildPackPermission objects filtered by the pack_id column
 * @method     ChildPackPermission[]|ObjectCollection findByDeletedAt(string $deleted_at) Return ChildPackPermission objects filtered by the deleted_at column
 * @method     ChildPackPermission[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPackPermission objects filtered by the created_at column
 * @method     ChildPackPermission[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPackPermission objects filtered by the updated_at column
 * @method     ChildPackPermission[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PackPermissionQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\PackPermissionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\PackPermission', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPackPermissionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPackPermissionQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPackPermissionQuery) {
            return $criteria;
        }
        $query = new ChildPackPermissionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPackPermission|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PackPermissionTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PackPermissionTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPackPermission A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, type, belonger_id, belonger_type, pack_id, deleted_at, created_at, updated_at FROM pack_permission WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPackPermission $obj */
            $obj = new ChildPackPermission();
            $obj->hydrate($row);
            PackPermissionTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPackPermission|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PackPermissionTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PackPermissionTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * @param     mixed $type The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        $valueSet = PackPermissionTableMap::getValueSet(PackPermissionTableMap::COL_TYPE);
        if (is_scalar($type)) {
            if (!in_array($type, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $type));
            }
            $type = array_search($type, $valueSet);
        } elseif (is_array($type)) {
            $convertedValues = array();
            foreach ($type as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $type = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the belonger_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBelongerId(1234); // WHERE belonger_id = 1234
     * $query->filterByBelongerId(array(12, 34)); // WHERE belonger_id IN (12, 34)
     * $query->filterByBelongerId(array('min' => 12)); // WHERE belonger_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @see       filterByGroup()
     *
     * @param     mixed $belongerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByBelongerId($belongerId = null, $comparison = null)
    {
        if (is_array($belongerId)) {
            $useMinMax = false;
            if (isset($belongerId['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $belongerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($belongerId['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $belongerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $belongerId, $comparison);
    }

    /**
     * Filter the query on the belonger_type column
     *
     * @param     mixed $belongerType The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByBelongerType($belongerType = null, $comparison = null)
    {
        $valueSet = PackPermissionTableMap::getValueSet(PackPermissionTableMap::COL_BELONGER_TYPE);
        if (is_scalar($belongerType)) {
            if (!in_array($belongerType, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $belongerType));
            }
            $belongerType = array_search($belongerType, $valueSet);
        } elseif (is_array($belongerType)) {
            $convertedValues = array();
            foreach ($belongerType as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $belongerType = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_BELONGER_TYPE, $belongerType, $comparison);
    }

    /**
     * Filter the query on the pack_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPackId(1234); // WHERE pack_id = 1234
     * $query->filterByPackId(array(12, 34)); // WHERE pack_id IN (12, 34)
     * $query->filterByPackId(array('min' => 12)); // WHERE pack_id > 12
     * </code>
     *
     * @see       filterByPack()
     *
     * @param     mixed $packId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByPackId($packId = null, $comparison = null)
    {
        if (is_array($packId)) {
            $useMinMax = false;
            if (isset($packId['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_PACK_ID, $packId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($packId['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_PACK_ID, $packId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_PACK_ID, $packId, $comparison);
    }

    /**
     * Filter the query on the deleted_at column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE deleted_at = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE deleted_at = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE deleted_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_DELETED_AT, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_DELETED_AT, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_DELETED_AT, $deletedAt, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PackPermissionTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackPermissionTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\Group object
     *
     * @param \Models\Group|ObjectCollection $group The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = null)
    {
        if ($group instanceof \Models\Group) {
            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_BELONGER_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByGroup() only accepts arguments of type \Models\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Group relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function joinGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Group');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Group');
        }

        return $this;
    }

    /**
     * Use the Group relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupQuery A secondary query class using the current class as primary query
     */
    public function useGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Group', '\Models\GroupQuery');
    }

    /**
     * Filter the query by a related \Models\Pack object
     *
     * @param \Models\Pack|ObjectCollection $pack The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPackPermissionQuery The current query, for fluid interface
     */
    public function filterByPack($pack, $comparison = null)
    {
        if ($pack instanceof \Models\Pack) {
            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_PACK_ID, $pack->getId(), $comparison);
        } elseif ($pack instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PackPermissionTableMap::COL_PACK_ID, $pack->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPack() only accepts arguments of type \Models\Pack or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Pack relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function joinPack($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Pack');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Pack');
        }

        return $this;
    }

    /**
     * Use the Pack relation Pack object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\PackQuery A secondary query class using the current class as primary query
     */
    public function usePackQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPack($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Pack', '\Models\PackQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPackPermission $packPermission Object to remove from the list of results
     *
     * @return $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function prune($packPermission = null)
    {
        if ($packPermission) {
            $this->addUsingAlias(PackPermissionTableMap::COL_ID, $packPermission->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pack_permission table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackPermissionTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PackPermissionTableMap::clearInstancePool();
            PackPermissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackPermissionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PackPermissionTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PackPermissionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PackPermissionTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PackPermissionTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PackPermissionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PackPermissionTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PackPermissionTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PackPermissionTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPackPermissionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PackPermissionTableMap::COL_CREATED_AT);
    }

} // PackPermissionQuery
