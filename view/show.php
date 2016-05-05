<h3>User Details</h3>

<p>User ID: <?php echo $user->id ?></p>
<p>User Name: <?php echo $user->name ?></p>
<p>User Email: <?php echo $user->email ?></p>
<a href=<?php echo '/'.$user->id.'/edit'; ?>>Edit</a>
<a href="/">Home</a>
<p></p>
<form action='/<?php echo $user->id ?>?method=delete' method='post'>
    <input type="submit" value="Delete">
</form>