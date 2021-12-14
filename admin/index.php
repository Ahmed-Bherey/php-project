<?php include "includes/header.inc"?>
<?php include "config.php"?>




<?php
// if($_SERVER['REQUEST_METHOD'] == "POST"){
//     $adminusername = $_POST['adminusername'];
//     $adminpassword = $_POST['adminpassword'];
//     $hashedhpass = sha1($adminpassword);

//     $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=?");
//     $stmt->execute(array($adminusername , $hashedhpass ));
//     $row = $stmt->fetch();
//     $count = $stmt->rowCount();

// if($count == $in_DB){
//     $_SESSION['USER_NAME'] = $adminusername;
//     $_SESSION['USER_ID'] = $row['user_id'];
//     $_SESSION['FULL_NAME'] = $row['fullname'];
//     $_SESSION['GROUB_ID'] = $row['groupid'];
//     header("location:dashboard.php");
// }else{
//     echo "cheack username or password";
// }
// }

?>


<h1 class="text-center">Admin login</h1>
<div class="container">
    <form method="post" action="index.php">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" name="adminusername" id="input">
            <span class="span"></span>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" name="adminpassword">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>





<?php include "includes/footer.php"?>