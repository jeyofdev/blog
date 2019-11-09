<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\Auth\User;
    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Table\CommentTable;
    use jeyofdev\php\blog\Table\CommentUserTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;
    use jeyofdev\php\blog\Url;


    /**
     * Manage the controller of the comments
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentController extends AbstractController
    {
        /**
         * Delete a comment
         *
         * @return void
         */
        public function delete () : void
        {
            $tableComment = new CommentTable($this->connection);
            $tableCommentUser = new CommentUserTable($this->connection);
            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            /**
             * @var Comment
             */
            $comment = $tableComment->find(["id" => $id]);

            // check that the user is authorized to delete the comment
            $user = new User($this->router, $this->session, $tableUser, $tableRole);
            $user->actionIsAuthorizedForComment($comment, $tableCommentUser, "post", "You do not have permission to delete this comment", "delete");

            $tableComment->delete(["id" => $id]);

            // flash message
            $this->session->setFlash("The comment has been deleted", "success", "mt-5");

            // redirect to the post
            $post = $this->session->read("post");
            $url = $this->router->url("post", ["slug" => $post["slug"], "id" => $post["id"]]) . "?comment=1&delete=1";
            Url::redirect(301, $url);
        }
    }