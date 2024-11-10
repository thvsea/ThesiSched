<div id="request-<?php echo $requestId; ?>" class="flex border border-gray-400 rounded-lg overflow-hidden bg-white">
    <img src="<?php echo $profilePicture; ?>" alt="" width="160px">
    <div class="p-4 space-y-1">
        <p class="text-lg font-bold text-center"><?php echo $teacherName; ?></p>
        <div class="font-semibold text-[#426b1f]">
            <p><?php echo $guidanceDate; ?></p>
            <p><?php echo $timePeriod; ?></p>
        </div>

        <form method="POST">
            <input type="hidden" name="requestId" value="<?php echo $requestId; ?>">
            <input type="submit" name="cancelRequest" value="Cancel" class="cursor-pointer px-6 py-2 bg-red-400 rounded-full font-medium transition-all hover:scale-95" />
        </form>
    </div>
</div>