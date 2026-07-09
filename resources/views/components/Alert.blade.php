<div class="block {{ $alertColor }} rounded md:mb-2 px-4 py-2 alertWrap">
    <div class="flex flex-row">
        <div>
            <button type="button" class="close customClose">&times;</button>
        </div>
        <div style="margin-left:40px;" class="font-semibold text-green-800">{{ $message }}</div>
    </div>
    
    
</div>

<script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
<script src="{{ asset('popper/popper.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.customClose').click(function(){
            $('.alertWrap').hide();
        });
    });
    
</script>
{{-- <style>
    .customAlert{
        margin-bottom:10px !important;
        /* padding:5px !important; */
    }
    .customClose{
        left:0 !important;
        padding-top:11px !important;
    }
</style> --}}