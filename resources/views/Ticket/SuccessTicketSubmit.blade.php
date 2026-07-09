<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 md:mb-5">
            <div class="text-white">
                You have successfully submitted the UCUA Observation.
            </div>
            <div class="text-white">
                Person in Charge will be notified and verify your observation.
            </div>
            <div class="text-white">
                You will receive an email containing the submitted observation's info for your own reference.
            </div>
            <div class="text-white">
                Thank you.
            </div>
            <div>
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded inline-block mt-4"
                    href="/">Home</a>
                <a class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded inline-block mt-4"
                    href="{{ route('ShowNewTicketForm') }}">New UCUA Observation</a>
            </div>
        </div>
    </div>
</x-guest-layout>
