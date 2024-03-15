<?php

/**
 * Validates the source page from which the request is coming.
 * If the source page is not as expected, it redirects to a specified page.
 *
 * @param string $incomingPage    The expected source page.
 * @param string $redirectedPage  The page to redirect to if the source page is not as expected.
 * @param int    $errCode         The error code to pass to the redirected page.
 * @return void
 */
function validateSourcePage($incomingPage, $redirectedPage, $errCode)
{
    // Retrieve the referer information from the server headers
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    // Check if referer information is available
    if ($referer === '') {
        //echo "No referer information available.";
        header('Location: ' . $redirectedPage . '?err=' . $errCode);
        exit;
    }

    // Extract the relative path from the referer URL
    $refererRelativePath = basename(parse_url($referer, PHP_URL_PATH));

    // Compare the relative paths
    if ($refererRelativePath !== $incomingPage) {
        // Redirect to the specified page with the provided error code
        header('Location: ' . $redirectedPage . '?err=' . $errCode);
        exit; // It's a good practice to exit after sending a Location header
    }
}
