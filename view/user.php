<?php if($user->id) { echo "Update User Details"; } else {echo "Create New User"; } ?>

<form action='<?php if($user->id) { echo "/"."$user->id"."/?method=update"; } else {echo "/?method=create"; } ?>' method='post'>
    Name: <input type="text" name="name" value="<?php echo $user->name; ?>" ><br>
    Email: <input type="text" name="email" value="<?php echo $user->email; ?>"><br>
    
    <input type="submit" value="<?php if($user->id) { echo "Update"; } else {echo "Create"; } ?>">
</form>