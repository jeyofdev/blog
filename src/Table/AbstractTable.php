<?php

    namespace jeyofdev\php\blog\Table;


    use PDO;
    use PDOStatement;


    /**
     * Manage the standard queries
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractTable implements TableInterface, QueryInterface
    {
        /**
         * @var PDO
         */
        protected $connection;



        /**
         * The name of the table
         *
         * @var string
         */
        protected $table;


        /**
         * The name of the class
         *
         * @var object Instance of type table (ex PostTable)
         */
        protected $className;



        /**
         * @var PDOStatement
         */
        protected $query;



        /**
         * @param PDO $connection
         */
        public function __construct (PDO $connection)
        {
            $this->connection = $connection;
        }



        /**
         * {@inheritDoc}
         */
        public function find (array $params, $fetchMode = PDO::FETCH_CLASS)
        {
            $paramsKeys = [];
            $paramsValues = [];
            foreach ($params as $k => $v) {
                $paramsKeys[] = "$k = :$k";
                $paramsValues[] = "$k = $v";
            }

            $paramsKeys = implode(", ", $paramsKeys);
            $paramsValues = implode(", ", $paramsValues);

            $sql = "SELECT * FROM {$this->table} WHERE $paramsKeys";
            $query = $this->prepare($sql, $params, $fetchMode);

            return $this->fetch($query);
        }



        /**
         * {@inheritDoc}
         */
        public function findAllBy (?string $orderBy = null, string $direction = "ASC", ?int $limit = null, ?int $offset = null, ?int $fetchMode = PDO::FETCH_CLASS) : array
        {
            $sql = "SELECT * FROM {$this->table}";

            if (!is_null($orderBy)) {
                $direction = strtoupper($direction);
                $sql .= " ORDER BY $orderBy $direction";
            }

            if (!is_null($limit)) {
                $sql .= " LIMIT $limit";
            }

            if (!is_null($offset)) {
                $sql .= " OFFSET $offset";
            }
            
            $query = $this->query($sql, $fetchMode);

            return $this->fetchAll($query);
        }



        /**
         * {@inheritDoc}
         */
        public function countAll (string $column, int $fetchMode = PDO::FETCH_NUM) : int
        {
            $sql = "SELECT COUNT($column) FROM {$this->table}";
            $query = $this->query($sql, $fetchMode);

            return $this->fetch($query)[0];
        }



        /**
         * {@inheritDoc}
         */
        public function query (string $sql, int $fetchMode) : PDOStatement
        {
            $this->query = $this->connection->query($sql);
            $this->setFetchMode($fetchMode);

            return $this->query;
        }



        /**
         * {@inheritDoc}
         */
        public function prepare (string $sql, array $params = [], int $fetchMode) : PDOStatement
        {
            $this->query = $this->connection->prepare($sql);
            $this->query->execute($params);
            $this->setFetchMode($fetchMode);

            return $this->query;
        }



        /**
         * {@inheritDoc}
         */
        public function fetch ()
        {
            return $this->query->fetch();
        }



        /**
         * {@inheritDoc}
         */
        public function fetchAll () : array
        {
            return $this->query->fetchAll();
        }



        /**
         * {@inheritDoc}
         */
        public function setFetchMode (int $fetchMode) : void
        {
            if ($fetchMode === PDO::FETCH_CLASS) {
                $this->query->setFetchMode($fetchMode, $this->className);
            } else {
                $this->query->setFetchMode($fetchMode);
            }
        }
    }