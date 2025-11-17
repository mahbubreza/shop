<?php

namespace App\Http\Controllers;


use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CouponController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['auth','admin']);
    }

    public function index()
    {
        $coupons = Coupon::orderBy('created_at','desc')->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }


    public function create()
    {
        return view('admin.coupons.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
        'code' => 'required|string|unique:coupons,code',
        'type' => 'required|in:fixed,percent',
        'value' => 'required|numeric|min:0',
        'min_cart_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:1',
        'max_uses_per_user' => 'nullable|integer|min:1',
        'starts_at' => 'nullable|date',
        'ends_at' => 'nullable|date|after_or_equal:starts_at',
        'active' => 'nullable|boolean',
        ]);


        $data['active'] = $request->has('active');


        Coupon::create($data);


        return redirect()->route('admin.coupons.index')->with('success','Coupon created');
    }


    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }


    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
        'code' => 'required|string|unique:coupons,code,'.$coupon->id,
        'type' => 'required|in:fixed,percent',
        'value' => 'required|numeric|min:0',
        'min_cart_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:1',
        'max_uses_per_user' => 'nullable|integer|min:1',
        'starts_at' => 'nullable|date',
        'ends_at' => 'nullable|date|after_or_equal:starts_at',
        'active' => 'nullable|boolean',
        ]);


        $data['active'] = (int) $request->input('active', 0);


        $coupon->update($data);


        return redirect()->route('admin.coupons.index')->with('success','Coupon updated');
    }


    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success','Coupon deleted');
    }


    public function toggle(Coupon $coupon)
    {
        $coupon->active = ! $coupon->active;
        $coupon->save();
        return redirect()->back()->with('success','Coupon status updated');
    }
}