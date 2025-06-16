// resources/js/status_helper.js
function getStatusHtml(status) {
    if (status === 'delivered') {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-green-300 px-2 py-1 text-xs font-semibold text-black">
<!--                    <span class="h-1.5 w-1.5 rounded-full bg-green-200"></span>-->
                    Delivered
                </span>`;
    } else if (status === 'delivering') {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-purple-300 px-2 py-1 text-xs font-semibold text-black">
<!--                    <span class="h-1.5 w-1.5 rounded-full bg-purple-200"></span>-->
                    Delivering
                </span>`;
    } else if (status === 'canceled') {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-red-300 px-2 py-1 text-xs font-semibold text-black">
<!--                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>-->
                    Canceled
                </span>`;
    } else if (status === 'received') {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-blue-300 px-2 py-1 text-xs font-semibold text-black">
<!--                    <span class="h-1.5 w-1.5 rounded-full bg-orange-600"></span>-->
                    Received
                </span>`;
    } else if (status === 'processing') {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-yellow-300 px-2 py-1 text-xs font-semibold text-black">
<!--                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>-->
                    Processing
                </span>`;
    } else {
        return `<span class="inline-flex items-center gap-1 rounded-full bg-gray-300 px-2 py-1 text-xs font-semibold text-black">
                    Status Unknown
                </span>`;
    }
}
