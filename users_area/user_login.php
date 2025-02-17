<?php
include('../includes/connect.php');
include('../functions/common_function.php');
@session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../css/user_login.css">
    <style>
    body {
        background: white !important;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Đăng Nhập Tài Khoản</h2>
        </div>
        <form action="" method="post">
            <!-- username field -->
            <div class="form-group">
                <label for="user_username" class="form-label">Tên Người Dùng</label>
                <input type="text" id="user_username" placeholder="Nhập tên của bạn" autocomplete="off"
                    required="required" name="user_username" />
            </div>

            <!-- password field -->
            <div class="form-group">
                <label for="user_password" class="form-label">Mật Khẩu</label>
                <input type="password" id="user_password" placeholder="Nhập mật khẩu của bạn" autocomplete="off"
                    required="required" name="user_password" />
            </div>

            <div class="form-group">
                <input type="submit" value="Đăng Nhập" name="user_login">
            </div>

            <div class="small-text">
                <p>Chưa có tài khoản? <a href="user_register.php">Đăng Ký</a></p><br>
                <p>Không cần tạo tài khoản? <a href="../dienthongtin.php">Bỏ qua</a></p>
            </div>
        </form>
    </div>
</body>

</html>

<?php
if(isset($_POST['user_login'])){
    $user_username = trim($_POST['user_username']);
    $user_password = trim($_POST['user_password']);
    $user_ip = getIPAddress();

    // Sử dụng Prepared Statement để chống SQL Injection
    $stmt = $con->prepare("SELECT * FROM `user_table` WHERE username = ?");
    $stmt->bind_param("s", $user_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_count = $result->num_rows;
    $row_data = $result->fetch_assoc();
    $stmt->close();

    // Kiểm tra giỏ hàng
    $stmt_cart = $con->prepare("SELECT * FROM `cart_details` WHERE ip_address = ?");
    $stmt_cart->bind_param("s", $user_ip);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();
    $row_count_cart = $result_cart->num_rows;
    $stmt_cart->close();

    if($row_count>0){
        $_SESSION['username']=$user_username;
        if(password_verify($user_password,$row_data['user_password'])){
            if($row_count==1 && $row_count_cart==0){
                $_SESSION['username']=$user_username;
                // echo "<script>alert('Đăng nhập thành công.')</script>";
                echo "<script>window.open('profile.php','_self')</script>";
            }else{
                $_SESSION['username']=$user_username;
                // echo "<script>alert('Đăng nhập thành công.')</script>";
                echo "<script>window.open('payment.php','_self')</script>";
            }
        }else{
            echo "<script>alert('Thông tin đăng nhập không hợp lệ, vui lòng thử lại.')</script>";
        }
    }else{
        echo "<script>alert('Thông tin đăng nhập không hợp lệ, vui lòng thử lại.')</script>";
    }
}
?>