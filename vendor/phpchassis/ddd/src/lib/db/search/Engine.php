<?php
/**
 * Created by PhpStorm.
 * User: tharwat
 * Date: 4/27/2019
 * Time: 07:19
 */
namespace phpchassis\lib\db\search;

/**
 * Class (Search) Engine
 *
 * @package phpchassis-ddd\lib\db\search
 */
class Engine {

    const ERROR_PREPARE = 'ERROR: unable to prepare statement';
    const ERROR_EXECUTE = 'ERROR: unable to execute statement';
    const ERROR_COLUMN  = 'ERROR: column name not on list';
    const ERROR_OPERATOR= 'ERROR: operator not on list';
    const ERROR_INVALID = 'ERROR: invalid search criteria';

    protected $connection;
    protected $table;
    protected $columns;
    protected $mapping;
    protected $statement;
    protected $sql = '';

    protected $operators = [
        'LIKE'     => 'Equals',
        '<'        => 'Less Than',
        '>'        => 'Greater Than',
        '<>'       => 'Not Equals',
        'NOT NULL' => 'Exists',
    ];

    public function __construct(Connection $connection, $table, array $columns, array $mapping) {

        $this->connection  = $connection;
        $this->setTable($table);
        $this->setColumns($columns);
        $this->setMapping($mapping);
    }

    /**
     * prepareStatement
    */
    public function prepareStatement(Criteria $criteria) {

        $this->sql = 'SELECT * FROM ' . $this->table . ' WHERE ';
        $this->sql .= $this->mapping[$criteria->key()] . ' ';

        switch ($criteria->operator()) {
            case 'NOT NULL' :
                $this->sql .= ' IS NOT NULL OR ';
                break;
            default :
                $this->sql .= $criteria->operator() . ' :' . $this->mapping[$criteria->key()] . ' OR ';
        }
        $this->sql = substr($this->sql, 0, -4) . ' ORDER BY ' . $this->mapping[$criteria->key()];
        $statement = $this->connection->pdo->prepare($this->sql);
        return $statement;
    }

    /**
     * The search() method accepts a Criteria object as an argument. This ensures that we have an item key and operator 
     * at a minimum. To be on the safe side, we add an if() statement to check these properties
     */
    public function search(Criteria $criteria) {

        if (empty($criteria->key()) || empty($criteria->operator())) {
            yield ['error' => self::ERROR_INVALID];
            return false;
        }
        try {
            if (!$statement = $this->prepareStatement($criteria)) {
                yield ['error' => self::ERROR_PREPARE];
                return false;
            }

            /*
            Build an array of parameters that will be supplied to execute().
            The key represents the database column name that was used as a placeholder
            in the prepared statement. 
            Note: Instead of using =, we use the LIKE %value% construct:
            */

            $params = array();

            switch ($criteria->operator()) {
                case 'NOT NULL' :
                    // do nothing: already in statement
                    break;
                case 'LIKE' :
                    $params[$this->mapping[$criteria->key()]] = '%' . $criteria->item() . '%';
                    break;
                default :
                    $params[$this->mapping[$criteria->key()]] =
                        $criteria->item();
            }
            $statement->execute($params);

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                yield $row;
            }
        }
        catch (Throwable $e) {
            error_log(__METHOD__ . ':' . $e->getMessage());
            throw new Exception(self::ERROR_EXECUTE);
        }
        return true;
    }


    /**
     * Getter/Setter for columns
     *
     * @param null $columns
     * @return string
     */
    public function columns($columns = null) {
        if($columns === null) {
            return $this->columns;
        }
        else {
            $this->columns = $columns;
        }
    }

    // TODO these getters / setters
    // $connection
    // $table
    // $mapping
    // $statement
}