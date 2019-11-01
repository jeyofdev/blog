<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Entity\Post;


    /**
     * Manage the queries of the comment table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "comment";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = Comment::class;



        /**
         * Get all comments from a post
         *
         * @return Comment[]
         */
        public function findCommentsByPost (array $params)
        {
            $sql = "SELECT c.*
                FROM post_comment AS pc
                JOIN {$this->tableName} AS c ON pc.comment_id = c.id
                WHERE pc.post_id = :id
            ";

            $query = $this->prepare($sql, $params);

            return $this->fetchAll($query);
        }



        /**
         * Get all comments paginated from a post
         *
         * @return Comment[]
         */
        public function findCommentsPaginated (int $postId, int $perPage, string $orderBy = "id", string $direction = "ASC")
        {
            $params = ["id" => $postId];
            $direction = strtoupper($direction);

            if ($this->checkIfValueIsAllowed("orderBy", $orderBy, $this->columns)) {
                if ($this->checkIfValueIsAllowed("direction", $direction, self::DIRECTION_ALLOWED)) {
                    // $sqlComments = "SELECT * FROM {$this->tableName} WHERE postORDER BY $orderBy $direction";
                    $sqlComments = $sqlComments = "SELECT c.*
                        FROM post_comment AS pc
                        JOIN {$this->tableName} AS c ON pc.comment_id = c.id
                        WHERE pc.post_id = :id
                    ";

                    $sqlCount = "SELECT COUNT(c.id)
                    FROM post_comment AS pc
                    JOIN {$this->tableName} AS c ON pc.comment_id = c.id
                    WHERE pc.post_id = :id
                ";

                    $this->pagination = new Pagination($this->connection, $sqlComments, $sqlCount, $this, $perPage);

                    return $this->pagination->getItemsPaginated($params);
                }
            }
        }



        /**
         * Create a new comment
         *
         * @param Comment $comment
         * @param Post $post
         * @return self
         */
        public function createComment (Comment $comment, Post $post) : self
        {
            $this->create([
                "username" => $comment->getUsername(),
                "content" => $comment->getContent()
            ]);

            $comment->setId((int)$this->connection->lastInsertId());

            $this->attachPost($comment, $post);

            return $this;
        }



        /**
         * Update a comment
         *
         * @param Comment $comment
         * @return self
         */
        public function updateComment (Comment $comment) : self
        {
            $this
                ->update([
                    "username" => $comment->getUsername(),
                    "content" => $comment->getContent()
                ], ["id" => $comment->getId()]);

            return $this;
        }



        /**
         * Associate a comment with the current post
         *
         * @param Comment $comment
         * @param Post $post
         * @return self
         */
        public function attachPost (Comment $comment, Post $post) : self
        {
            $this->create([
                "comment_id" => $comment->getID(),
                "post_id" => $post->getId()
            ], "post_comment");

            return $this;
        }
    }