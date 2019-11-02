<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Role;
    use jeyofdev\php\blog\Entity\User;


    /**
     * Manage the queries of the user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "user";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = User::class;



        /**
         * Get the user from a post
         *
         * @return User
         */
        public function findUser (array $params) : User
        {
            $sql = "SELECT u.*
                FROM post_user AS pu
                JOIN {$this->tableName} AS u
                ON pu.user_id = u.id
                WHERE pu.post_id = :id
            ";

            $query = $this->prepare($sql, $params);

            return $this->fetch($query);
        }



        /**
         * Get the user corresponding to each post
         *
         * @param string $ids The ids of the posts of the current page
         * @return User[]
         */
        public function findUserByPosts (string $ids)
        {
            $sql = "SELECT u.*, pu.post_id
                FROM post_user AS pu
                JOIN {$this->tableName} AS u
                ON u.id = pu.user_id
                WHERE pu.post_id IN ({$ids})
            ";

            $query = $this->query($sql);
            
            return $this->fetchAll($query);
        }



        /**
         * Create a new user
         *
         * @param User $user
         * @param Role $role
         * @return self
         */
        public function createUser (User $user, Role $role) : self
        {
            $this->addUser($user, $role);
            $this->attachRole($user, $role);

            return $this;
        }



        /**
         * Add a new user
         *
         * @param User $user
         * @return self
         */
        public function addUser (User $user) : self
        {
            $this->create([
                "username" => $user->getUsername(),
                "password" => $user->getPassword(),
                "slug" => $user->getSlug()
            ]);

            $user->setId((int)$this->connection->lastInsertId());

            return $this;
        }



        /**
         * Associate a user with a role
         *
         * @param User $user
         * @param Role $role
         * @return self
         */
        public function attachRole (User $user, Role $role) : self
        {
            $this->create([
                "user_id" => $user->getId(),
                "role_id" => $role->getId()
            ], "user_role");

            return $this;
        }



        /**
         * Associate a user with a post
         *
         * @return void
         */
        public function addToPost (int $postId, int $userId) : void
        {
            $params = [
                "post_id" => $postId,
                "user_id" => $userId
            ];
            $set = $this->setQueryParams($params);

            $sql = "INSERT INTO post_user SET $set[0]";
            $this->prepare($sql, $set[1]);
        }
    }