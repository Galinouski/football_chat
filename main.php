<?php
header("Content-type: text/html; charset=utf-8");
require_once __DIR__ . "/header.php";
?>

<body>
    <br>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start">
            <div class="col-6">
                <h2 style="color: lawngreen">Футбольный чат</h2>
                <form method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="main" id="main" value="1">
                    <div class="form-group">
                        <textarea class="form-control" name="message" id="message" rows="5" required></textarea>
                        <div class="row no-gutters justify-content-start">
                            <div class="col-10">
                                <input type="file" class="form-control" id="exampleFormControlFile1" name="txtFile">
                                <input type="file" class="form-control" id="exampleFormControlFile1" name="imageFile">
                            </div>
                            <div class="col-2">
                                <div class="form-control" style="height: 44px">txt</div>
                                <div class="form-control" style="height: 44px">image</div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Send</button>
                </form>
            </div>
            <div class="col-1">
            </div>
            <div class="col">
                <img class="image" src="images/1.jpg">
            </div>

        </div>
    </div>
    <div class="container fluid">
        <div class="row no-gutters justify-content-start">
            <div class="col">
                <div class="errors">
                    <?php
                    if(isset($errors)){
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger' role='alert'>$error</div>";
                        }
                        exit();
                        header("Location: {$_SERVER['PHP_SELF']}");
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row no-gutters justify-content-start" style="margin-bottom: 20px">
            <div class="col">
                <form method="post" enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <div class='row no-gutters justify-content-center'>
                        <div class='col-md-7'></div>
                        <div class='col'>
                            <select name="select_pages_show" class="form-control">
                                <?php for ( $i = 5; $i<= MAX_PAGES_PER_PAGE; $i+=5 ) {

                                    if( $i == $_SESSION['pageShow'] ){
                                        echo "<option value='$i' selected>$i</option>";
                                    }else {
                                        echo "<option value='$i'>$i</option>";
                                    }

                                } ?>
                            </select>
                        </div>
                        <div class='col'>
                            <button type="submit" class="btn btn-success" name="page_sort" style='margin-right: 5px'>go</button>
                        </div>
                        <div class='col'>
                            <button type="submit" class="btn btn-success" name="name_sort" style='width: 90px; margin-left: 10px; margin-right: 5px'> name sort</button>
                        </div>
                        <div class='col'>
                            <button type="submit" class="btn btn-success" name="email_sort" style='width: 90px; margin-right: 5px'>email sort</button>
                        </div>
                        <div class='col'>
                            <button type="submit" class="btn btn-success" name="date_sort" style='width: 90px'>date sort</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row no-gutters justify-content-start">
            <div class="col">
                <div class="massages">

                    <?php

                    //var_dump($chat_body); die;


                        $color = [
                            "alert alert-primary",
                            "alert alert-secondary",
                            "alert alert-success",
                            "alert alert-danger",
                            "alert alert-warning",
                            "alert alert-info",
                            "alert alert-light",
                            "alert alert-dark"
                        ];

                        if(isset($chat_body)){
                            foreach ($chat_body as $user => $data) {
                                $class = $color[rand(1,7)];
                                echo "<div class='$class' role='alert' style='margin-bottom: 2px; padding: 2px; font-size: 16px'>
                                        <div class='row no-gutters justify-content-start'>
                                                <div class='col-6'>$data[message] </div> ";

                                if(isset($data['txt_file_upload_name'])) {
                                echo "          <div class='col'><a href='download.php?path=downloads/$data[txt_file_upload_name]'>txt</a></div>";}
                                else echo "     <div class='col'></div>";
                                if(isset($data['image_file_upload_name'])) {
                                echo "          <div class='col'><a href='download.php?path=downloads/$data[image_file_upload_name]'>img</a></div>";}
                                else echo "     <div class='col'></div>";

                                echo "          <div class='col-1' style='font-size: 12px'>$data[username]</div>
                                                <div class='col-2' style='font-size: 12px'>$data[email]</div>
                                                <div class='col-2' style='font-size: 12px'>$data[date]</div>
                                        </div>
                                      </div>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="row no-gutters justify-content-center">
            <div class="col"></div>
            <div class = "col">
                <?php
                    $i = 1;
                    while ($i <= $pages){

                        if(isset($_SESSION['pageShow'])) {
                            $page_show = $_SESSION['pageShow'];
                        }

                        if (isset($_SESSION['page']) && $i == $_SESSION['page']){
                            echo "<a href='index.php?&page=$i&select_pages_show=$page_show' style='color: lawngreen; font-weight: bold; margin-right: 20px '>$i</b></a>";
                        }else{
                            if (!isset($_SESSION['page']) && $i == 1) {
                                echo "<a href='index.php?&page=$i&select_pages_show=$page_show' style='color: lawngreen; font-weight: bold; margin-right: 20px '>$i</b></a>";
                            } else {
                                echo "<a href='index.php?&page=$i&select_pages_show=$page_show' style='margin-right: 20px'>$i</a>";
                            }

                        }

                        $i++;
                    }
                ?>
            </div>
            <div class="col"></div>
        </div>
    </div>

<?php require_once __DIR__ . "/footer.php"; ?>

</body>
</html>
