<?php
require_once 'includes/all.php';
if(!is_logged_in()) {
    header("Location: signin.php");
    exit(0);
}

$db = connect_db();
$user_id = get_logged_in_user_id();
$user_groups = get_user_groups($db, $user_id);

if (!isset($_GET['id'])) {
    if (empty($user_groups)) {
        header("Location: form.php");
        exit(0);
    } else {
        $group = get_group($db, $user_groups[0]['id']);
    }
} else {
    $group = get_group($db, $_GET['id']);
}
$user_email = get_user($db, $user_id)['email'];

if (!$group) {
  header('Status: 404');
  die('no such group');
}
$course = get_course($db, $group['course_id']);
$users = get_group_members($db, $group['id']);
$is_member = is_member($db, $user_id, $group['id']);
if ($is_member) {
  $posts = get_group_posts($db, $group['id']);
}
$_SESSION['memgid'] = $group['id'];
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Study Group for <?= htmlspecialchars($course['department'].' '.$course['number']) ?> | Study Group Finder</title>
    <?php include 'includes/_head.html';?>
    <script src="js/jquery-1.12.1.min.js"></script>
    <script>
      function reload(id){
        self.location="group.php?id="+id;
      }
    </script>
    <style>
      article {
        background: #dadada;
        padding: 1em;
        border-radius: .5em;
        margin: 1em 0;
      }
      #spinner { display: none; }
    </style>
  </head>

  <body>
    <?php include 'includes/_nav.php';?>
    <?php
      if(isset($_SESSION['event'])) {
        echo '<div class="alert alert-success">'.$_SESSION['event'].'</div>';
        $_SESSION['event'] = NULL;
      }
      if(isset($_SESSION['flash_success'])) {
          foreach($_SESSION['flash_success'] as $output){
              echo '<div class="alert alert-success">'.htmlspecialchars($output).'</div>';
          }
          $_SESSION['flash_success'] = NULL;
      }
    ?>

    <div class="breadcrumbs">
      <a href="index.php">Home</a>
      » <a href="group.php?id=<?=$group['id']?>">Group: <?= htmlspecialchars($group['name']) ?></a>
    </div>

    <div class='row'>
      <div class='col-sm-3'>
        <label for='name'>Select the group</label>
        <select name='cgrp' id='greload' onChange="reload(this.value);" class='form-control'>
        <option class='cgop' value=''>Select Group</option>;
        <?php
          foreach($user_groups as $g){
            echo '<option value="'.htmlspecialchars($g['id']).'">'.htmlspecialchars($g['name'])."</option>\n";
          }
        ?>
        </select>
        <br>
      </div>
    </div>

    <h2>Study Group: <?= htmlspecialchars($group['name']) ?></h2>

    <div class="row">
    <div class="col-md-6">

    <dl class="dl-horizontal">
      <dt>Name
      <dd><?= htmlspecialchars($group['name']) ?>

      <dt>Group Message
      <dd><?= htmlspecialchars($group['blurb']) ?>

      <dt>Meeting Place
      <dd><?= htmlspecialchars($group['place']) ?>
        
       <dt>Campus</dt>
        <dd><?= htmlspecialchars($group['campus'])?></dd>

      <dt>Meeting Time
      <dd><?= htmlspecialchars(format_time($group['day'], $group['time'])) ?>

      <dt>Course
      <dd><?= htmlspecialchars($course['department']) ?>
          <?= htmlspecialchars($course['number']) ?>
          <?= htmlspecialchars($course['title']) ?>
      </dd>
      <br>
      <dt><dd>
              <?php if ($is_member) { ?>
      <a href="group_edit.php?id=<?= htmlspecialchars(urlencode($group['id'])) ?>" class="btn btn-primary btn-sm">
        <span class="glyphicon glyphicon-cog"></span> Edit
      </a>
      <?php } ?>

</dd></dt>
    </dl>

    </div>
    <div class="col-md-6">
    <h2 style="margin-top: 0px;">Members</h2>

    <ul class="list-group">
      <?php foreach ($users as $user) { ?>
        <li class="list-group-item">
          <a href="profile.php?id=<?= $user['id'] ?>">
            <?= htmlspecialchars($user['name']) ?>
          </a>
        </li>
      <?php } ?>
    </ul>

    <?php if ($is_member) { ?>
      <div>
       <?php $gid = $group['id'];
         echo "<a href='members_edit.php?id=$gid' class='btn btn-primary btn-sm'>";
         ?>
            <span class="glyphicon glyphicon-cog"></span> Edit
        </a>
      </div>
    <?php } ?>

    <?php if (!$is_member) { ?>
        <form action="members_entry.php" role='form' method='POST' name='mementry'>
            <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
            <div>
                <label for='name'>Join Group</label>
                <input type='hidden' class='form-control' name='addmemb' id='addmemb' value="<?php echo $user_email; ?>">
                <input type='hidden' class='form-control' name='removemem' id='removemem'>
            </div>
                <input type='submit' class='btn btn-primary' value='Join'>
        </form>
    <?php } ?>

    <?php if ($is_member) { ?>
      <form action= "EventCreate.php" method="GET">
          <h3> Create a Group Meeting </h3>
          <?php
            echo '<input type="hidden" name="group_id" value="'.urlencode($group['id']).'">';
          ?>
          <div class="form-group">
              <button class="btn btn-primary">Create Group Meeting </button>
          </div>
      </form>
</div>
</div>

      <h2>Discussion</h2>

      <form action="post.php" method="POST" id="discussion-form">
        <div id="discussion-errors">
        </div>
        <input type="hidden" name="group_id" value="<?= htmlspecialchars($group['id']) ?>">
        <div class="form-group">
          <textarea name="body" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
          <button class="btn btn-primary">Post</button>
          <span id="spinner" class="btn"><img src="images/spinner.gif"></span>
        </div>
      </form>

      <div id="discussions">
        <?php
          foreach ($posts as $post) {
            $date = new DateTime($post['created_at']);
            echo '<article id="post-'.$post['id'].'">';
            echo '<b>'.htmlspecialchars($post['user_name']).
              ' on '.htmlspecialchars($date->format("M j")).
              ' at '.htmlspecialchars($date->format("H:i")).
              '</b>';
            echo '<p>'.htmlspecialchars($post['body']).'</p>';
            echo '</article>';
          }
        ?>
      </div>

    <script>
      $("#discussion-form").submit(function(event) {
        var that = this;
        var body = this.elements.body.value;
        event.preventDefault();
        if (!body) {
          return;
        }
        $.ajax({
          url: "post_ajax.php",
          method: "POST",
          data: {
            "group_id": <?= json_encode($group['id']) ?>,
            "body": body,
          },
          dataType: "json",
          success: function(data) {
            that.elements.body.value = "";
            $("#spinner").hide();
            if (data.error) {
              $('<div class="alert alert-warning"></div>')
                .text(data.error)
                .appendTo( $("#discussion-errors").empty() );
            } else {
              var hi = $("<div>").html(data.post).first();
              console.log(hi);
              $("#discussions").prepend( hi);
            }
          },
          error: function(xhr, status) {
              $("#spinner").hide();
              $('<div class="alert alert-warning"></div>')
                .text(status)
                .appendTo( $("#discussion-errors").empty() );
          },
        });
        $("#spinner").show();
      });
    </script>

    <?php } ?>

    <?php include 'includes/_footer.php';?>
  </body>
</html>
