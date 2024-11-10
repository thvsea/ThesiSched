<div id="schedule-<?php echo $requestId ?>" class="flex border border-gray-400 rounded-lg overflow-hidden bg-white">
    <div class="w-[160px] aspect-square bg-[#d9dce1] flex justify-center items-center">
        <i class="fa-solid fa-user fa-5x text-[#72777b]"></i>
    </div>
    <div class="p-4 space-y-1">
        <p class="text-lg font-bold"><?php echo $studentName; ?></p>
        <div class="font-semibold text-[#426b1f]">
            <p><?php echo $guidanceDate; ?></p>
            <p><?php echo $timePeriod; ?></p>
        </div>
        <?php
        if ($status == "Diterima") {
        ?>
            <form method="POST" class="flex space-x-2" id="schedule-action-<?php echo $requestId ?>">
                <input type="button" onclick="handleRequest(this)" value="Cancel" name="<?php echo $requestId ?>" id="cancel-<?php echo $requestId ?>" class="cursor-pointer px-6 py-2 bg-red-400 rounded-full font-medium transition-all hover:scale-95" />
            </form>
        <?php
        } else {
        ?>
            <p class="text-white bg-red-500 p-2 rounded-lg w-fit">Dibatalkan/Ditolak</p>
            <p>Alasan : <?php echo $reason ?></p>
        <?php
        }
        ?>
    </div>
</div>