<?php
/**
 * Created by IntelliJ IDEA.
 * User: angel
 * Date: 03-08-18
 * Time: 13:07
 */


session_start();
session_unset();
session_destroy();

header('Location: ../index.php');
