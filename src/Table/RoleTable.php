<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Role;
    use jeyofdev\php\blog\Entity\UserRole;


    /**
     * Manage the queries of the role table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class RoleTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "role";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = Role::class;



        /**
         * Get the role of an user
         *
         * @return Role
         */
        public function findRole (array $params) : Role
        {
            $sql = "SELECT r.*, ur.user_id
                FROM user_role AS ur
                JOIN role AS r
                ON r.id = ur.role_id
                WHERE ur.user_id = :id
            ";

            $query = $this->prepare($sql, $params);

            return $this->fetch($query);
        }
    }