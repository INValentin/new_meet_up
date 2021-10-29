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
        <?php require_once __DIR__ . "/../post/print_posts.php"; ?>
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