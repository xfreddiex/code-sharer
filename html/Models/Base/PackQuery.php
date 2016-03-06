<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Pack as ChildPack;
use Models\PackQuery as ChildPackQuery;
use Models\Map\PackTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'pack' table.
 *
 *
 *
 * @method     ChildPackQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPackQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildPackQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildPackQuery orderByPrivate($order = Criteria::ASC) Order by the private column
 * @method     ChildPackQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildPackQuery orderByDeletedAt($order = Criteria::ASC) Order by the deleted_at column
 * @method     ChildPackQuery orderByTags($order = Criteria::ASC) Order by the tags column
 * @method     ChildPackQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPackQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPackQuery groupById() Group by the id column
 * @method     ChildPackQuery groupByName() Group by the name column
 * @method     ChildPackQuery groupByDescription() Group by the description column
 * @method     ChildPackQuery groupByPrivate() Group by the private column
 * @method     ChildPackQuery groupByUserId() Group by the user_id column
 * @method     ChildPackQuery groupByDeletedAt() Group by the deleted_at column
 * @method     ChildPackQuery groupByTags() Group by the tags column
 * @method     ChildPackQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPackQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPackQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPackQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPackQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPackQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPackQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPackQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPackQuery leftJoinOwner($relationAlias = null) Adds a LEFT JOIN clause to the query using the Owner relation
 * @method     ChildPackQuery rightJoinOwner($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Owner relation
 * @method     ChildPackQuery innerJoinOwner($relationAlias = null) Adds a INNER JOIN clause to the query using the Owner relation
 *
 * @method     ChildPackQuery joinWithOwner($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Owner relation
 *
 * @method     ChildPackQuery leftJoinWithOwner() Adds a LEFT JOIN clause and with to the query using the Owner relation
 * @method     ChildPackQuery rightJoinWithOwner() Adds a RIGHT JOIN clause and with to the query using the Owner relation
 * @method     ChildPackQuery innerJoinWithOwner() Adds a INNER JOIN clause and with to the query using the Owner relation
 *
 * @method     ChildPackQuery leftJoinPackPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the PackPermission relation
 * @method     ChildPackQuery rightJoinPackPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PackPermission relation
 * @method     ChildPackQuery innerJoinPackPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the PackPermission relation
 *
 * @method     ChildPackQuery joinWithPackPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PackPermission relation
 *
 * @method     ChildPackQuery leftJoinWithPackPermission() Adds a LEFT JOIN clause and with to the query using the PackPermission relation
 * @method     ChildPackQuery rightJoinWithPackPermission() Adds a RIGHT JOIN clause and with to the query using the PackPermission relation
 * @method     ChildPackQuery innerJoinWithPackPermission() Adds a INNER JOIN clause and with to the query using the PackPermission relation
 *
 * @method     ChildPackQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method     ChildPackQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method     ChildPackQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method     ChildPackQuery joinWithFile($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the File relation
 *
 * @method     ChildPackQuery leftJoinWithFile() Adds a LEFT JOIN clause and with to the query using the File relation
 * @method     ChildPackQuery rightJoinWithFile() Adds a RIGHT JOIN clause and with to the query using the File relation
 * @method     ChildPackQuery innerJoinWithFile() Adds a INNER JOIN clause and with to the query using the File relation
 *
 * @method     \Models\UserQuery|\Models\PackPermissionQuery|\Models\FileQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPack findOne(ConnectionInterface $con = null) Return the first ChildPack matching the query
 * @method     ChildPack findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPack matching the query, or a new ChildPack object populated from the query conditions when no match is found
 *
 * @method     ChildPack findOneById(int $id) Return the first ChildPack filtered by the id column
 * @method     ChildPack findOneByName(string $name) Return the first ChildPack filtered by the name column
 * @method     ChildPack findOneByDescription(string $description) Return the first ChildPack filtered by the description column
 * @method     ChildPack findOneByPrivate(boolean $private) Return the first ChildPack filtered by the private column
 * @method     ChildPack findOneByUserId(int $user_id) Return the first ChildPack filtered by the user_id column
 * @method     ChildPack findOneByDeletedAt(string $deleted_at) Return the first ChildPack filtered by the deleted_at column
 * @method     ChildPack findOneByTags(array $tags) Return the first ChildPack filtered by the tags column
 * @method     ChildPack findOneByCreatedAt(string $created_at) Return the first ChildPack filtered by the created_at column
 * @method     ChildPack findOneByUpdatedAt(string $updated_at) Return the first ChildPack filtered by the updated_at column *

 * @method     ChildPack requirePk($key, ConnectionInterface $con = null) Return the ChildPack by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOne(ConnectionInterface $con = null) Return the first ChildPack matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPack requireOneById(int $id) Return the first ChildPack filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByName(string $name) Return the first ChildPack filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByDescription(string $description) Return the first ChildPack filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByPrivate(boolean $private) Return the first ChildPack filtered by the private column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByUserId(int $user_id) Return the first ChildPack filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByDeletedAt(string $deleted_at) Return the first ChildPack filtered by the deleted_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByTags(array $tags) Return the first ChildPack filtered by the tags column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByCreatedAt(string $created_at) Return the first ChildPack filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPack requireOneByUpdatedAt(string $updated_at) Return the first ChildPack filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPack[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPack objects based on current ModelCriteria
 * @method     ChildPack[]|ObjectCollection findById(int $id) Return ChildPack objects filtered by the id column
 * @method     ChildPack[]|ObjectCollection findByName(string $name) Return ChildPack objects filtered by the name column
 * @method     ChildPack[]|ObjectCollection findByDescription(string $description) Return ChildPack objects filtered by the description column
 * @method     ChildPack[]|ObjectCollection findByPrivate(boolean $private) Return ChildPack objects filtered by the private column
 * @method     ChildPack[]|ObjectCollection findByUserId(int $user_id) Return ChildPack objects filtered by the user_id column
 * @method     ChildPack[]|ObjectCollection findByDeletedAt(string $deleted_at) Return ChildPack objects filtered by the deleted_at column
 * @method     ChildPack[]|ObjectCollection findByTags(array $tags) Return ChildPack objects filtered by the tags column
 * @method     ChildPack[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPack objects filtered by the created_at column
 * @method     ChildPack[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildPack objects filtered by the updated_at column
 * @method     ChildPack[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PackQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\PackQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Pack', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPackQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPackQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPackQuery) {
            return $criteria;
        }
        $query = new ChildPackQuery();
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
     * @return ChildPack|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PackTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PackTableMap::DATABASE_NAME);
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
     * @return ChildPack A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, description, private, user_id, deleted_at, tags, created_at, updated_at FROM pack WHERE id = :p0';
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
            /** @var ChildPack $obj */
            $obj = new ChildPack();
            $obj->hydrate($row);
            PackTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPack|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PackTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PackTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PackTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PackTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the private column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivate(true); // WHERE private = true
     * $query->filterByPrivate('yes'); // WHERE private = true
     * </code>
     *
     * @param     boolean|string $private The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByPrivate($private = null, $comparison = null)
    {
        if (is_string($private)) {
            $private = in_array(strtolower($private), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PackTableMap::COL_PRIVATE, $private, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByOwner()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(PackTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(PackTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(PackTableMap::COL_DELETED_AT, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(PackTableMap::COL_DELETED_AT, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_DELETED_AT, $deletedAt, $comparison);
    }

    /**
     * Filter the query on the tags column
     *
     * @param     array $tags The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByTags($tags = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PackTableMap::COL_TAGS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($tags as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PackTableMap::COL_TAGS, $tags, $comparison);
    }

    /**
     * Filter the query on the tags column
     * @param     mixed $tags The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByTag($tags = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($tags)) {
                $tags = '%| ' . $tags . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $tags = '%| ' . $tags . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PackTableMap::COL_TAGS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $tags, $comparison);
            } else {
                $this->addAnd($key, $tags, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PackTableMap::COL_TAGS, $tags, $comparison);
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
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PackTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PackTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PackTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PackTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PackTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPackQuery The current query, for fluid interface
     */
    public function filterByOwner($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(PackTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PackTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOwner() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Owner relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function joinOwner($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Owner');

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
            $this->addJoinObject($join, 'Owner');
        }

        return $this;
    }

    /**
     * Use the Owner relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useOwnerQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinOwner($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Owner', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\PackPermission object
     *
     * @param \Models\PackPermission|ObjectCollection $packPermission the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPackQuery The current query, for fluid interface
     */
    public function filterByPackPermission($packPermission, $comparison = null)
    {
        if ($packPermission instanceof \Models\PackPermission) {
            return $this
                ->addUsingAlias(PackTableMap::COL_ID, $packPermission->getPackId(), $comparison);
        } elseif ($packPermission instanceof ObjectCollection) {
            return $this
                ->usePackPermissionQuery()
                ->filterByPrimaryKeys($packPermission->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPackPermission() only accepts arguments of type \Models\PackPermission or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PackPermission relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function joinPackPermission($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PackPermission');

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
            $this->addJoinObject($join, 'PackPermission');
        }

        return $this;
    }

    /**
     * Use the PackPermission relation PackPermission object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\PackPermissionQuery A secondary query class using the current class as primary query
     */
    public function usePackPermissionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPackPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PackPermission', '\Models\PackPermissionQuery');
    }

    /**
     * Filter the query by a related \Models\File object
     *
     * @param \Models\File|ObjectCollection $file the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPackQuery The current query, for fluid interface
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof \Models\File) {
            return $this
                ->addUsingAlias(PackTableMap::COL_ID, $file->getPackId(), $comparison);
        } elseif ($file instanceof ObjectCollection) {
            return $this
                ->useFileQuery()
                ->filterByPrimaryKeys($file->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type \Models\File or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function joinFile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

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
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Models\FileQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPack $pack Object to remove from the list of results
     *
     * @return $this|ChildPackQuery The current query, for fluid interface
     */
    public function prune($pack = null)
    {
        if ($pack) {
            $this->addUsingAlias(PackTableMap::COL_ID, $pack->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the pack table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PackTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PackTableMap::clearInstancePool();
            PackTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PackTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PackTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PackTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PackTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PackTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PackTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PackTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PackTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PackTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPackQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PackTableMap::COL_CREATED_AT);
    }

} // PackQuery
