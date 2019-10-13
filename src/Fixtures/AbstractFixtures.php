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
         * @var object The instance of an entity
         */
        protected $entity;



        /**
         * @var Faker
         */
        protected $faker;



        /**
         * @param EntityManager $manager
         * @param object $entity The instance of an entity
         * @param string $locale
         */
        public function __construct(EntityManager $manager, object $entity, string $locale)
        {
            $this->manager = $manager;
            $this->entity = $entity;
            $this->faker = Factory::create($locale);
        }



        /**
         * {@inheritDoc}
         */
        public function add ()
        {

        }
    }