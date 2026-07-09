<div class="grid grid-cols-3 justify-items-stretch shadow-md rounded-t-lg py-2 px-3 block bg-white">
    <div class="col-span-2">
        <div class="font-bold" data-toggle="collapse">{{ $title }}
            {{-- <span class="text-red-600">*</span> --}}
        </div>
    </div>
    <div class="col-span-1 justify-self-end">
        <div href="#section{{ $section }}" class="text-blue-500 cursor-pointer">Hide</div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var isCollapse = true;
        var sectionNum = "{{ $section }}";
        $('[href="#section' + sectionNum + '"]').click(function() {
            $('#section' + sectionNum).collapse('toggle');
            (isCollapse) ? $(this).text('Show'): $(this).text('Hide');
            isCollapse = !isCollapse;
        });
    });
</script>
