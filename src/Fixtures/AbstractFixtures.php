<?php

    namespace jeyofdev\php\blog\Fixtures;


    use Faker\Factory;
    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the fixtures
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class AbstractFixtures implements FixturesInterface
    {
        /**
         * @var EntityManager
         */
        protected $manager;



        /**
         * @var Faker
         */
        protected $faker;



        /**
         * @param EntityManager $manager
         * @param string $locale
         */
        public function __construct(EntityManager $manager, string $locale)
        {
            $this->manager = $manager;
            $this->faker = Factory::create($locale);
        }



        /**
         * {@inheritDoc}
         */
        public function add ()
        {

        }
    }