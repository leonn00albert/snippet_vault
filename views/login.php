<?php include "header.php"; ?>

<body>
    <div class="container text-center">
        <?php include "nav.php"; ?>
        <div class="card">
            <div class="login-container">
                <h2>Login</h2>
                <form action="/auth/login" method="POST" >

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="user" id="username" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>


                </form>


            </div>
        </div>
    </div>

</body>

</html>