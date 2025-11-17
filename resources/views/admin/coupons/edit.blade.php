<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Coupon
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                @csrf 
                @method('PATCH')
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 dark:border-gray-700 pb-12">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            
                      
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="name" >Code</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="code" type="text" name="code" value="{{ old('code', $coupon->code) }}" required />
                                        <x-forms.form-error name="code" />
                                    </div>
                                </x-forms.form-field>
                            </div>
        

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="type" >Type</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="type" name="type">
                                        <option value="fixed"  {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percent" {{ old('type', $coupon->type) === 'percent' ? 'selected' : '' }}>Percent</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="type" />                                  
                                </div>
                            </div> 

                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="value" >Value (amount or percent)</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="value" type="number" name="value" value="{{ old('value', $coupon->value) }}"  required />
                                        <x-forms.form-error name="value" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="min_cart_amount" >Minimum Cart Amount (optional)</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="min_cart_amount" type="number" name="min_cart_amount" value="{{ old('min_cart_amount', $coupon->min_cart_amount) }}" required />
                                        <x-forms.form-error name="min_cart_amount" />
                                    </div>
                                </x-forms.form-field>
                            </div>

                             <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="max_uses" >Max Uses</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="max_uses" type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}"  />
                                        <x-forms.form-error name="max_uses" />
                                    </div>
                                </x-forms.form-field>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="max_uses_per_user" >Max Uses Per User (optional)</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input id="max_uses_per_user" type="number" name="max_uses_per_user" value="{{ old('max_uses_per_user', $coupon->max_uses_per_user) }}" />
                                        <x-forms.form-error name="max_uses_per_user" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="starts_at" >Starts At (optional)</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input 
                                            id="starts_at" 
                                            type="text" 
                                            name="starts_at"  
                                            value="{{ old('discount_start_date', $coupon->starts_at) }}"

                                        />
                                        <x-forms.form-error name="starts_at" />
                                    </div>
                                </x-forms.form-field>
                            </div>
                            <div class="sm:col-span-3">
                                <x-forms.form-field>
                                    <x-forms.form-label for="ends_at" >Ends At (optional)</x-forms.form-label>
                                    <div class="mt-2">
                                        <x-forms.form-input 
                                            id="ends_at" 
                                            type="text" 
                                            name="ends_at"  
                                            value="{{ old('discount_start_date', $coupon->ends_at) }}"

                                        />
                                        <x-forms.form-error name="ends_at" />
                                    </div>
                                </x-forms.form-field>
                            </div>

                            <div class="sm:col-span-3">
                                <x-forms.form-label for="active" >Active</x-forms.form-label>
                                <div class="mt-2 grid grid-cols-1">
                                    <x-forms.form-select id="active" name="active">
                                        <option value="1" {{ $coupon->active ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$coupon->active ? 'selected' : '' }}>No</option>
                                    </x-forms.form-select>    
                                    <x-forms.form-error name="active" />                                  
                                </div>
                            </div>  
                         
                        </div>
                    </div>
                </div>
                {{-- Buttons --}}
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="button"
                        class="text-sm font-semibold text-gray-900 dark:text-gray-200 hover:text-indigo-500 dark:hover:text-indigo-400 transition">
                        <a href="/brands">Cancel</a>
                    </button>

                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#starts_at", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
        flatpickr("#ends_at", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    </script>
</x-app-layout>
