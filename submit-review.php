<?php

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

function sendJsonResponse(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload);
    exit;
}

// Keep the upload filename predictable without trusting the browser's MIME type.
function getUploadedImageExtension(array $file): string
{
    $originalName = $file['name'] ?? '';
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($extension, $allowedExtensions, true)) {
        return $extension === 'jpeg' ? 'jpg' : $extension;
    }

    return 'jpg';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

$review = trim($_POST['review'] ?? '');
$rating = (int) ($_POST['rating'] ?? 0);

// Reject requests that do not contain the minimum review data.
if ($review === '' || $rating < 1 || $rating > 5) {
    sendJsonResponse(['error' => 'Please provide a valid review and rating'], 400);
}

$imageUrl = null;

$hasReviewImage = isset($_FILES['review_image'])
    && is_array($_FILES['review_image'])
    && ($_FILES['review_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK;

if ($hasReviewImage && is_uploaded_file($_FILES['review_image']['tmp_name'])) {
    // Create the upload directory on demand so the first upload can succeed.
    $uploadDir = __DIR__ . '/Assets/ReviewUploads';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            sendJsonResponse(['error' => 'Unable to create the review upload directory'], 500);
        }
    }

    // Validate that the uploaded file is actually an image before storing it.
    $sourceMime = null;
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $sourceMime = finfo_file($finfo, $_FILES['review_image']['tmp_name']) ?: null;
            finfo_close($finfo);
        }
    }

    if ($sourceMime !== null && strpos($sourceMime, 'image/') !== 0) {
        sendJsonResponse(['error' => 'Uploaded file is not a valid image'], 400);
    }

    // Resize when GD is available; otherwise fall back to a straight file save.
    $canResizeImage = function_exists('imagecreatefromstring')
        && function_exists('imagecreatetruecolor')
        && function_exists('imagecopyresampled')
        && function_exists('imagejpeg');

    $fileName = 'review-' . date('YmdHis') . '-' . bin2hex(random_bytes(4));

    if ($canResizeImage) {
        // Normalize the image to a consistent size and format.
        $sourceImageData = file_get_contents($_FILES['review_image']['tmp_name']);
        $sourceImage = $sourceImageData !== false ? imagecreatefromstring($sourceImageData) : false;

        if ($sourceImage === false) {
            sendJsonResponse(['error' => 'Uploaded file is not a valid image'], 400);
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        if ($sourceWidth <= 0 || $sourceHeight <= 0) {
            imagedestroy($sourceImage);
            sendJsonResponse(['error' => 'Uploaded image has invalid dimensions'], 400);
        }

        $newHeight = 400;
        $newWidth = (int) round($newHeight * ($sourceWidth / $sourceHeight));
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled(
            $newImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $sourceWidth,
            $sourceHeight
        );

        $targetPath = $uploadDir . '/' . $fileName . '.jpg';
        if (!imagejpeg($newImage, $targetPath, 85)) {
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            sendJsonResponse(['error' => 'Unable to save the uploaded image'], 500);
        }

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        $imageUrl = 'Assets/ReviewUploads/' . $fileName . '.jpg';
    } else {
        // Preserve the original upload when image resizing support is unavailable.
        $extension = getUploadedImageExtension($_FILES['review_image']);
        $targetPath = $uploadDir . '/' . $fileName . '.' . $extension;

        if (!move_uploaded_file($_FILES['review_image']['tmp_name'], $targetPath)) {
            sendJsonResponse(['error' => 'Unable to save the uploaded image'], 500);
        }

        $imageUrl = 'Assets/ReviewUploads/' . $fileName . '.' . $extension;
    }
}

$timestamp = date('M d, Y h:i:s A');

sendJsonResponse([
    'success' => true,
    'rating' => $rating,
    'review' => $review,
    'timestamp' => $timestamp,
    'imageUrl' => $imageUrl,
]);