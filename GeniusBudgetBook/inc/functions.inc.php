<?php

function e($html) {
    return htmlspecialchars($html, ENT_QUOTES, 'UTF-8', true);
}
