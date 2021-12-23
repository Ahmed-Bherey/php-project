<?php session_start()?>
<?php include "includes/header.php"?>
<?php include "config.php"?>




<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = $_POST['email'];
    $password = sha1($_POST['adminpass']);

    $stmt = $con->prepare("SELECT * FROM users WHERE email=? AND password=? AND role !=3");
    $stmt->execute(array($email , $password ));
    $user = $stmt->fetch();
    $count = $stmt->rowCount();

if($count == 1){
    $_SESSION["ID"] = $user['id'];
    $_SESSION["USERNAME"] = $user['username'];
    $_SESSION["EMAIL"] = $user['email'];
    $_SESSION["ROLE"] = $user['role'];
    header("location:dashboard.php");
}else{
    echo "cheack username or password";
}
}

?>


<h1 class="text-center">Admin login</h1>
<div class="container">
    <form method="post" action="index.php">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="input">
            <span class="span"></span>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="adminpass">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>





<?php include "includes/footer.php"?>