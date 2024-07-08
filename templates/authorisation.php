<?php
header("Content-type: text/html; charset=utf-8");
require_once __DIR__ . "/header.php";
?>

<body>
<br>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start align-content-center" style="margin-top: 20% ">
            <div class="col"></div>
            <div class="col-8">
                <h2 style="color: lawngreen">Футбольный чат</h2>
                <form method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="authorisation" id="authorisation" value="1">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label class="sr-only" for="inlineFormInput">login</label>
                            <input type="text" class="form-control mb-2" name="userName" id="inlineFormInput" placeholder="user name" required>
                        </div>
                        <div class="col-auto">
                            <label class="sr-only" for="inlineFormInputGroup">password</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">/</div>
                                </div>
                                <input type="text" class="form-control" name="password" id="inlineFormInputGroup" placeholder="password" required>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start">
            <div class="col"></div>
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
            <div class="col"></div>
        </div>
    </div>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start">
            <div class="col"></div>
            <div class="col-6">
                <div class="registration_link"><a href='index.php?registration=1'>registration</a></div>
            </div>
            <div class="col"></div>
        </div>
    </div>
</body>
