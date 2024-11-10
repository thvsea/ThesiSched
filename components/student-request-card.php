<div id="request-<?php echo $requestId ?>" class="flex border border-gray-400 rounded-lg overflow-hidden bg-white">
    <div class="w-[160px] h-[160px] bg-[#d9dce1] flex justify-center items-center">
        <i class="fa-solid fa-user fa-5x text-[#72777b]"></i>
    </div>
    <div class="p-4 space-y-1">
        <p class="text-lg font-bold"><?php echo $studentName; ?></p>
        <div class="font-semibold text-[#426b1f]">
            <p><?php echo $guidanceDate; ?></p>
            <p><?php echo $timePeriod; ?></p>
        </div>
        <form method="POST" class="flex space-x-2" id="request-action-<?php echo $requestId ?>">
            <input type="button" onclick="handleRequest(this)" value="Accept" name="<?php echo $requestId ?>" id="accept-<?php echo $requestId ?>" class="cursor-pointer px-6 py-2 bg-green-400 rounded-full font-medium transition-all hover:scale-95"/>
            <input type="button" onclick="handleRequest(this)" value="Reject" name="<?php echo $requestId ?>" id="reject-<?php echo $requestId ?>" class="cursor-pointer px-6 py-2 bg-red-400 rounded-full font-medium transition-all hover:scale-95"/>
        </form>
    </div>
</div>
