<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'user' table.
 *
 *
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildUserQuery orderBySurname($order = Criteria::ASC) Order by the surname column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildUserQuery orderByAvatarPath($order = Criteria::ASC) Order by the avatar_path column
 * @method     ChildUserQuery orderByPasswordResetToken($order = Criteria::ASC) Order by the password_reset_token column
 * @method     ChildUserQuery orderByEmailConfirmToken($order = Criteria::ASC) Order by the email_confirm_token column
 * @method     ChildUserQuery orderByEmailConfirmedAt($order = Criteria::ASC) Order by the email_confirmed_at column
 * @method     ChildUserQuery orderByDeletedAt($order = Criteria::ASC) Order by the deleted_at column
 * @method     ChildUserQuery orderByAccountRestoreToken($order = Criteria::ASC) Order by the account_restore_token column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByName() Group by the name column
 * @method     ChildUserQuery groupBySurname() Group by the surname column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 * @method     ChildUserQuery groupByEmail() Group by the email column
 * @method     ChildUserQuery groupByAvatarPath() Group by the avatar_path column
 * @method     ChildUserQuery groupByPasswordResetToken() Group by the password_reset_token column
 * @method     ChildUserQuery groupByEmailConfirmToken() Group by the email_confirm_token column
 * @method     ChildUserQuery groupByEmailConfirmedAt() Group by the email_confirmed_at column
 * @method     ChildUserQuery groupByDeletedAt() Group by the deleted_at column
 * @method     ChildUserQuery groupByAccountRestoreToken() Group by the account_restore_token column
 * @method     ChildUserQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinIdentity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Identity relation
 * @method     ChildUserQuery rightJoinIdentity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Identity relation
 * @method     ChildUserQuery innerJoinIdentity($relationAlias = null) Adds a INNER JOIN clause to the query using the Identity relation
 *
 * @method     ChildUserQuery joinWithIdentity($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Identity relation
 *
 * @method     ChildUserQuery leftJoinWithIdentity() Adds a LEFT JOIN clause and with to the query using the Identity relation
 * @method     ChildUserQuery rightJoinWithIdentity() Adds a RIGHT JOIN clause and with to the query using the Identity relation
 * @method     ChildUserQuery innerJoinWithIdentity() Adds a INNER JOIN clause and with to the query using the Identity relation
 *
 * @method     ChildUserQuery leftJoinPackPermission($relationAlias = null) Adds a LEFT JOIN clause to the query using the PackPermission relation
 * @method     ChildUserQuery rightJoinPackPermission($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PackPermission relation
 * @method     ChildUserQuery innerJoinPackPermission($relationAlias = null) Adds a INNER JOIN clause to the query using the PackPermission relation
 *
 * @method     ChildUserQuery joinWithPackPermission($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the PackPermission relation
 *
 * @method     ChildUserQuery leftJoinWithPackPermission() Adds a LEFT JOIN clause and with to the query using the PackPermission relation
 * @method     ChildUserQuery rightJoinWithPackPermission() Adds a RIGHT JOIN clause and with to the query using the PackPermission relation
 * @method     ChildUserQuery innerJoinWithPackPermission() Adds a INNER JOIN clause and with to the query using the PackPermission relation
 *
 * @method     ChildUserQuery leftJoinPack($relationAlias = null) Adds a LEFT JOIN clause to the query using the Pack relation
 * @method     ChildUserQuery rightJoinPack($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Pack relation
 * @method     ChildUserQuery innerJoinPack($relationAlias = null) Adds a INNER JOIN clause to the query using the Pack relation
 *
 * @method     ChildUserQuery joinWithPack($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Pack relation
 *
 * @method     ChildUserQuery leftJoinWithPack() Adds a LEFT JOIN clause and with to the query using the Pack relation
 * @method     ChildUserQuery rightJoinWithPack() Adds a RIGHT JOIN clause and with to the query using the Pack relation
 * @method     ChildUserQuery innerJoinWithPack() Adds a INNER JOIN clause and with to the query using the Pack relation
 *
 * @method     ChildUserQuery leftJoinMyGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the MyGroup relation
 * @method     ChildUserQuery rightJoinMyGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MyGroup relation
 * @method     ChildUserQuery innerJoinMyGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the MyGroup relation
 *
 * @method     ChildUserQuery joinWithMyGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MyGroup relation
 *
 * @method     ChildUserQuery leftJoinWithMyGroup() Adds a LEFT JOIN clause and with to the query using the MyGroup relation
 * @method     ChildUserQuery rightJoinWithMyGroup() Adds a RIGHT JOIN clause and with to the query using the MyGroup relation
 * @method     ChildUserQuery innerJoinWithMyGroup() Adds a INNER JOIN clause and with to the query using the MyGroup relation
 *
 * @method     ChildUserQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildUserQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildUserQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildUserQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     ChildUserQuery leftJoinUserGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserGroup relation
 * @method     ChildUserQuery rightJoinUserGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserGroup relation
 * @method     ChildUserQuery innerJoinUserGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the UserGroup relation
 *
 * @method     ChildUserQuery joinWithUserGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UserGroup relation
 *
 * @method     ChildUserQuery leftJoinWithUserGroup() Adds a LEFT JOIN clause and with to the query using the UserGroup relation
 * @method     ChildUserQuery rightJoinWithUserGroup() Adds a RIGHT JOIN clause and with to the query using the UserGroup relation
 * @method     ChildUserQuery innerJoinWithUserGroup() Adds a INNER JOIN clause and with to the query using the UserGroup relation
 *
 * @method     \Models\IdentityQuery|\Models\PackPermissionQuery|\Models\PackQuery|\Models\GroupQuery|\Models\CommentQuery|\Models\UserGroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser findOne(ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser findOneByName(string $name) Return the first ChildUser filtered by the name column
 * @method     ChildUser findOneBySurname(string $surname) Return the first ChildUser filtered by the surname column
 * @method     ChildUser findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 * @method     ChildUser findOneByEmail(string $email) Return the first ChildUser filtered by the email column
 * @method     ChildUser findOneByAvatarPath(string $avatar_path) Return the first ChildUser filtered by the avatar_path column
 * @method     ChildUser findOneByPasswordResetToken(string $password_reset_token) Return the first ChildUser filtered by the password_reset_token column
 * @method     ChildUser findOneByEmailConfirmToken(string $email_confirm_token) Return the first ChildUser filtered by the email_confirm_token column
 * @method     ChildUser findOneByEmailConfirmedAt(string $email_confirmed_at) Return the first ChildUser filtered by the email_confirmed_at column
 * @method     ChildUser findOneByDeletedAt(string $deleted_at) Return the first ChildUser filtered by the deleted_at column
 * @method     ChildUser findOneByAccountRestoreToken(string $account_restore_token) Return the first ChildUser filtered by the account_restore_token column
 * @method     ChildUser findOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column
 * @method     ChildUser findOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column *

 * @method     ChildUser requirePk($key, ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByName(string $name) Return the first ChildUser filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySurname(string $surname) Return the first ChildUser filtered by the surname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $email) Return the first ChildUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAvatarPath(string $avatar_path) Return the first ChildUser filtered by the avatar_path column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordResetToken(string $password_reset_token) Return the first ChildUser filtered by the password_reset_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailConfirmToken(string $email_confirm_token) Return the first ChildUser filtered by the email_confirm_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailConfirmedAt(string $email_confirmed_at) Return the first ChildUser filtered by the email_confirmed_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByDeletedAt(string $deleted_at) Return the first ChildUser filtered by the deleted_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAccountRestoreToken(string $account_restore_token) Return the first ChildUser filtered by the account_restore_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $created_at) Return the first ChildUser filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $updated_at) Return the first ChildUser filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @method     ChildUser[]|ObjectCollection findById(int $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|ObjectCollection findByUsername(string $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|ObjectCollection findByName(string $name) Return ChildUser objects filtered by the name column
 * @method     ChildUser[]|ObjectCollection findBySurname(string $surname) Return ChildUser objects filtered by the surname column
 * @method     ChildUser[]|ObjectCollection findByPassword(string $password) Return ChildUser objects filtered by the password column
 * @method     ChildUser[]|ObjectCollection findByEmail(string $email) Return ChildUser objects filtered by the email column
 * @method     ChildUser[]|ObjectCollection findByAvatarPath(string $avatar_path) Return ChildUser objects filtered by the avatar_path column
 * @method     ChildUser[]|ObjectCollection findByPasswordResetToken(string $password_reset_token) Return ChildUser objects filtered by the password_reset_token column
 * @method     ChildUser[]|ObjectCollection findByEmailConfirmToken(string $email_confirm_token) Return ChildUser objects filtered by the email_confirm_token column
 * @method     ChildUser[]|ObjectCollection findByEmailConfirmedAt(string $email_confirmed_at) Return ChildUser objects filtered by the email_confirmed_at column
 * @method     ChildUser[]|ObjectCollection findByDeletedAt(string $deleted_at) Return ChildUser objects filtered by the deleted_at column
 * @method     ChildUser[]|ObjectCollection findByAccountRestoreToken(string $account_restore_token) Return ChildUser objects filtered by the account_restore_token column
 * @method     ChildUser[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildUser objects filtered by the created_at column
 * @method     ChildUser[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildUser objects filtered by the updated_at column
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\UserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
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
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, username, name, surname, password, email, avatar_path, password_reset_token, email_confirm_token, email_confirmed_at, deleted_at, account_restore_token, created_at, updated_at FROM user WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%'); // WHERE username LIKE '%fooValue%'
     * </code>
     *
     * @param     string $username The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUsername($username = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $username)) {
                $username = str_replace('*', '%', $username);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(UserTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the surname column
     *
     * Example usage:
     * <code>
     * $query->filterBySurname('fooValue');   // WHERE surname = 'fooValue'
     * $query->filterBySurname('%fooValue%'); // WHERE surname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $surname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterBySurname($surname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($surname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $surname)) {
                $surname = str_replace('*', '%', $surname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_SURNAME, $surname, $comparison);
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%'); // WHERE password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $password The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPassword($password = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $password)) {
                $password = str_replace('*', '%', $password);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the avatar_path column
     *
     * Example usage:
     * <code>
     * $query->filterByAvatarPath('fooValue');   // WHERE avatar_path = 'fooValue'
     * $query->filterByAvatarPath('%fooValue%'); // WHERE avatar_path LIKE '%fooValue%'
     * </code>
     *
     * @param     string $avatarPath The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAvatarPath($avatarPath = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($avatarPath)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $avatarPath)) {
                $avatarPath = str_replace('*', '%', $avatarPath);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_AVATAR_PATH, $avatarPath, $comparison);
    }

    /**
     * Filter the query on the password_reset_token column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordResetToken('fooValue');   // WHERE password_reset_token = 'fooValue'
     * $query->filterByPasswordResetToken('%fooValue%'); // WHERE password_reset_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $passwordResetToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByPasswordResetToken($passwordResetToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passwordResetToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $passwordResetToken)) {
                $passwordResetToken = str_replace('*', '%', $passwordResetToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_PASSWORD_RESET_TOKEN, $passwordResetToken, $comparison);
    }

    /**
     * Filter the query on the email_confirm_token column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailConfirmToken('fooValue');   // WHERE email_confirm_token = 'fooValue'
     * $query->filterByEmailConfirmToken('%fooValue%'); // WHERE email_confirm_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailConfirmToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmailConfirmToken($emailConfirmToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailConfirmToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $emailConfirmToken)) {
                $emailConfirmToken = str_replace('*', '%', $emailConfirmToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRM_TOKEN, $emailConfirmToken, $comparison);
    }

    /**
     * Filter the query on the email_confirmed_at column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailConfirmedAt('2011-03-14'); // WHERE email_confirmed_at = '2011-03-14'
     * $query->filterByEmailConfirmedAt('now'); // WHERE email_confirmed_at = '2011-03-14'
     * $query->filterByEmailConfirmedAt(array('max' => 'yesterday')); // WHERE email_confirmed_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $emailConfirmedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByEmailConfirmedAt($emailConfirmedAt = null, $comparison = null)
    {
        if (is_array($emailConfirmedAt)) {
            $useMinMax = false;
            if (isset($emailConfirmedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($emailConfirmedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_EMAIL_CONFIRMED_AT, $emailConfirmedAt, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_DELETED_AT, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_DELETED_AT, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_DELETED_AT, $deletedAt, $comparison);
    }

    /**
     * Filter the query on the account_restore_token column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountRestoreToken('fooValue');   // WHERE account_restore_token = 'fooValue'
     * $query->filterByAccountRestoreToken('%fooValue%'); // WHERE account_restore_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $accountRestoreToken The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByAccountRestoreToken($accountRestoreToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($accountRestoreToken)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $accountRestoreToken)) {
                $accountRestoreToken = str_replace('*', '%', $accountRestoreToken);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_ACCOUNT_RESTORE_TOKEN, $accountRestoreToken, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Identity object
     *
     * @param \Models\Identity|ObjectCollection $identity the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByIdentity($identity, $comparison = null)
    {
        if ($identity instanceof \Models\Identity) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $identity->getUserId(), $comparison);
        } elseif ($identity instanceof ObjectCollection) {
            return $this
                ->useIdentityQuery()
                ->filterByPrimaryKeys($identity->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIdentity() only accepts arguments of type \Models\Identity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Identity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinIdentity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Identity');

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
            $this->addJoinObject($join, 'Identity');
        }

        return $this;
    }

    /**
     * Use the Identity relation Identity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\IdentityQuery A secondary query class using the current class as primary query
     */
    public function useIdentityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinIdentity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Identity', '\Models\IdentityQuery');
    }

    /**
     * Filter the query by a related \Models\PackPermission object
     *
     * @param \Models\PackPermission|ObjectCollection $packPermission the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPackPermission($packPermission, $comparison = null)
    {
        if ($packPermission instanceof \Models\PackPermission) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $packPermission->getUserId(), $comparison);
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
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinPackPermission($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePackPermissionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPackPermission($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PackPermission', '\Models\PackPermissionQuery');
    }

    /**
     * Filter the query by a related \Models\Pack object
     *
     * @param \Models\Pack|ObjectCollection $pack the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByPack($pack, $comparison = null)
    {
        if ($pack instanceof \Models\Pack) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $pack->getOwnerId(), $comparison);
        } elseif ($pack instanceof ObjectCollection) {
            return $this
                ->usePackQuery()
                ->filterByPrimaryKeys($pack->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildUserQuery The current query, for fluid interface
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
     * Filter the query by a related \Models\Group object
     *
     * @param \Models\Group|ObjectCollection $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByMyGroup($group, $comparison = null)
    {
        if ($group instanceof \Models\Group) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $group->getOwnerId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            return $this
                ->useMyGroupQuery()
                ->filterByPrimaryKeys($group->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMyGroup() only accepts arguments of type \Models\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MyGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinMyGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MyGroup');

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
            $this->addJoinObject($join, 'MyGroup');
        }

        return $this;
    }

    /**
     * Use the MyGroup relation Group object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\GroupQuery A secondary query class using the current class as primary query
     */
    public function useMyGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMyGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MyGroup', '\Models\GroupQuery');
    }

    /**
     * Filter the query by a related \Models\Comment object
     *
     * @param \Models\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \Models\Comment) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $comment->getUserId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \Models\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

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
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\Models\CommentQuery');
    }

    /**
     * Filter the query by a related \Models\UserGroup object
     *
     * @param \Models\UserGroup|ObjectCollection $userGroup the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByUserGroup($userGroup, $comparison = null)
    {
        if ($userGroup instanceof \Models\UserGroup) {
            return $this
                ->addUsingAlias(UserTableMap::COL_ID, $userGroup->getUserId(), $comparison);
        } elseif ($userGroup instanceof ObjectCollection) {
            return $this
                ->useUserGroupQuery()
                ->filterByPrimaryKeys($userGroup->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserGroup() only accepts arguments of type \Models\UserGroup or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserGroup relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function joinUserGroup($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserGroup');

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
            $this->addJoinObject($join, 'UserGroup');
        }

        return $this;
    }

    /**
     * Use the UserGroup relation UserGroup object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserGroupQuery A secondary query class using the current class as primary query
     */
    public function useUserGroupQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserGroup', '\Models\UserGroupQuery');
    }

    /**
     * Filter the query by a related Group object
     * using the user_group table as cross reference
     *
     * @param Group $group the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildUserQuery The current query, for fluid interface
     */
    public function filterByGroup($group, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserGroupQuery()
            ->filterByGroup($group, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildUser $user Object to remove from the list of results
     *
     * @return $this|ChildUserQuery The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(UserTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(UserTableMap::COL_CREATED_AT);
    }

} // UserQuery
