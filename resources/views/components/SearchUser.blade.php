<!-- This example requires Tailwind CSS v2.0+ -->
<div class="fixed z-10 inset-0 overflow-y-auto ConfirmModal" aria-labelledby="modal-title" role="dialog" aria-modal="true" hidden>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!--
        Background overlay, show/hide based on modal state.
  
        Entering: "ease-out duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100"
          To: "opacity-0"
      -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
  
      <!-- This element is to trick the browser into centering the modal contents. -->
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
  
      <!--
        Modal panel, show/hide based on modal state.
  
        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="">
                    {{-- <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Heroicon name: outline/exclamation -->
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    </div> --}}
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Search and Select User
                        </h3>
                        <div class="mt-2">
                            <div class="form-inline">
                                <div class="input-group">
                                    <input class="w-full" type="text" id="inputSearchId" placeholder="Enter username" autocomplete="off" onkeydown="showResult()">
                                    {{-- <div class="input-group-append"> --}}
                                        {{-- <span class="input-group-text">@drb-hicom.com</span> --}}
                                    {{-- </div> --}}
                                    {{-- <button type="button" class="btn btn-info" onclick="showResult()" style="margin-left:5px;"><i class="fas fa-search"></i></button> --}}
                                </div>
                                <div style="margin-top:10px;width:100%;">
                                    {{-- <button type="button" class="btn btn-info btn-block" onclick="showResult()" style="margin-left:5px;"><i class="fas fa-search"></i> Search</button> --}}
                                    {{-- <div>Results:</div> --}}
                                </div>
                                <form action="{{ route('SubmitMember') }}" method="post" id="SubmitAddMember">
                                    @csrf
                                    <input type="text" name="mode" value="Add" hidden />
                                    <input type="text" id="approverId" name="userEmail" hidden />
                                    <input type="text" name="tenantId" value="{{ $record->id }}" hidden />
                                </form>
                                
                            </div>
                            <div id="livesearch" style="margin-top:10px;"></div>

                            <script>
                                function showResult() {
                                    var str = document.getElementById("inputSearchId").value;
                                    var domain = window.location.origin;
                            
                                    if (str.length===0) {
                                        document.getElementById("livesearch").innerHTML="No result";
                                        document.getElementById("livesearch").style.border="10px";
                                        return;
                                    }
                                    // if (window.XMLHttpRequest) {
                                    //     // code for IE7+, Firefox, Chrome, Opera, Safari
                                    //     xmlhttp=new XMLHttpRequest();
                                    // } else {  // code for IE6, IE5
                                    //     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                                    // }

                                    var records = {!! json_encode($users->toArray()) !!};
                                    var filtered = records.filter(function(each) {
                                        if(each.email.includes(str)){
                                            return setTemplate(each.email);
                                        }
                                    });
                                    // console.log(records);
                                    var list = filtered.map(function(each) {
                                        return setTemplate(each.email);
                                    });
                                    document.getElementById("livesearch").innerHTML=list.join("");
                            
                                    
                                }

                                function setTemplate(email){
                                    return "<div class='shadow hover:shadow-md mb-2 px-4 py-2' onclick='addStaff(\""+email+"\")' >"+email+"</div>"
                                }

                                function addStaff(email){
                                    document.getElementById("approverId").value = email;
                                    document.getElementById("SubmitAddMember").submit();
                                }

                                function removeStaff(value){
                                    $('.'+value).remove();
                                }
                            </script>
                        </div>
                        <div class="mt-2">
                            {{-- <input type="text" name="approverRespond" class="InputApproverRespond" hidden>
                            <input type="text" name="ticketId" value={{ $ticket->ticket_id }} hidden>
                            <input type="text" name="approverLevel" value="{{ $approvalStatues->where('approver_status','Pending')->first()->approver_level }}" hidden>
                            <div>
                                <label for="remark">Remarks (Optional)</label>
                                <textarea name="remark" id="remark" cols="30" rows="5" class="block mt-1 w-full rounded-md border-gray-400" placeholder="Enter here"></textarea>
                            </div> --}}
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                {{-- <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Continue
                </button> --}}
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    onclick="handleClickActionButton(false,'')"
                >
                    Cancel
                </button>
            </div>
        
      </div>
    </div>
</div>

<script src="{{ asset('jquery/3.4.1/jquery.min.js') }}"></script>
<script src="{{ asset('popper/popper.min.js') }}"></script>

<script>
    $(document).ready(function(){
        // $('.ConfirmModal').hide();
    });

    function handleClickActionButton(status,respond){
        if(status){
            $('.ConfirmModal').show();
        }
        else{
            $('.ConfirmModal').hide();
        }

        $('.InputApproverRespond').val(respond);

        if(respond == "Approved"){
            $('.approverRespond').html("<span style='color:green;'>approve</span>");
        }
        else if(respond == "Declined"){
            $('.approverRespond').html("<span style='color:red;'>decline</span>");
        }

    }
</script>