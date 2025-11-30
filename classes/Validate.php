<?php

class Validate {

    public static function validateName($name) {
        $name = trim($name);
        if (strlen($name) < 3) {
            return false;
        }
        // Only letters, spaces, hyphens, apostrophes
        return (bool) preg_match("/^[a-zA-Z-' ]+$/", $name);
    }

    public static function validateEmail($email) {
        return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword($password) {
        return strlen($password) >= 6;
    }

    public static function validateImageUpload($file, $maxFileSize = 2097152) {
        // 1) Must have been uploaded without PHP error
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // 2) Size check
        if ($file['size'] > $maxFileSize) {
            return false;
        }

        // 3) Verify it’s an image and get its MIME
        $info = @getimagesize($file['tmp_name']);
        if ($info === false) {
            return false;
        }

        // 4) Only allow these MIME types
        $allowed = ['image/jpg', 'image/jpeg', 'image/webp', 'image/svg+xml', 'image/png', 'image/gif'];
        return in_array($info['mime'], $allowed, true);
    }

    // A quick helper function to trim and strip tags from input.
    public static function sanitize($value) {
        return trim(strip_tags($value));
    }
}

?>
