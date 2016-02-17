<?php
setcookie("id", "", time() -60*60*12, "/");
echo("<html><script>window.location = '../sign_in.php'</script></html>"); exit();
