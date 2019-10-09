<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Entity\Post;


    /**
     * Add the fixtures in the 'post' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostFixtures extends AbstractFixtures
    {
        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $post = new Post($this->database);

            for ($i = 0; $i < 20; $i++) { 
                $this->connection->exec("INSERT INTO {$post->getTableName()} SET 
                    name = '{$this->faker->sentence(4, true)}',
                    slug = '{$this->faker->slug}',
                    content = '{$this->faker->paragraphs(rand(5, 20), true)}',
                    created_at = '{$this->faker->dateTimeBetween('-3 years', 'now')->format("Y-m-d H:i:s")}'
                ");
            }

            return $this;
        }
    }