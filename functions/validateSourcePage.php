<?php

/**
 * Validates the source page from which the request is coming.
 * If the source page is not as expected, it redirects to a specified page.
 *
 * @param string $incomingPage The expected source page.
 * @param string $redirectedPage The page to redirect to if the source page is not as expected.
 * @return void
 */
function validateSourcePage($incomingPage, $redirectedPage)
{
    if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== $incomingPage) {
        header('Location: ' . $redirectedPage);
        exit; // It's a good practice to exit after sending a Location header
    }
}
