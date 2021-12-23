<?php session_start();?>

<?php
if(isset($_GET['action'])){
    $do = $_GET['action'];
}else{
    $do = 'index';
}
?>

<?php if(isset($_SESSION['ID'])):?>
<?php include "config.php"?>
<?php include "includes/header.php"?>
<?php require "includes/navbar.php"?>

<?php if($do == 'index'):?>
<?php if(isset($_GET['open']) && $_GET['open'] == 'admin'): ?>
<h1 class="text-center">All Admin & Moderators </h1>
<?php else:?>
<h1 class="text-center">All Members </h1>
<?php endif?>
<?php
$role = isset($_GET['open']) && $_GET['open'] == 'admin' ? "!=3" : "=3";
$stmt = $con->prepare("SELECT * FROM users WHERE role $role");
$stmt->execute();
$users = $stmt->fetchAll();
?>
<div class="container">
    <a href="?action=create" class="btn btn-primary"><i class="fas fa-user-plus"></i> Add User</a>
    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">image</th>
                <th scope="col">userName</th>
                <th scope="col">Created_at</th>
                <th scope="col">Controll</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user):?>
            <tr>
                <th scope="row">1</th>
                <th><img src="img/uploads/<?= $user['img']?>" alt="" style="height: 10vh;"></th>
                <td><?= $user['username']?></td>
                <td><?= $user['created_at']?></td>
                <td>
                    <?php if($_SESSION["ROLE"] == 2): ?>
                    <a href="?action=show&userid=<?= $user['id']?>" class="btn btn-info"><i class="fas fa-eye"></i>
                        Show</a>
                    <?php else:?>
                    <a href="?action=show&userid=<?= $user['id']?>" class="btn btn-info"><i class="fas fa-eye"></i>
                        Show</a>
                    <a href="?action=edit&userid=<?= $user['id']?>" class="btn btn-warning"><i class="fas fa-edit"></i>
                        Edit</a>
                    <a href="?action=delete&userid=<?= $user['id']?>" class="btn btn-danger confirm"><i
                            class="fas fa-trash-alt"></i> Delete</a>
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
</div>
<?php elseif($do == 'create'):?>
<div class="container">
    <form method="post" action="?action=store" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">User Name</label>
            <input type="text" class="form-control" name="username">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Phone</label>
            <input type="number" class="form-control" name="phone">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Upload img</label>
            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php elseif($do == 'store'):?>
<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $imgName = $_FILES['image']['name'];
    $imgType = $_FILES['image']['type'];
    $imgTmp = $_FILES['image']['tmp_name'];
    $imgAllowedExtension = array("image/jpeg" , "image/jpg" , "image/png");
    $image = rand(0 , 1000) . $imgName;
    move_uploaded_file($imgTmp , "img/uploads/" .$image);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $phone = $_POST['phone'];

if(empty($username)){
    echo "Please add username";
}
elseif(empty($email)){
    echo "Please add email";
}
elseif(empty($password)){
    echo "Please add password";
}
elseif(empty($phone)){
    echo "Please add phone number";
}
else{
    $stmt = $con->prepare("INSERT INTO users (username , email , password , role , phone , img , created_at) 
    VALUES(? , ? , ? , '3' , ? , ? , now() )");
    $stmt->execute(array($username , $email , $password , $phone , $image));
    header("location:members.php");
    }
}
?>
<?php elseif($do == 'edit'):?>
<?php
    $userid = $_GET['userid'];
    $stmt = $con->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute(array($userid));
    $user = $stmt->fetch();
    $count = $stmt->rowCount();
?>
<?php if($count == 1):?>
<div class="container">
    <h1 class="text-center">Edit User</h1>
    <form method="POST" action="?action=update">
        <input type="text" class="form-control" name="userid" value="<?= $user['id']?>">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">User Name</label>
            <input type="text" class="form-control" name="username" value="<?= $user['username']?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" value="<?= $user['email']?>">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="newpassword">
            <input type="hidden" class="form-control" value="<?= $row['password']?>" name="oldpassword">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Phone</label>
            <input type="number" class="form-control" name="phone" value="<?= $user['phone']?>">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<?php else:?>
<script>
    window.history.back();
</script>
<?php endif?>

<?php elseif($do == 'update'):?>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $userid = $_POST['userid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password   = empty($_POST['newpassword'])?$_POST['oldpassword']:$_POST['newpassword'];
        $hashedpass = sha1($password);

        $stmt = $con->prepare("UPDATE users SET username = ? , password = ? , email = ? , phone = ? WHERE id=?");
        $stmt->execute(array($username , $hashedpass , $email , $phone , $userid));
    }
?>

<?php elseif($do == 'show'):?>
<?php
    $userid = $_GET['userid'];
    $stmt = $con->prepare("SELECT * FROM users WHERE `id`=?");
    $stmt->execute(array($userid));
    $user = $stmt->fetch();
?>
<div class="container">
    <h1 class="text-center"><?= $user['username'] ?> info</h1>
    <div class="userImg text-center">
    <img src="img/uploads/<?= $user['img']?>" alt="">
    </div>
    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Created At</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row"><?= $user['username'] ?></th>
                <td><?= $user['email'] ?></td>
                <td><?= $user['phone'] ?></td>
                <td><?= $user['created_at'] ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php elseif($do == "delete"):?>
<?php
    $userid = $_GET['userid'];
    $stmt =$con->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute(array($userid));
    header("location:members.php");
?>

<?php else:?>
<h1>404 page</h1>
<?php endif?>

<?php include "includes/footer.php"?>

<?php else:?>
<?php header("location:index.php")?>
<?php endif?>