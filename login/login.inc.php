<?php

if (!isset($_POST['login'])){

    header("Location: index.php");
    exit();
}
else {

    require 'dbh.inc.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {

        header("Location: ../login/?error=emptyfields");
        exit();
    } 
    else {

        $sql = "SELECT * FROM users WHERE username=?;";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)) {

            header("Location: ../login/?error=sqlerror");
            exit();
        } 
        else {

            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {

                $pwdCheck = password_verify($password, $row['password']);

                if ($pwdCheck == false) {

                    header("Location: ../login/?error=wrongpwd");
                    exit();
                } else if ($pwdCheck == true) {

                    session_start();
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['full-name'] = $row['full-name'];
                    $_SESSION['last-name'] = $row['last-name'];
                    $_SESSION['gender'] = $row['gender'];
                    $_SESSION['headline'] = $row['headline'];
                    $_SESSION['bio'] = $row['bio'];
                    $_SESSION['profile-image'] = $row['profile-image'];
                    $_SESSION['banner-image'] = $row['banner-image'];
                    $_SESSION['user-level'] = $row['user-level'];

                    header("Location: ../home/?login=success");
                    exit();
                } 
            } 
            else {

                header("Location: ../login/?error=nouser");
                exit();
            }
        }
    }
}