<?php

    namespace jeyofdev\php\blog\Fixtures;


    use Faker\Factory;
    use jeyofdev\php\blog\Database\Database;


    /**
     * Manage the fixtures
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class AbstractFixtures implements FixturesInterface
    {
        /**
         * @var Database
         */
        protected $database;



        /**
         * @var PDO
         */
        protected $connection;



        /**
         * @var Faker
         */
        protected $faker;



        /**
         * @param Database $database
         * @param string $locale
         */
        public function __construct(Database $database, string $locale)
        {
            $this->database = $database;
            $this->connection = $this->database->getConnection($this->database->getDb_name());
            $this->faker = Factory::create($locale);
        }



        /**
         * {@inheritDoc}
         */
        public function add ()
        {

        }
    }