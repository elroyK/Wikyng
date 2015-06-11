        <div id="footer">
            <img src="./img/<?php  if (logged_in() && $_SESSION['id'] == 17) echo "G_";?>copyright.png" id="copyright" alt="Copyright"><p><?php echo $ini_array['main_infos']['cpyright']; ?><br>
            <a href="mailto:<?php echo $ini_array['main_infos']['mailto']; ?>"><?php echo $ini_array['main_infos']['author']; ?></a></p>
        </div>
    </body>
</html>