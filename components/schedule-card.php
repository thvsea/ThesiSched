<div class="flex border border-gray-400 rounded-lg overflow-hidden bg-white">
    <img src="<?php echo $profilePicture; ?>" alt="" width="160px" class="object-cover">
    <div class="p-4 space-y-1">
        <p class="text-lg font-bold"><?php echo $teacherName; ?></p>
        <div class="font-semibold text-[#426b1f]">
            <p><?php echo $guidanceDate; ?></p>
            <p><?php echo $timePeriod; ?></p>
            <p><?php echo $guidanceMedia; ?></p>
        </div>
        <?php
        if ($status == "Dibatalkan" || $status == "Ditolak" ) {
        ?>
            <p class="text-white bg-red-500 p-2 rounded-lg w-fit">Dibatalkan/Ditolak</p>
            <p>Alasan : <?php echo $reason ?></p>
        <?php
        }
        ?>
    </div>
</div>