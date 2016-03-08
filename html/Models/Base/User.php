<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Group as ChildGroup;
use Models\GroupPermission as ChildGroupPermission;
use Models\GroupPermissionQuery as ChildGroupPermissionQuery;
use Models\GroupQuery as ChildGroupQuery;
use Models\Identity as ChildIdentity;
use Models\IdentityQuery as ChildIdentityQuery;
use Models\Pack as ChildPack;
use Models\PackPermission as ChildPackPermission;
use Models\PackPermissionQuery as ChildPackPermissionQuery;
use Models\PackQuery as ChildPackQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\Map\GroupPermissionTableMap;
use Models\Map\GroupTableMap;
use Models\Map\IdentityTableMap;
use Models\Map\PackPermissionTableMap;
use Models\Map\PackTableMap;
use Models\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Propel\Runtime\Validator\Constraints\Uniqueness;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base class that represents a row from the 'user' table.
 *
 *
 *
* @package    propel.generator.Models.Base
*/
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\UserTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the surname field.
     *
     * @var        string
     */
    protected $surname;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the avatar_path field.
     *
     * @var        string
     */
    protected $avatar_path;

    /**
     * The value for the password_reset_token field.
     *
     * @var        string
     */
    protected $password_reset_token;

    /**
     * The value for the email_confirm_token field.
     *
     * @var        string
     */
    protected $email_confirm_token;

    /**
     * The value for the email_confirmed_at field.
     *
     * @var        \DateTime
     */
    protected $email_confirmed_at;

    /**
     * The value for the deleted_at field.
     *
     * @var        \DateTime
     */
    protected $deleted_at;

    /**
     * The value for the created_at field.
     *
     * @var        \DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        \DateTime
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildIdentity[] Collection to store aggregation of ChildIdentity objects.
     */
    protected $collIdentities;
    protected $collIdentitiesPartial;

    /**
     * @var        ObjectCollection|ChildPackPermission[] Collection to store aggregation of ChildPackPermission objects.
     */
    protected $collPackPermissions;
    protected $collPackPermissionsPartial;

    /**
     * @var        ObjectCollection|ChildGroupPermission[] Collection to store aggregation of ChildGroupPermission objects.
     */
    protected $collGroupPermissions;
    protected $collGroupPermissionsPartial;

    /**
     * @var        ObjectCollection|ChildPack[] Collection to store aggregation of ChildPack objects.
     */
    protected $collPacks;
    protected $collPacksPartial;

    /**
     * @var        ObjectCollection|ChildGroup[] Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;
    protected $collGroupsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildIdentity[]
     */
    protected $identitiesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPackPermission[]
     */
    protected $packPermissionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroupPermission[]
     */
    protected $groupPermissionsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPack[]
     */
    protected $packsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Base\User object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|User The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [surname] column value.
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [avatar_path] column value.
     *
     * @return string
     */
    public function getAvatarPath()
    {
        return $this->avatar_path;
    }

    /**
     * Get the [password_reset_token] column value.
     *
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->password_reset_token;
    }

    /**
     * Get the [email_confirm_token] column value.
     *
     * @return string
     */
    public function getEmailConfirmToken()
    {
        return $this->email_confirm_token;
    }

    /**
     * Get the [optionally formatted] temporal [email_confirmed_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEmailConfirmedAt($format = NULL)
    {
        if ($format === null) {
            return $this->email_confirmed_at;
        } else {
            return $this->email_confirmed_at instanceof \DateTime ? $this->email_confirmed_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [deleted_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDeletedAt($format = NULL)
    {
        if ($format === null) {
            return $this->deleted_at;
        } else {
            return $this->deleted_at instanceof \DateTime ? $this->deleted_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [username] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    } // setUsername()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[UserTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [surname] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setSurname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->surname !== $v) {
            $this->surname = $v;
            $this->modifiedColumns[UserTableMap::COL_SURNAME] = true;
        }

        return $this;
    } // setSurname()

    /**
     * Set the value of [password] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    } // setPassword()

    /**
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [avatar_path] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setAvatarPath($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->avatar_path !== $v) {
            $this->avatar_path = $v;
            $this->modifiedColumns[UserTableMap::COL_AVATAR_PATH] = true;
        }

        return $this;
    } // setAvatarPath()

    /**
     * Set the value of [password_reset_token] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setPasswordResetToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password_reset_token !== $v) {
            $this->password_reset_token = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD_RESET_TOKEN] = true;
        }

        return $this;
    } // setPasswordResetToken()

    /**
     * Set the value of [email_confirm_token] column.
     *
     * @param string $v new value
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmailConfirmToken($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_confirm_token !== $v) {
            $this->email_confirm_token = $v;
            $this->modifiedColumns[UserTableMap::COL_EMAIL_CONFIRM_TOKEN] = true;
        }

        return $this;
    } // setEmailConfirmToken()

    /**
     * Sets the value of [email_confirmed_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setEmailConfirmedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->email_confirmed_at !== null || $dt !== null) {
            if ($this->email_confirmed_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->email_confirmed_at->format("Y-m-d H:i:s")) {
                $this->email_confirmed_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_EMAIL_CONFIRMED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setEmailConfirmedAt()

    /**
     * Sets the value of [deleted_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setDeletedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->deleted_at !== null || $dt !== null) {
            if ($this->deleted_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->deleted_at->format("Y-m-d H:i:s")) {
                $this->deleted_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_DELETED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setDeletedAt()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : UserTableMap::translateFieldName('Surname', TableMap::TYPE_PHPNAME, $indexType)];
            $this->surname = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : UserTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : UserTableMap::translateFieldName('AvatarPath', TableMap::TYPE_PHPNAME, $indexType)];
            $this->avatar_path = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : UserTableMap::translateFieldName('PasswordResetToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password_reset_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : UserTableMap::translateFieldName('EmailConfirmToken', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_confirm_token = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : UserTableMap::translateFieldName('EmailConfirmedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->email_confirmed_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : UserTableMap::translateFieldName('DeletedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->deleted_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : UserTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : UserTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\User'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collIdentities = null;

            $this->collPackPermissions = null;

            $this->collGroupPermissions = null;

            $this->collPacks = null;

            $this->collGroups = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                UserTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->identitiesScheduledForDeletion !== null) {
                if (!$this->identitiesScheduledForDeletion->isEmpty()) {
                    \Models\IdentityQuery::create()
                        ->filterByPrimaryKeys($this->identitiesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->identitiesScheduledForDeletion = null;
                }
            }

            if ($this->collIdentities !== null) {
                foreach ($this->collIdentities as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->packPermissionsScheduledForDeletion !== null) {
                if (!$this->packPermissionsScheduledForDeletion->isEmpty()) {
                    \Models\PackPermissionQuery::create()
                        ->filterByPrimaryKeys($this->packPermissionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->packPermissionsScheduledForDeletion = null;
                }
            }

            if ($this->collPackPermissions !== null) {
                foreach ($this->collPackPermissions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->groupPermissionsScheduledForDeletion !== null) {
                if (!$this->groupPermissionsScheduledForDeletion->isEmpty()) {
                    \Models\GroupPermissionQuery::create()
                        ->filterByPrimaryKeys($this->groupPermissionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupPermissionsScheduledForDeletion = null;
                }
            }

            if ($this->collGroupPermissions !== null) {
                foreach ($this->collGroupPermissions as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->packsScheduledForDeletion !== null) {
                if (!$this->packsScheduledForDeletion->isEmpty()) {
                    \Models\PackQuery::create()
                        ->filterByPrimaryKeys($this->packsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->packsScheduledForDeletion = null;
                }
            }

            if ($this->collPacks !== null) {
                foreach ($this->collPacks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    \Models\GroupQuery::create()
                        ->filterByPrimaryKeys($this->groupsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->groupsScheduledForDeletion = null;
                }
            }

            if ($this->collGroups !== null) {
                foreach ($this->collGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(UserTableMap::COL_SURNAME)) {
            $modifiedColumns[':p' . $index++]  = 'surname';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR_PATH)) {
            $modifiedColumns[':p' . $index++]  = 'avatar_path';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RESET_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'password_reset_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRM_TOKEN)) {
            $modifiedColumns[':p' . $index++]  = 'email_confirm_token';
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRMED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'email_confirmed_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'deleted_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'surname':
                        $stmt->bindValue($identifier, $this->surname, PDO::PARAM_STR);
                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);
                        break;
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'avatar_path':
                        $stmt->bindValue($identifier, $this->avatar_path, PDO::PARAM_STR);
                        break;
                    case 'password_reset_token':
                        $stmt->bindValue($identifier, $this->password_reset_token, PDO::PARAM_STR);
                        break;
                    case 'email_confirm_token':
                        $stmt->bindValue($identifier, $this->email_confirm_token, PDO::PARAM_STR);
                        break;
                    case 'email_confirmed_at':
                        $stmt->bindValue($identifier, $this->email_confirmed_at ? $this->email_confirmed_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'deleted_at':
                        $stmt->bindValue($identifier, $this->deleted_at ? $this->deleted_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getUsername();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getSurname();
                break;
            case 4:
                return $this->getPassword();
                break;
            case 5:
                return $this->getEmail();
                break;
            case 6:
                return $this->getAvatarPath();
                break;
            case 7:
                return $this->getPasswordResetToken();
                break;
            case 8:
                return $this->getEmailConfirmToken();
                break;
            case 9:
                return $this->getEmailConfirmedAt();
                break;
            case 10:
                return $this->getDeletedAt();
                break;
            case 11:
                return $this->getCreatedAt();
                break;
            case 12:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getSurname(),
            $keys[4] => $this->getPassword(),
            $keys[5] => $this->getEmail(),
            $keys[6] => $this->getAvatarPath(),
            $keys[7] => $this->getPasswordResetToken(),
            $keys[8] => $this->getEmailConfirmToken(),
            $keys[9] => $this->getEmailConfirmedAt(),
            $keys[10] => $this->getDeletedAt(),
            $keys[11] => $this->getCreatedAt(),
            $keys[12] => $this->getUpdatedAt(),
        );
        if ($result[$keys[9]] instanceof \DateTime) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }

        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }

        if ($result[$keys[12]] instanceof \DateTime) {
            $result[$keys[12]] = $result[$keys[12]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collIdentities) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'identities';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'identities';
                        break;
                    default:
                        $key = 'Identities';
                }

                $result[$key] = $this->collIdentities->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPackPermissions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'packPermissions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'pack_permissions';
                        break;
                    default:
                        $key = 'PackPermissions';
                }

                $result[$key] = $this->collPackPermissions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGroupPermissions) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groupPermissions';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'group_permissions';
                        break;
                    default:
                        $key = 'GroupPermissions';
                }

                $result[$key] = $this->collGroupPermissions->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPacks) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'packs';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'packs';
                        break;
                    default:
                        $key = 'Packs';
                }

                $result[$key] = $this->collPacks->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'groups';
                        break;
                    default:
                        $key = 'Groups';
                }

                $result[$key] = $this->collGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Models\User
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\User
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setSurname($value);
                break;
            case 4:
                $this->setPassword($value);
                break;
            case 5:
                $this->setEmail($value);
                break;
            case 6:
                $this->setAvatarPath($value);
                break;
            case 7:
                $this->setPasswordResetToken($value);
                break;
            case 8:
                $this->setEmailConfirmToken($value);
                break;
            case 9:
                $this->setEmailConfirmedAt($value);
                break;
            case 10:
                $this->setDeletedAt($value);
                break;
            case 11:
                $this->setCreatedAt($value);
                break;
            case 12:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSurname($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setPassword($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEmail($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setAvatarPath($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setPasswordResetToken($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setEmailConfirmToken($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setEmailConfirmedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setDeletedAt($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setCreatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setUpdatedAt($arr[$keys[12]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Models\User The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_NAME)) {
            $criteria->add(UserTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(UserTableMap::COL_SURNAME)) {
            $criteria->add(UserTableMap::COL_SURNAME, $this->surname);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL)) {
            $criteria->add(UserTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(UserTableMap::COL_AVATAR_PATH)) {
            $criteria->add(UserTableMap::COL_AVATAR_PATH, $this->avatar_path);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD_RESET_TOKEN)) {
            $criteria->add(UserTableMap::COL_PASSWORD_RESET_TOKEN, $this->password_reset_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRM_TOKEN)) {
            $criteria->add(UserTableMap::COL_EMAIL_CONFIRM_TOKEN, $this->email_confirm_token);
        }
        if ($this->isColumnModified(UserTableMap::COL_EMAIL_CONFIRMED_AT)) {
            $criteria->add(UserTableMap::COL_EMAIL_CONFIRMED_AT, $this->email_confirmed_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_DELETED_AT)) {
            $criteria->add(UserTableMap::COL_DELETED_AT, $this->deleted_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_CREATED_AT)) {
            $criteria->add(UserTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(UserTableMap::COL_UPDATED_AT)) {
            $criteria->add(UserTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Models\User (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setName($this->getName());
        $copyObj->setSurname($this->getSurname());
        $copyObj->setPassword($this->getPassword());
        $copyObj->setEmail($this->getEmail());
        $copyObj->setAvatarPath($this->getAvatarPath());
        $copyObj->setPasswordResetToken($this->getPasswordResetToken());
        $copyObj->setEmailConfirmToken($this->getEmailConfirmToken());
        $copyObj->setEmailConfirmedAt($this->getEmailConfirmedAt());
        $copyObj->setDeletedAt($this->getDeletedAt());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getIdentities() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIdentity($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPackPermissions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPackPermission($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGroupPermissions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroupPermission($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPacks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPack($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroup($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\User Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Identity' == $relationName) {
            return $this->initIdentities();
        }
        if ('PackPermission' == $relationName) {
            return $this->initPackPermissions();
        }
        if ('GroupPermission' == $relationName) {
            return $this->initGroupPermissions();
        }
        if ('Pack' == $relationName) {
            return $this->initPacks();
        }
        if ('Group' == $relationName) {
            return $this->initGroups();
        }
    }

    /**
     * Clears out the collIdentities collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addIdentities()
     */
    public function clearIdentities()
    {
        $this->collIdentities = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collIdentities collection loaded partially.
     */
    public function resetPartialIdentities($v = true)
    {
        $this->collIdentitiesPartial = $v;
    }

    /**
     * Initializes the collIdentities collection.
     *
     * By default this just sets the collIdentities collection to an empty array (like clearcollIdentities());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIdentities($overrideExisting = true)
    {
        if (null !== $this->collIdentities && !$overrideExisting) {
            return;
        }

        $collectionClassName = IdentityTableMap::getTableMap()->getCollectionClassName();

        $this->collIdentities = new $collectionClassName;
        $this->collIdentities->setModel('\Models\Identity');
    }

    /**
     * Gets an array of ChildIdentity objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildIdentity[] List of ChildIdentity objects
     * @throws PropelException
     */
    public function getIdentities(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collIdentitiesPartial && !$this->isNew();
        if (null === $this->collIdentities || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIdentities) {
                // return empty collection
                $this->initIdentities();
            } else {
                $collIdentities = ChildIdentityQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collIdentitiesPartial && count($collIdentities)) {
                        $this->initIdentities(false);

                        foreach ($collIdentities as $obj) {
                            if (false == $this->collIdentities->contains($obj)) {
                                $this->collIdentities->append($obj);
                            }
                        }

                        $this->collIdentitiesPartial = true;
                    }

                    return $collIdentities;
                }

                if ($partial && $this->collIdentities) {
                    foreach ($this->collIdentities as $obj) {
                        if ($obj->isNew()) {
                            $collIdentities[] = $obj;
                        }
                    }
                }

                $this->collIdentities = $collIdentities;
                $this->collIdentitiesPartial = false;
            }
        }

        return $this->collIdentities;
    }

    /**
     * Sets a collection of ChildIdentity objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $identities A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setIdentities(Collection $identities, ConnectionInterface $con = null)
    {
        /** @var ChildIdentity[] $identitiesToDelete */
        $identitiesToDelete = $this->getIdentities(new Criteria(), $con)->diff($identities);


        $this->identitiesScheduledForDeletion = $identitiesToDelete;

        foreach ($identitiesToDelete as $identityRemoved) {
            $identityRemoved->setUser(null);
        }

        $this->collIdentities = null;
        foreach ($identities as $identity) {
            $this->addIdentity($identity);
        }

        $this->collIdentities = $identities;
        $this->collIdentitiesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Identity objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Identity objects.
     * @throws PropelException
     */
    public function countIdentities(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collIdentitiesPartial && !$this->isNew();
        if (null === $this->collIdentities || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIdentities) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIdentities());
            }

            $query = ChildIdentityQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collIdentities);
    }

    /**
     * Method called to associate a ChildIdentity object to this object
     * through the ChildIdentity foreign key attribute.
     *
     * @param  ChildIdentity $l ChildIdentity
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addIdentity(ChildIdentity $l)
    {
        if ($this->collIdentities === null) {
            $this->initIdentities();
            $this->collIdentitiesPartial = true;
        }

        if (!$this->collIdentities->contains($l)) {
            $this->doAddIdentity($l);

            if ($this->identitiesScheduledForDeletion and $this->identitiesScheduledForDeletion->contains($l)) {
                $this->identitiesScheduledForDeletion->remove($this->identitiesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildIdentity $identity The ChildIdentity object to add.
     */
    protected function doAddIdentity(ChildIdentity $identity)
    {
        $this->collIdentities[]= $identity;
        $identity->setUser($this);
    }

    /**
     * @param  ChildIdentity $identity The ChildIdentity object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeIdentity(ChildIdentity $identity)
    {
        if ($this->getIdentities()->contains($identity)) {
            $pos = $this->collIdentities->search($identity);
            $this->collIdentities->remove($pos);
            if (null === $this->identitiesScheduledForDeletion) {
                $this->identitiesScheduledForDeletion = clone $this->collIdentities;
                $this->identitiesScheduledForDeletion->clear();
            }
            $this->identitiesScheduledForDeletion[]= clone $identity;
            $identity->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collPackPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPackPermissions()
     */
    public function clearPackPermissions()
    {
        $this->collPackPermissions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPackPermissions collection loaded partially.
     */
    public function resetPartialPackPermissions($v = true)
    {
        $this->collPackPermissionsPartial = $v;
    }

    /**
     * Initializes the collPackPermissions collection.
     *
     * By default this just sets the collPackPermissions collection to an empty array (like clearcollPackPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPackPermissions($overrideExisting = true)
    {
        if (null !== $this->collPackPermissions && !$overrideExisting) {
            return;
        }

        $collectionClassName = PackPermissionTableMap::getTableMap()->getCollectionClassName();

        $this->collPackPermissions = new $collectionClassName;
        $this->collPackPermissions->setModel('\Models\PackPermission');
    }

    /**
     * Gets an array of ChildPackPermission objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPackPermission[] List of ChildPackPermission objects
     * @throws PropelException
     */
    public function getPackPermissions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPackPermissionsPartial && !$this->isNew();
        if (null === $this->collPackPermissions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPackPermissions) {
                // return empty collection
                $this->initPackPermissions();
            } else {
                $collPackPermissions = ChildPackPermissionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPackPermissionsPartial && count($collPackPermissions)) {
                        $this->initPackPermissions(false);

                        foreach ($collPackPermissions as $obj) {
                            if (false == $this->collPackPermissions->contains($obj)) {
                                $this->collPackPermissions->append($obj);
                            }
                        }

                        $this->collPackPermissionsPartial = true;
                    }

                    return $collPackPermissions;
                }

                if ($partial && $this->collPackPermissions) {
                    foreach ($this->collPackPermissions as $obj) {
                        if ($obj->isNew()) {
                            $collPackPermissions[] = $obj;
                        }
                    }
                }

                $this->collPackPermissions = $collPackPermissions;
                $this->collPackPermissionsPartial = false;
            }
        }

        return $this->collPackPermissions;
    }

    /**
     * Sets a collection of ChildPackPermission objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $packPermissions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPackPermissions(Collection $packPermissions, ConnectionInterface $con = null)
    {
        /** @var ChildPackPermission[] $packPermissionsToDelete */
        $packPermissionsToDelete = $this->getPackPermissions(new Criteria(), $con)->diff($packPermissions);


        $this->packPermissionsScheduledForDeletion = $packPermissionsToDelete;

        foreach ($packPermissionsToDelete as $packPermissionRemoved) {
            $packPermissionRemoved->setUser(null);
        }

        $this->collPackPermissions = null;
        foreach ($packPermissions as $packPermission) {
            $this->addPackPermission($packPermission);
        }

        $this->collPackPermissions = $packPermissions;
        $this->collPackPermissionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PackPermission objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PackPermission objects.
     * @throws PropelException
     */
    public function countPackPermissions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPackPermissionsPartial && !$this->isNew();
        if (null === $this->collPackPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPackPermissions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPackPermissions());
            }

            $query = ChildPackPermissionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPackPermissions);
    }

    /**
     * Method called to associate a ChildPackPermission object to this object
     * through the ChildPackPermission foreign key attribute.
     *
     * @param  ChildPackPermission $l ChildPackPermission
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addPackPermission(ChildPackPermission $l)
    {
        if ($this->collPackPermissions === null) {
            $this->initPackPermissions();
            $this->collPackPermissionsPartial = true;
        }

        if (!$this->collPackPermissions->contains($l)) {
            $this->doAddPackPermission($l);

            if ($this->packPermissionsScheduledForDeletion and $this->packPermissionsScheduledForDeletion->contains($l)) {
                $this->packPermissionsScheduledForDeletion->remove($this->packPermissionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPackPermission $packPermission The ChildPackPermission object to add.
     */
    protected function doAddPackPermission(ChildPackPermission $packPermission)
    {
        $this->collPackPermissions[]= $packPermission;
        $packPermission->setUser($this);
    }

    /**
     * @param  ChildPackPermission $packPermission The ChildPackPermission object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePackPermission(ChildPackPermission $packPermission)
    {
        if ($this->getPackPermissions()->contains($packPermission)) {
            $pos = $this->collPackPermissions->search($packPermission);
            $this->collPackPermissions->remove($pos);
            if (null === $this->packPermissionsScheduledForDeletion) {
                $this->packPermissionsScheduledForDeletion = clone $this->collPackPermissions;
                $this->packPermissionsScheduledForDeletion->clear();
            }
            $this->packPermissionsScheduledForDeletion[]= clone $packPermission;
            $packPermission->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related PackPermissions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPackPermission[] List of ChildPackPermission objects
     */
    public function getPackPermissionsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPackPermissionQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getPackPermissions($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related PackPermissions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPackPermission[] List of ChildPackPermission objects
     */
    public function getPackPermissionsJoinPack(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPackPermissionQuery::create(null, $criteria);
        $query->joinWith('Pack', $joinBehavior);

        return $this->getPackPermissions($query, $con);
    }

    /**
     * Clears out the collGroupPermissions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroupPermissions()
     */
    public function clearGroupPermissions()
    {
        $this->collGroupPermissions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGroupPermissions collection loaded partially.
     */
    public function resetPartialGroupPermissions($v = true)
    {
        $this->collGroupPermissionsPartial = $v;
    }

    /**
     * Initializes the collGroupPermissions collection.
     *
     * By default this just sets the collGroupPermissions collection to an empty array (like clearcollGroupPermissions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroupPermissions($overrideExisting = true)
    {
        if (null !== $this->collGroupPermissions && !$overrideExisting) {
            return;
        }

        $collectionClassName = GroupPermissionTableMap::getTableMap()->getCollectionClassName();

        $this->collGroupPermissions = new $collectionClassName;
        $this->collGroupPermissions->setModel('\Models\GroupPermission');
    }

    /**
     * Gets an array of ChildGroupPermission objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroupPermission[] List of ChildGroupPermission objects
     * @throws PropelException
     */
    public function getGroupPermissions(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupPermissionsPartial && !$this->isNew();
        if (null === $this->collGroupPermissions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroupPermissions) {
                // return empty collection
                $this->initGroupPermissions();
            } else {
                $collGroupPermissions = ChildGroupPermissionQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupPermissionsPartial && count($collGroupPermissions)) {
                        $this->initGroupPermissions(false);

                        foreach ($collGroupPermissions as $obj) {
                            if (false == $this->collGroupPermissions->contains($obj)) {
                                $this->collGroupPermissions->append($obj);
                            }
                        }

                        $this->collGroupPermissionsPartial = true;
                    }

                    return $collGroupPermissions;
                }

                if ($partial && $this->collGroupPermissions) {
                    foreach ($this->collGroupPermissions as $obj) {
                        if ($obj->isNew()) {
                            $collGroupPermissions[] = $obj;
                        }
                    }
                }

                $this->collGroupPermissions = $collGroupPermissions;
                $this->collGroupPermissionsPartial = false;
            }
        }

        return $this->collGroupPermissions;
    }

    /**
     * Sets a collection of ChildGroupPermission objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $groupPermissions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setGroupPermissions(Collection $groupPermissions, ConnectionInterface $con = null)
    {
        /** @var ChildGroupPermission[] $groupPermissionsToDelete */
        $groupPermissionsToDelete = $this->getGroupPermissions(new Criteria(), $con)->diff($groupPermissions);


        $this->groupPermissionsScheduledForDeletion = $groupPermissionsToDelete;

        foreach ($groupPermissionsToDelete as $groupPermissionRemoved) {
            $groupPermissionRemoved->setUser(null);
        }

        $this->collGroupPermissions = null;
        foreach ($groupPermissions as $groupPermission) {
            $this->addGroupPermission($groupPermission);
        }

        $this->collGroupPermissions = $groupPermissions;
        $this->collGroupPermissionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related GroupPermission objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related GroupPermission objects.
     * @throws PropelException
     */
    public function countGroupPermissions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupPermissionsPartial && !$this->isNew();
        if (null === $this->collGroupPermissions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroupPermissions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroupPermissions());
            }

            $query = ChildGroupPermissionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collGroupPermissions);
    }

    /**
     * Method called to associate a ChildGroupPermission object to this object
     * through the ChildGroupPermission foreign key attribute.
     *
     * @param  ChildGroupPermission $l ChildGroupPermission
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addGroupPermission(ChildGroupPermission $l)
    {
        if ($this->collGroupPermissions === null) {
            $this->initGroupPermissions();
            $this->collGroupPermissionsPartial = true;
        }

        if (!$this->collGroupPermissions->contains($l)) {
            $this->doAddGroupPermission($l);

            if ($this->groupPermissionsScheduledForDeletion and $this->groupPermissionsScheduledForDeletion->contains($l)) {
                $this->groupPermissionsScheduledForDeletion->remove($this->groupPermissionsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGroupPermission $groupPermission The ChildGroupPermission object to add.
     */
    protected function doAddGroupPermission(ChildGroupPermission $groupPermission)
    {
        $this->collGroupPermissions[]= $groupPermission;
        $groupPermission->setUser($this);
    }

    /**
     * @param  ChildGroupPermission $groupPermission The ChildGroupPermission object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeGroupPermission(ChildGroupPermission $groupPermission)
    {
        if ($this->getGroupPermissions()->contains($groupPermission)) {
            $pos = $this->collGroupPermissions->search($groupPermission);
            $this->collGroupPermissions->remove($pos);
            if (null === $this->groupPermissionsScheduledForDeletion) {
                $this->groupPermissionsScheduledForDeletion = clone $this->collGroupPermissions;
                $this->groupPermissionsScheduledForDeletion->clear();
            }
            $this->groupPermissionsScheduledForDeletion[]= clone $groupPermission;
            $groupPermission->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related GroupPermissions from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildGroupPermission[] List of ChildGroupPermission objects
     */
    public function getGroupPermissionsJoinGroup(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildGroupPermissionQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getGroupPermissions($query, $con);
    }

    /**
     * Clears out the collPacks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPacks()
     */
    public function clearPacks()
    {
        $this->collPacks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPacks collection loaded partially.
     */
    public function resetPartialPacks($v = true)
    {
        $this->collPacksPartial = $v;
    }

    /**
     * Initializes the collPacks collection.
     *
     * By default this just sets the collPacks collection to an empty array (like clearcollPacks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPacks($overrideExisting = true)
    {
        if (null !== $this->collPacks && !$overrideExisting) {
            return;
        }

        $collectionClassName = PackTableMap::getTableMap()->getCollectionClassName();

        $this->collPacks = new $collectionClassName;
        $this->collPacks->setModel('\Models\Pack');
    }

    /**
     * Gets an array of ChildPack objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPack[] List of ChildPack objects
     * @throws PropelException
     */
    public function getPacks(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPacksPartial && !$this->isNew();
        if (null === $this->collPacks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPacks) {
                // return empty collection
                $this->initPacks();
            } else {
                $collPacks = ChildPackQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPacksPartial && count($collPacks)) {
                        $this->initPacks(false);

                        foreach ($collPacks as $obj) {
                            if (false == $this->collPacks->contains($obj)) {
                                $this->collPacks->append($obj);
                            }
                        }

                        $this->collPacksPartial = true;
                    }

                    return $collPacks;
                }

                if ($partial && $this->collPacks) {
                    foreach ($this->collPacks as $obj) {
                        if ($obj->isNew()) {
                            $collPacks[] = $obj;
                        }
                    }
                }

                $this->collPacks = $collPacks;
                $this->collPacksPartial = false;
            }
        }

        return $this->collPacks;
    }

    /**
     * Sets a collection of ChildPack objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $packs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setPacks(Collection $packs, ConnectionInterface $con = null)
    {
        /** @var ChildPack[] $packsToDelete */
        $packsToDelete = $this->getPacks(new Criteria(), $con)->diff($packs);


        $this->packsScheduledForDeletion = $packsToDelete;

        foreach ($packsToDelete as $packRemoved) {
            $packRemoved->setUser(null);
        }

        $this->collPacks = null;
        foreach ($packs as $pack) {
            $this->addPack($pack);
        }

        $this->collPacks = $packs;
        $this->collPacksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Pack objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Pack objects.
     * @throws PropelException
     */
    public function countPacks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPacksPartial && !$this->isNew();
        if (null === $this->collPacks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPacks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPacks());
            }

            $query = ChildPackQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collPacks);
    }

    /**
     * Method called to associate a ChildPack object to this object
     * through the ChildPack foreign key attribute.
     *
     * @param  ChildPack $l ChildPack
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addPack(ChildPack $l)
    {
        if ($this->collPacks === null) {
            $this->initPacks();
            $this->collPacksPartial = true;
        }

        if (!$this->collPacks->contains($l)) {
            $this->doAddPack($l);

            if ($this->packsScheduledForDeletion and $this->packsScheduledForDeletion->contains($l)) {
                $this->packsScheduledForDeletion->remove($this->packsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildPack $pack The ChildPack object to add.
     */
    protected function doAddPack(ChildPack $pack)
    {
        $this->collPacks[]= $pack;
        $pack->setUser($this);
    }

    /**
     * @param  ChildPack $pack The ChildPack object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removePack(ChildPack $pack)
    {
        if ($this->getPacks()->contains($pack)) {
            $pos = $this->collPacks->search($pack);
            $this->collPacks->remove($pos);
            if (null === $this->packsScheduledForDeletion) {
                $this->packsScheduledForDeletion = clone $this->collPacks;
                $this->packsScheduledForDeletion->clear();
            }
            $this->packsScheduledForDeletion[]= clone $pack;
            $pack->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collGroups collection loaded partially.
     */
    public function resetPartialGroups($v = true)
    {
        $this->collGroupsPartial = $v;
    }

    /**
     * Initializes the collGroups collection.
     *
     * By default this just sets the collGroups collection to an empty array (like clearcollGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroups($overrideExisting = true)
    {
        if (null !== $this->collGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = GroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGroups = new $collectionClassName;
        $this->collGroups->setModel('\Models\Group');
    }

    /**
     * Gets an array of ChildGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     * @throws PropelException
     */
    public function getGroups(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                // return empty collection
                $this->initGroups();
            } else {
                $collGroups = ChildGroupQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupsPartial && count($collGroups)) {
                        $this->initGroups(false);

                        foreach ($collGroups as $obj) {
                            if (false == $this->collGroups->contains($obj)) {
                                $this->collGroups->append($obj);
                            }
                        }

                        $this->collGroupsPartial = true;
                    }

                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    foreach ($this->collGroups as $obj) {
                        if ($obj->isNew()) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of ChildGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $groups A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ConnectionInterface $con = null)
    {
        /** @var ChildGroup[] $groupsToDelete */
        $groupsToDelete = $this->getGroups(new Criteria(), $con)->diff($groups);


        $this->groupsScheduledForDeletion = $groupsToDelete;

        foreach ($groupsToDelete as $groupRemoved) {
            $groupRemoved->setUser(null);
        }

        $this->collGroups = null;
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        $this->collGroups = $groups;
        $this->collGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Group objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Group objects.
     * @throws PropelException
     */
    public function countGroups(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroups());
            }

            $query = ChildGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collGroups);
    }

    /**
     * Method called to associate a ChildGroup object to this object
     * through the ChildGroup foreign key attribute.
     *
     * @param  ChildGroup $l ChildGroup
     * @return $this|\Models\User The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $l)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
            $this->collGroupsPartial = true;
        }

        if (!$this->collGroups->contains($l)) {
            $this->doAddGroup($l);

            if ($this->groupsScheduledForDeletion and $this->groupsScheduledForDeletion->contains($l)) {
                $this->groupsScheduledForDeletion->remove($this->groupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGroup $group The ChildGroup object to add.
     */
    protected function doAddGroup(ChildGroup $group)
    {
        $this->collGroups[]= $group;
        $group->setUser($this);
    }

    /**
     * @param  ChildGroup $group The ChildGroup object to remove.
     * @return $this|ChildUser The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) {
            $pos = $this->collGroups->search($group);
            $this->collGroups->remove($pos);
            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }
            $this->groupsScheduledForDeletion[]= clone $group;
            $group->setUser(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->name = null;
        $this->surname = null;
        $this->password = null;
        $this->email = null;
        $this->avatar_path = null;
        $this->password_reset_token = null;
        $this->email_confirm_token = null;
        $this->email_confirmed_at = null;
        $this->deleted_at = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collIdentities) {
                foreach ($this->collIdentities as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPackPermissions) {
                foreach ($this->collPackPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroupPermissions) {
                foreach ($this->collGroupPermissions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPacks) {
                foreach ($this->collPacks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collIdentities = null;
        $this->collPackPermissions = null;
        $this->collGroupPermissions = null;
        $this->collPacks = null;
        $this->collGroups = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildUser The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[UserTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Length(array ('max' => 32,'maxMessage' => 'Maximal username length is {{ limit }} characters.',)));
        $metadata->addPropertyConstraint('username', new NotBlank(array ('message' => 'Username should not be blank.',)));
        $metadata->addPropertyConstraint('username', new Uniqueness(array ('message' => 'Username already exists.',)));
        $metadata->addPropertyConstraint('username', new Regex(array ('pattern' => '/^[a-zA-Z0-9]*$/','match' => true,'message' => 'Username must contain only alphanumeric characters.',)));
        $metadata->addPropertyConstraint('email', new Length(array ('max' => 70,'maxMessage' => 'Maximal email address length is {{ limit }} characters.',)));
        $metadata->addPropertyConstraint('email', new NotBlank(array ('message' => 'Email address should not be blank.',)));
        $metadata->addPropertyConstraint('email', new Uniqueness(array ('message' => 'Email address is already used.',)));
        $metadata->addPropertyConstraint('email', new Email(array ('message' => 'Entered email address must be valid.',)));
        $metadata->addPropertyConstraint('password', new Length(array ('min' => 6,'max' => 60,'minMessage' => 'Password must contain at least {{ limit }} characters.','maxMessage' => 'Maximal password length is {{ limit }} characters.',)));
        $metadata->addPropertyConstraint('password', new NotBlank(array ('message' => 'Password should not be blank.',)));
        $metadata->addPropertyConstraint('name', new Length(array ('max' => 50,'maxMessage' => 'Maximal name length is {{ limit }} characters.',)));
        $metadata->addPropertyConstraint('surname', new Length(array ('max' => 50,'maxMessage' => 'Maximal surname length is {{ limit }} characters.',)));
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      ValidatorInterface|null $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
     */
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = new RecursiveValidator(
                new ExecutionContextFactory(new IdentityTranslator()),
                new LazyLoadingMetadataFactory(new StaticMethodLoader()),
                new ConstraintValidatorFactory()
            );
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;


            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collIdentities) {
                foreach ($this->collIdentities as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collPackPermissions) {
                foreach ($this->collPackPermissions as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collGroupPermissions) {
                foreach ($this->collGroupPermissions as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collPacks) {
                foreach ($this->collPacks as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collGroups) {
                foreach ($this->collGroups as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }

            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return     object ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
