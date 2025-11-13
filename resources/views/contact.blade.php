<x-shop.layout>
    <!-- Breadcrumbs -->
    <section id="breadcrumbs" class="pt-6 bg-gray-50">
        <div class="container mx-auto px-4">
            <ol class="list-reset flex">
                <li><a href="/" class="font-semibold hover:text-primary">Home</a></li>
                <li><span class="mx-2">&gt;</span></li>
                <li><span class="font-semibold text-primary">Contact Us</span></li>
            </ol>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="py-12">
        <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-2xl p-8">
            <h2 class="text-3xl font-bold text-center mb-8 text-primary">Contact Us</h2>

            <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Your Name</label>
                    <input type="text" name="name"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:border-primary focus:ring-2 focus:ring-primary"
                        required value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Email</label>
                    <input type="email" name="email"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:border-primary focus:ring-2 focus:ring-primary"
                        required value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Subject</label>
                    <input type="text" name="subject"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:border-primary focus:ring-2 focus:ring-primary"
                        value="{{ old('subject') }}">
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-2">Message</label>
                    <textarea name="message" rows="5"
                        class="w-full border border-gray-300 rounded-lg p-3 focus:border-primary focus:ring-2 focus:ring-primary"
                        required>{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-primary hover:bg-transparent border border-transparent hover:border-primary text-white hover:text-primary font-semibold py-2 px-4 rounded-full focus:outline-none">
                    Send Message
                </button>
            </form>
        </div>
    </section>

</x-shop.layout>

<!-- Toastify -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

@if (session('success'))
    <script>
        const msg = "{{ session('success') }}";
        // Top toast
        Toastify({
            text: msg,
            duration: 3000,
            gravity: "top",
            position: "center",
            style: {
                background: "linear-gradient(to right, #16a34a, #22c55e)",
                borderRadius: "10px",
                boxShadow: "0 3px 6px rgba(0,0,0,0.2)",
            }
        }).showToast();

        // Bottom toast
        Toastify({
            text: msg,
            duration: 3000,
            gravity: "bottom",
            position: "center",
            style: {
                background: "linear-gradient(to right, #16a34a, #22c55e)",
                borderRadius: "10px",
                boxShadow: "0 3px 6px rgba(0,0,0,0.2)",
            }
        }).showToast();
    </script>
@endif

@if ($errors->any())
    <script>
        const errors = @json($errors->all());
        errors.forEach(err => {
            Toastify({
                text: "❌ " + err,
                duration: 4000,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #ef4444, #dc2626)",
                    borderRadius: "10px",
                    boxShadow: "0 3px 6px rgba(0,0,0,0.2)",
                }
            }).showToast();
        });
    </script>
@endif
