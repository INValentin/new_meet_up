<!-- prints posts  -->
<!-- should be placed on a page where posts array is present -->

<div class="postContainer">

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
                        <video class="postVideo" src="<?php getUrl("/post/videos/{$post->video}"); ?>" controls>
                        <?php endif; ?>
                </div>
                <div class="postFooter">
                    <form action="like_post.php" method="post">
                        <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                        <input type="hidden" name="username" value="<?php echo $me->username; ?>">
                    <button class="postLike likeBtn <?php echo boolval($post->likedBy($me->username)) ? "liked" : ""; ?> ">
                    <i class="fa <?php echo $post->likedBy($me->username) ? "fa-thumbs-up" : "fa-thumbs-o-up"; ?>"></i>
                    <span class="likeNum"> <?php echo $post->likes(); ?></span></button>
                </form>
                    <button data-comment-toggle class="cmtBtn"><i class="fa fa-commenting-o"></i></button>
                </div>
                
                <div data-current-user="<?php echo $me->username; ?>" data-post-id="<?php echo $post->id; ?>" class="commentContainer hide">
                    <label for="user-comment">Comment</label>
                    <form data-comment-form class="commentForm">
                        <?php echo $validator->getCsrfField()(); ?>
                        <input type="hidden" name="username" value="<?php echo $me->username; ?>">
                        <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                        <div class="inputContainer">
                            <textarea autofocus name="comment" data-emojiable="true" data-emoji-input="unicode" id="user-comment" placeholder="comment here.." data-comment-area></textarea>
                        </div>
                        <button data-comment-btn><i class="fa fa-send"></i></button>
                    </form>
                    <div class="commentList">
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
                        </div>
        <!-- USED IN JavaScript NOT SHOWN IN HTML comment template -->
            <template data-comment-template>
                <div class="comment">
                    <div class="commentUserImg">
                        <img src="<?php echo getUrl("/images/{$me->profile_pic}");  ?>" alt="comment">
                    </div>
                    <div>
                    <div class="commentContent">
                        <span class="commentUserName"><?php echo $me->username; ?></span>
                        <div class="commentBody">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id, eius.</div>
                    </div>
                    <div class="commentBtns">
                        <button class="commentBtn likeBtn" data-comment-like>Like 1</button>
                    </div>
                    </div>
                </div>
            </template>
        <!-- comment template -->