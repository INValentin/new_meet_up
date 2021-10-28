<?php 

require_once __DIR__ . "/../classes/init.php";

require_once __DIR__ . "/../post/Post.php";
require_once __DIR__ . "/../forms/Validator.php";

$user = isset($_GET['user']) ? User::findOne($_GET['user']) : $me;

if (!$user){
    header("Location: ". getUrl("/404.php?url=" . current_url()));
    exit("Error");
}

$validator = new Validator();

$posts = Post::getUserPosts($user->username);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($user->username); ?> - Profile</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/profile.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css") ?>">
    <script src="<?php echo getUrl("/js/comments.js"); ?>" defer></script>
</head>
<body>
    <div class="container">
        <?php require_once __DIR__ . "/../menu/menu.php"; ?>
        <div class="profileContainer">
            <div class="profileBg">
                <div class="profileImg">
                    <img src="<?php echo getUrl("/images/{$user->profile_pic}"); ?>" alt="">
                </div>
                <div class="profileUserName"><?php echo $user->username; ?></div>
            </div>
            <div class="profileBtns">
                <button class="profileBtn">
                    <i class="fa fa-send"></i> 
                    Message
                </button>
                <?php if ($me->username === $user->username) : ?>
                <a href="#" class="btn successBtn profileBtn">
                    <i class="fa fa-edit"></i> 
                    Edit Profile
                </a>
                <?php endif; ?>
                <button class="profileBtn">
                    <i class="fa fa-group"></i> 
                    Friends
                </button>
            </div>
            
        </div>

        <!-- user posts -->

        <div class="postContainer">
            <?php echo !count($posts) ? "<p style='color:var(--white);text-align:center;'>No posts found</p>" : "" ?>
            <?php foreach ($posts as $post) : ?>
                <div class="userpost"  id="post<?php echo $post->id; ?>">
                    <div class="postHeader">
                        <a href="<?php echo getUrl("/friends/profile.php?user={$post->username}") ?>" class="postUser">
                            <div class="userProfile">
                                <img src="../images/<?php echo $post->owner()->profile_pic; ?>" />
                            </div>
                            <div class="userName"><?php echo $post->username; ?></div>
                        </a>
                        <div class="postTime">
                            <?php echo $date_obj->dateDiffStr($post->date); ?>
                        </div>
                    </div>
                    <div class="postBody">
                        <?php if ($post->post) : ?>
                            <div class="postText">
                                <?php echo $post->post; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($post->image && !$post->video) : ?>
                            <img class="postImg" src="<?php echo getUrl("/post/images/{$post->image}"); ?>" />
                            <?php endif; ?>
                        <?php if ($post->video) : ?>
                            <video class="postVideo" src="<?php echo  getUrl("/post/images/{$post->video}"); ?>" controls>
                            <?php endif; ?>
                    </div>
                    <div class="postFooter">
                        <form action="<?php echo getUrl("/post/like_post.php") ?>" method="post">
                        <input type="hidden" name="back_url" value="<?php echo getUrl("/friends/profile.php"); ?>">    
                        <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                            <input type="hidden" name="username" value="<?php echo $post->username; ?>">
                        <button class="postLike <?php echo $post->likedBy($user->username) ? "liked" : ""; ?> likeBtn">
                        <?php echo $post->likedBy($user->username) ? "UnLike" : "Like"; ?>
                        <span class="likeNum"> <?php echo $post->likes(); ?></span></button>
                    </form>
                        <button data-comment-toggle class="cmtBtn">Comment</button>
                    </div>
                    
                    <div data-current-user="<?php echo $user->username; ?>" data-post-id="<?php echo $post->id; ?>" class="commentContainer hide">
                        <label for="user-comment">Comment</label>
                        <form data-comment-form class="commentForm">
                            <?php echo $validator->getCsrfField()(); ?>
                            <input type="hidden" name="username" value="<?php echo $user->username; ?>">
                            <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                            <textarea autofocus name="comment" id="user-comment" placeholder="comment here.." data-comment-area></textarea>
                            <button data-comment-btn>Post</button>
                        </form>
                        <div class="commentList">
                            
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
        
    <!-- end user posts -->
</div>
<!-- comment template -->
    <template data-comment-template>
        <div class="comment">
            <div class="commentUserImg">
                <img src="<?php echo getUrl("/images/{$user->profile_pic}");  ?>" alt="comment">
            </div>
            <div class="commentContent">
                <span class="commentUserName"><?php echo $user->username; ?></span>
                <div class="commentBody">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id, eius.</div>
                <div class="commentBtns">
                    <button class="commentBtn likeBtn" data-comment-like>Like 1</button>
                </div>
            </div>
        </div>
    </template>
    <!-- comment template -->
    
    <!-- <script src="<?php echo getUrl("/js/comments.js"); ?>" defer></script> -->
</body>
</html>