<?php
session_start();
include '../Database/database_Ferrell_Patel.php';

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = pdo_connect_mysqli(); 
$pdo = pdo_connect_mysql();

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: dashboard.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        // $query_userid = "SELECT id from users where username='$username'";
        $results = mysqli_query($db, $query);
        // $results_userid = mysqli_query($db, $query_userid);

        //code to get userid from username 

        $stmt = $pdo->prepare('SELECT id FROM users where username=?');
        // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
        // $stmt->bindValue(1, $username, PDO::PARAM_INT);
        $stmt->execute([$username]);
        // Fetch the products from the database and return the result as an Array
        $results_userid = $stmt->fetchAll(PDO::FETCH_ASSOC);
              

        if (mysqli_num_rows($results) == 1) {
          $_SESSION['username'] = $username;
          $_SESSION['userid'] = $results_userid[0]['id'];
          // $_SESSION['success'] = "You are now logged in";
          header('location: ../Products/products.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
  
  ?>