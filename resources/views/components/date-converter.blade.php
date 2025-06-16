<!-- resources/views/components/date-converter.blade.php -->

@props(['inputName', 'saveAs', 'initialValue', 'classStyle'])

<div>
    <input type="date" name="{{ $inputName }}_date" id="{{ $inputName }}_date"
           @if(isset($classStyle))
               class="{{ $classStyle }}"
           @else
               class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
           @endif
           placeholder="" @if(isset($initialValue)) value="{{ $initialValue }}" @endif>

    <!-- Hidden input for backend submission -->
    <input hidden id="{{ $inputName }}" name="{{ $inputName }}" type="text" value="">

    <!-- Display area for Hijri date -->
    <p id="{{ $inputName }}_hijri_date"></p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputName = '{{ $inputName }}';
        const saveAs = '{{ $saveAs }}';
        const gregorianDateInput = document.getElementById(inputName + '_date');
        const hiddenInput = document.getElementById(inputName);

        // Function to convert Gregorian date to Hijri
        function convertToHijri(gregorianDate, S) {
            const dateParts = gregorianDate.split('-');
            const year = dateParts[0];
            const month = dateParts[1];
            const day = dateParts[2];

            fetch(`https://api.aladhan.com/v1/gToH/${day}-${month}-${year}`)
                .then(response => response.json())
                .then(data => {
                    const hijriDate = data.data.hijri.date;
                    const hijriDateFormatted = `${data.data.hijri.year}/${data.data.hijri.month.number}/${data.data.hijri.day}`;
                    document.getElementById(inputName + '_hijri_date').innerText = `Hijri Date: ${hijriDateFormatted}`;
                    if (saveAs === 'hijri') {
                        hiddenInput.value = hijriDateFormatted; // Set hidden input value as Hijri date in Y-m-d format
                    }
                })
                .catch(error => {
                    console.error('Error fetching Hijri date:', error);
                    document.getElementById(inputName + '_hijri_date').innerText = 'Error fetching Hijri date';
                });
        }

        // Initialize on page load with initial value if available
        const initialGregorianDate = gregorianDateInput.value;
        if (initialGregorianDate) {
            convertToHijri(initialGregorianDate);
        }

        // Event listener for date change
        gregorianDateInput.addEventListener('change', function (event) {
            const gregorianDate = event.target.value;
            console.log('Selected Gregorian Date:', gregorianDate);

            convertToHijri(gregorianDate);

            if (saveAs === 'hijri') {
                convertToHijri(gregorianDate);
            } else if (saveAs === 'gregorian') {
                convertToHijri(gregorianDate);
                hiddenInput.value = gregorianDate;
                document.getElementById(inputName + '_hijri_date').innerText = ''; // Clear Hijri date display
                console.log('Gregorian Date saved:', gregorianDate);
            }
        });
    });
</script>
