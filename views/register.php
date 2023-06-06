<?php include "header.php"; ?>

<body>
    <div class="container text-center">
        <?php include "nav.php"; ?>
        <div class="card">
            <div class="register-container">
                <h2>Register</h2>
                <form action="/auth/register" method="POST" >

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name"  name="user" placeholder="Enter your name">
                </div>
       
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control"  name="password" id="password" placeholder="Enter a password">
                </div>
          
                <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>