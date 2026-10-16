<?php
require __DIR__.'/includes/helpers.php'; 
require __DIR__.'/config/database.php'; start_secure_session();
if(!empty($_SESSION['last_activity']) && time()-(int)$_SESSION['last_activity']>1800)
    {session_unset();session_regenerate_id(true);}
$error='';if($_SERVER['REQUEST_METHOD']==='POST')
{ $email=strtolower(trim((string)($_POST['email']??'')));
$password=(string)($_POST['password']??'');
if(!csrf_valid())$error='Form expired. Refresh and try again.';
else
    {
        try{$pdo=getDatabaseConnection();$q=$pdo->prepare('SELECT student_id,full_name,email,password_hash,role FROM students WHERE email=? LIMIT 1');
        $q->execute([$email]);$u=$q->fetch();
        if($u && $u['password_hash'] && password_verify($password,$u['password_hash']))
            {
                session_regenerate_id(true);
                $_SESSION['student_id']=(int)$u['student_id'];$_SESSION['full_name']=$u['full_name'];
                $_SESSION['role']=$u['role'];
                $_SESSION['last_activity']=time();
                header('Location: '.($u['role']==='admin'?'admin_dashboard.php':'events.php'));
                exit;
                }
                else $error='Invalid email or password.';
                }
                catch(Throwable $x)
                {
                    error_log($x->getMessage());
                    $error='Database unavailable. Import schema.sql first.';
                    }
                    }
                    }
                    page_start('Student Login');
                    if($error)
                        echo '<div class="notice error">'.e($error).'</div>';
                    echo '<div class="practical-box"><form method="post"><input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">
                    <label>Email<input type="email" name="email" required autocomplete="username"></label>
                    <label>Password<input type="password" name="password" required autocomplete="current-password"></label>
                    <button class="btn btn-primary">Login</button></form>
                    <p>New student? <a href="register.php">Create an account</a>.</p>
                    <p>Session timeout: 30 minutes of inactivity.</p></div>';
                    page_end(); ?>