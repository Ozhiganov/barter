<?php
for ($i = 1; $i < 100; $i++)
    echo md5(time() + mt_rand())." ";