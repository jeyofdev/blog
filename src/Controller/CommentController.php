<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\Table\CommentTable;
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

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            $tableComment->delete(["id" => $id]);

            // flash message
            $this->session->setFlash("The comment has been deleted", "success", "mt-5");

            // redirect to the post
            $post = $this->session->read("post");
            $url = $this->router->url("post", ["slug" => $post["slug"], "id" => $post["id"]]) . "?comment=1&delete=1";
            Url::redirect(301, $url);
        }
    }