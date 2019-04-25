<?php

/**
 * Class Database database class to insert and get information
 */
class Database
{
    /**
     * Connect to the database
     */
    public function connect()
    {
        if($_SERVER['HTTP_HOST'] == "dkovalevich.greenriverdev.com") {
            require_once('/home/dkovalev/final-config2.php');
        }

        else if($_SERVER['HTTP_HOST'] == "bskar.greenriverdev.com") {
            require_once('/home/bskargre/final-config.php');
        }

        else if($_SERVER['HTTP_HOST'] == "http://yvainilovich.greenriverdev.com") {
            require_once('/home/yvainilo/final-config.php');
        }

        try {
            //instantiate a database object
            $GLOBALS['dbh'] = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getHomepagePosts()
    {
        global $dbh;

        $sql = "SELECT * FROM posts
                ORDER BY date DESC
                LIMIT 5";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $posts = array();

        foreach($results as $result) {
            $postID = $result['post_id'];
            $categoryID = $result['category_id'];
            $title = $result['title'];
            $body = $result['body'];
            $author = $result['author'];
            $numLikes = $result['num_likes'];
            $numComments = $result['num_comments'];
            $image = $result['image'];
            $date = $result['date'];
            $posts[] = new Post($postID, $categoryID, $title, $body, $author, $numLikes,
                $numComments, $image, $date);
        }

        return $posts;
    }

    /**
     * @param $id param ID used to get information from database (travel => 1, events => 2, life-style => 3)
     * @return array returns a post object array with top 25 posts sorted by date
     */
    public function getPostInfo($id)
    {
        global $dbh;

        $sql = "SELECT * FROM posts
                WHERE category_id = :id
                ORDER BY date DESC
                LIMIT 12";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':id', $id, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }


    public function getPostsLiked($user_id)
    {
        global $dbh;

        $sql = "SELECT post_id FROM posts_liked
                WHERE user_id = :user_id";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $userLikes = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $userLikes;
    }

    public function checkAdminSignin($email, $pass)
    {
        global $dbh;
        if(strtolower($email) === "admin" && $pass === "adminmilana2019") {
            return true;
        }
        return false;
    }

    /**
     * @param $email the email from the user to check the database for availability
     * @return bool returns true if there are no emails matching the email
     * given by the user in the database
     */
    public function isEmailAvailable($email)
    {
        global $dbh;

        $sql = "SELECT * FROM users
                WHERE email = :email";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':email', $email, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return sizeof($results) == 0;
    }

    public function createUser($name, $email, $pass)
    {
        global $dbh;

        $email = strtolower($email);
        $sql = "INSERT INTO users(fullname, email, password, admin, admin_view)
                VALUES(:fullname, :email, :password, 0, 0)";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':fullname', $name, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', SHA1($pass), PDO::PARAM_STR);


        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function checkSignin($email, $pass)
    {
        global $dbh;

        $sql = "SELECT user_id, fullname, email, password, admin, admin_view FROM users
                WHERE email = :email AND password = :password";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':password', SHA1($pass), PDO::PARAM_STR);


        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if(!empty($results)) {
            //construct a new user with values and a signed in as true boolean
            $user = new User();

            $user->setId($results['user_id']);
            $user->setEmail($results['email']);
            $user->setName($results['fullname']);
            $user->setAdmin($results['admin']);
            $user->setAdminView($results['admin_view']);
            return $user;
        }
        return false;
    }

    /**
     * Determines whether the user has already liked a post and then updates the database accordingly
     * (num_likes decrements if it is already liked by this user or num_likes increments if it is not liked
     * by this user)
     * @return bool boolean whether the email already exists in the database or not
     */
    public function emailExists($email)
    {
        global $dbh;

        $sql = "SELECT user_id FROM users WHERE email = :email";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':email', $email, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if(!empty($results)) {
            return true;
        }
        return false;
    }

    public function updateNumLikes($post_id, $user_id)
    {
        global $dbh;

        $sql = "SELECT posts_liked.user_id, posts_liked.post_id, posts.num_likes 
        FROM posts_liked INNER JOIN posts ON posts_liked.post_id = posts.post_id
        WHERE posts_liked.post_id = :post_id AND posts_liked.user_id = :user_id";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);
        $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);


        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        //if the logged in user has not liked this post before update the database
        if(empty($results)) {
            $sql = "INSERT INTO posts_liked (user_id, post_id)
                    VALUES(:user_id, :post_id)";

            $statement = $dbh->prepare($sql);

            $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);

            $statement->execute();
            $arr = $statement->errorInfo();
            if(isset($arr[2])) {
                print_r($arr[2]);
            }

            $sql = "UPDATE posts
                    SET num_likes = num_likes + 1
                    WHERE post_id = :post_id";

            $statement = $dbh->prepare($sql);

            $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);

            $statement->execute();
            $arr = $statement->errorInfo();
            if(isset($arr[2])) {
                print_r($arr[2]);
            }
        }

        else {
            $sql = "DELETE FROM posts_liked
                    WHERE user_id = :user_id AND post_id = :post_id";

            $statement = $dbh->prepare($sql);

            $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_STR);

            $statement->execute();
            $arr = $statement->errorInfo();
            if(isset($arr[2])) {
                print_r($arr[2]);
            }

            $sql = "UPDATE posts
                    SET num_likes = num_likes - 1
                    WHERE post_id = :post_id";

            $statement = $dbh->prepare($sql);

            $statement->bindValue(':post_id', $post_id, PDO::PARAM_STR);

            $statement->execute();
            $arr = $statement->errorInfo();
            if(isset($arr[2])) {
                print_r($arr[2]);
            }
        }
    }

