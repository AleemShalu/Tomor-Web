<div class="relative bg-indigo-200 p-4 sm:p-6 rounded-sm overflow-hidden mb-8">

    <!-- Background illustration -->
    <div class="absolute right-0 top-0 -mt-4 mr-16 pointer-events-none hidden xl:block" aria-hidden="true">
        <svg width="319" height="198" xmlns:xlink="http://www.w3.org/1999/xlink">
            <!-- SVG code here -->
        </svg>
    </div>

    <!-- Content -->
    <div class="relative">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold mb-1" id="greeting"></h1>
        <p>Here is what's happening with your projects today:</p>
    </div>
</div>



<script>
    // Get the current hour
    var currentHour = new Date().getHours();

    // Get the greeting based on the current hour
    var greeting;
    if (currentHour < 12) {
        greeting = "Good morning";
    } else if (currentHour < 18) {
        greeting = "Good afternoon";
    } else {
        greeting = "Good evening";
    }

    // Update the greeting in the HTML
    document.getElementById("greeting").textContent = greeting + ", {{ Auth::user()->name }} 👋";
</script>
