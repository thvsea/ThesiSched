<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThesiSched</title>
    <!-- tailwind cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./style/style.css">
</head>

<body class="font-poppins-regular text-start">
    <main class="bg-[#f4f4e2] min-h-dvh flex">
        <div class="w-full max-w-[90%] sm:!max-w-[50%] lg:!max-w-[40%] xl:!max-w-[30%] mx-auto flex flex-col items-center py-12">
            <p class="text-5xl text-[#426b1f] font-newsreader-medium ">ThesiSched</p>
            <!-- form -->
            <form class="py-12 w-full space-y-5 flex flex-col justify-center translate-y-1/3">
                <p class="text-3xl font-poppins-medium">Forgot Password</p>
                <!-- email or username -->
                <div class="space-y-1">
                    <p class="text-[#666666]">Email address or username</p>
                    <input type="text" placeholder="johndoe@gmail.com" class="border border-[#666666]/35 bg-transparent w-full p-3 rounded-lg">
                </div>
                <button href="./index.php" class="w-full bg-[#b7b7a9] p-4 rounded-full text-white transition-all hover:scale-95 hover:bg-[#426b1f]/50">Send to Email</button>
            </form>
        </div>
    </main>
</body>

</html>