    public function getSinglePost($postid)
    {
        global $dbh;

        $sql = "SELECT * FROM `posts` WHERE post_id = :post_id;";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':post_id', $postid, PDO::PARAM_STR);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $postID = $result['post_id'];
        $categoryID = $result['category_id'];
        $title = $result['title'];
        $body = $result['body'];
        $author = $result['author'];
        $numLikes = $result['num_likes'];
        $numComments = $result['num_comments'];
        $image = $result['image'];
        $date = $result['date'];

        $post = new Post($postID, $categoryID, $title, $body, $author, $numLikes,
            $numComments, $image, $date);
        return $post;
    }

    public function getPopularPosts()
    {
        global $dbh;

        $sql = "SELECT * FROM posts ORDER BY num_likes DESC LIMIT 5";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $popularPost = array();

        foreach($results as $result) {
            $postID = $result['post_id'];
            $categoryID = $result['category_id'];
            $title = $result['title'];
            $body = $result['body'];
            $author = $result['author'];
            $numLikes = $result['num_likes'];
            $numComments = $result['num_comments'];
            $image = $result['image'];
            $date = $result['date'];
            $popularPost[] = new Post($postID, $categoryID, $title, $body, $author, $numLikes,
                $numComments, $image, $date);
        }
        return $popularPost;
    }

    public function getMembers()
    {
        global $dbh;

        $sql = "SELECT * FROM users";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function getPosts()
    {
        global $dbh;

        $sql = "SELECT * FROM posts";

        $statement = $dbh->prepare($sql);

        $statement->execute();
        $arr = $statement->errorInfo();
        if(isset($arr[2])) {
            print_r($arr[2]);
        }

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function createPost($title, $body, $author, $image, $category_id)
    {
        global $dbh;

        $body = htmlentities($body);
        $body = str_replace("<","&lt;", $body);
        $body = str_replace(">","&gt;", $body);

        $sql = "INSERT INTO `posts`(`category_id`, `title`, `body`, `image`, `author`, `num_likes`, `num_comments`, `date`) VALUES (:category, :title, :body, :image, :author, 0, 0, now())";

        $statement = $dbh->prepare($sql);

        //bind all the parameters
        $statement->bindValue(':category', $category_id, PDO::PARAM_STR);
        $statement->bindValue(':title', $title, PDO::PARAM_STR);
        $statement->bindValue(':body', $body, PDO::PARAM_STR);
        $statement->bindValue(':author', $author, PDO::PARAM_STR);
        $statement->bindValue(':image', $image, PDO::PARAM_STR);

        $statement->execute();

        $arr = $statement->errorInfo();

        if(isset($arr[2])) {
            print_r($arr[2]);
        }
    }

    public function formatDate($date)
    {
        $month = date('M', strtotime($date));
        $day = date('t', strtotime($date));
        $year = date('Y', strtotime($date));

        return $month . " " . $day . ", " . $year;
    }
}