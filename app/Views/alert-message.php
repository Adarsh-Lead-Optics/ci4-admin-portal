<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden {
            display: none;
        }
        .alert{
        width: 60%;
        font-size: 14px;
        font-weight: 600;
        color: black !important;
        position: relative;
        top: 15rem;
        margin: auto;
        text-align: center;
    }

    </style>
</head>
<body>
    <div class="alert <?= $type ?>" role="alert" id="alertMessage">
        <?= $message ?>
    </div>

    <script>  
        <?php if($autoHide): ?>
            setTimeout(function() {
                document.getElementById('alertMessage').classList.add('hidden');
            }, <?= $duration ?>);
        <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
