<?php
// home feed file

require_once __DIR__ .  '/../classes/init.php';
require_once __DIR__ . "/./Post.php";
require_once __DIR__ . "/../forms/Validator.php";
require_once __DIR__ . "/../lib/createEmiji.php";

$user = $me;


$validator = new Validator();
list($errors, $data, $errorClass, $mainError, $msg, $csrf) = $validator->helpers();


$validator->methodPost(
    function (Validator $validator) {
        $validator->addRules(
            [
                "post" => ["required_without" => ["image", "video"]],
                "image" => ['is_file' => __DIR__ . "/images"],
                "video" => ['is_file' => __DIR__ . "/videos"],
                "username" => []
            ]
        )->addData($_POST)->validate();

        if ($validator->valid) {
            try {
                $post = Post::create(...$validator->valid_data);

                if (!$post) {
                    return new FormError("Something, went wrong! try again later ):");
                }

                header("Location: ./home.php?msg=Post crearted!");
                exit();
            } catch (FormError $e) {
                $validator->setMainError($e->getMessage());
            }
        }
    }
);

$posts = Post::getFriendsPosts($user->username);
$post_paginator = new Paginator();
$post_paginator->updateHasMore(count($posts));

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="<?php echo getUrl("/css/home.css");  ?>">
    <title>MeetUp MeetUs</title>
</head>

<body>
    <div class="container">
        <?php require '../menu/menu.php'; ?>
        <div class="postFormContainer">
            <?php echo $msg(); ?>
            <form action="" class="postForm formContainer" method="post" enctype="multipart/form-data">
                <?php echo $csrf(); ?>
                <input type="hidden" name="username" value="<?php echo $me->username; ?>">
                <div class="desc mainInput">
                    <label for="area">Any words <?php echo $me ? "'{$me?->username}'" : ""; ?>?</label>
                    <textarea autofocus name="post" data-emojiable="true" data-emoji-input="unicode" class=" <?php echo $errorClass('post'); ?>" id="area" placeholder="Write to share with friends (:"><?php echo $data('post'); ?></textarea>
                    <?php echo $errors('post'); ?>
                </div>
                <div class="filesWrapper">
                    <label for="image">Add Image</label>
                    <input data-tooltip="Post an image" class=" <?php echo $errorClass('image'); ?>" data-image-input accept="image/*" id="image" name="image" type="file" title="Picture" />
                    <?php echo $errors('image'); ?>
                    <label for="video">Add Video</label>
                    <input class=" <?php echo $errorClass('video'); ?>" data-video-input accept="video/*" id="video" type="file" name="video" title="Video">
                    <?php echo $errors('video'); ?>
                </div>
                <button type="submit" style="display: inline-block;max-width:max-content;">Post Now</button>
            </form>
        </div>

            <!-- print posts -->
            <?php require_once __DIR__ . "/./print_posts.php"; ?>
            <!-- end print posts -->
    </div>

    <script src="<?php echo getUrl("/js/comments.js");  ?>" defer></script>
    <script defer>
        window.addEventListener("DOMContentLoaded", e => {
            const imageInput = document.querySelector("[data-image-input]")
            const videoInput = document.querySelector("[data-video-input]")

            if (!videoInput || !imageInput) {
                return alert("Something is wrong!");
            }

            videoInput.addEventListener("change", e => {
                const vidElement = document.createElement("video")
                if (videoInput.files[0] && !vidElement.canPlayType(videoInput.files[0].type)) {
                    alert("Video file type not supported!")
                    // videoInput.setAttribute('value', '');
                    return videoInput.form.reset();
                }
                if (videoInput.files[0] && videoInput.files[0].size >= 41943040) {
                    alert("Choosen file is bigger than " + Math.round(41943040 / 1000000) + "MB");
                    return videoInput.form.reset();
                }
            })

            imageInput.addEventListener("change", e => {
                if (imageInput.files[0] && !(imageInput.files[0].type.startsWith("image/"))) {
                    alert("Image file type not supported!")
                    // videoInput.setAttribute('value', '');
                    return videoInput.form.reset();
                }
            })
        })
    </script>

    
</body>

</html>