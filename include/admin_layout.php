    <body>
        <?php
        adminMenu($result_menu);
        ?>
        <div class="container" id="content">
            <?php
            if(file_exists("view/".$page_view.".php")){
                include_once("view/".$page_view.".php");
            }else{
                exit("view non définie ou inexistante");
            }
            ?>
        </div>
    </body>
</html>