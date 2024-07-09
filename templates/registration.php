
<?php require_once __DIR__ . "/header.php"; ?>

<body>
    <br>
    <div class="container fluid">

        <div class="row no-gutters justify-content-start" style="margin-top: 20% ">
            <div class="col-1"></div>
            <div class="col-7"><h2 style="color: lawngreen">Футбольный чат регистрация пользователя</h2></div>
            <div class="col"></div>
            <div class="col-3">
                <div class="registration_link"><a href='index.php?authorisation=new'>authorisation</a></div>
            </div>
            <div class="col"></div>
        </div>

        <br>
        <div class="row no-gutters justify-content-start">
            <div class="col">
                <form method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="registration" id="registration" value="1">
                    <div class="form-group">
                        <input type="text" class="form-control" name="userName" id="userName" placeholder="User Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="password" id="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                    </div>

                    <button class="btn btn-primary" type="submit" style="margin-top: 10px">Register</button>

                </form>
            </div>
        </div>
    </div>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start">
            <div class="col-6">
                <div class="errors">
                    <?php
                    if(isset($context)){
                        foreach ($context as $err) {
                            echo "<span class='error'>$err</span><br>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>