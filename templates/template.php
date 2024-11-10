<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- tailwind cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- css -->
    <link rel="stylesheet" href="<?php echo $stylePath; ?>">
    <!-- font-awesome icons -->
    <script src="https://kit.fontawesome.com/ee3dfa30d0.js" crossorigin="anonymous"></script>
    <!-- js -->
    <script src="<?php echo $scriptPath; ?>"></script>
</head>

<body class="min-h-dvh bg-[#f4f4e2]">
    <!-- toast -->
    <div class="fixed top-4 left-1/2 -translate-x-1/2 text-start" id="toast-container">

    </div>
    <!-- navbar -->
    <?php 
    // if ($hasSearch) {
    //     include LOCAL_ROOT . '/components/navbar-search.php';
    // } else {
    // }  
    include LOCAL_ROOT . '/components/navbar.php';
    ?>

    <!-- headline -->
    <div class="bg-[#dbe6c3] w-full py-6">
        <div class="flex w-full justify-between px-4 mx-auto xl:max-w-[90%]">
            <p class="text-6xl font-newsreader-regular"><?php echo $headline; ?></p>
            <?php
            if ($headlineContent) {
            ?>
                <div class="flex space-x-5 items-center">
                    <?php echo $headlineContent; ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <!-- content -->
    <main class="text-start">
        <?php echo $content; ?>
    </main>

    <!-- footer -->
    <footer>
        <?php include LOCAL_ROOT . '/components/footer.php'; ?>
    </footer>
</body>

</html>