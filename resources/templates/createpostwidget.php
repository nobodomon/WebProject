<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<form action="submitPost.php" method="post" class="comment-area-box mb-3">


    <label for="title" class="mt-2 mb-2 button-looking-text">Title:</label>
    <div class="input-group">
        <input class="form-control" type="text" onkeyup="titleCharacterCount()" maxlength="255" id="title" name="title" required placeholder="Enter title"> 
        <span class="btn btn-dark text-white" id="title-label">0/255</span>
    </div>
    <span class="input-icon">
        <label for="content" class="mt-2 mb-2 button-looking-text">Content:</label>
        <textarea class="form-control" id="content" name="content" placeholder="Write something..."></textarea>
    </span>
    <div class="comment-area-btn">
        <label for="interestType" class="mt-2 mb-2 button-looking-text">Interest Tags: </label>
        <div  class="input-group">
            <input class="flex-grow-1 form-control" type="text" id="searchTagBox" onkeyup="searchUpdate()" placeholder="search for tag...">
        </div>
        <div class="d-flex interestTagGrp p-2" id="interestTagGrp">
            <?php while ($row = $categoriesResults->fetch_array(MYSQLI_NUM)) { ?> 
                <input type="checkbox" class="btn-check m-1" name="interest[]" id="btn-check<?php echo $row[0] ?>" autocomplete="off" value="<?php echo $row[0] ?>"/>
                <label class="btn btn-outline-dark m-1" for="btn-check<?php echo $row[0] ?>"><?php echo $row[1] ?></label>
            <?php } ?>
        </div>
        <label for="postType" class="mt-2 mb-2 button-looking-text">Post Privacy: </label>
        <div class="input-group d-flex">
            <select id="postType" name="postType" class="form-select form-select-sm" aria-label=".form-select-lg postPrivacy">
                <option selected value="0">Public</option>
                <option value="1">Followers Only</option>
                <option value="2">Subscribers only</option>
            </select>
            <div class="float-end">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Post</button>
            </div>
        </div>
    </div>
</form>

