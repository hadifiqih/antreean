<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function formatWhatsAppNumber($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // If number starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        // If number doesn't start with 62, add it
        elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    private function getWhatsAppLink($phone)
    {
        $formattedPhone = $this->formatWhatsAppNumber($phone);
        return '<a href="https://wa.me/' . $formattedPhone . '" target="_blank">' . $phone . '</a>';
    }

    public function index()
    {
        return view('page.customer.index');
    }

    public function getRepeatOrders()
    {
        $query = Customer::where('frekuensi_order', '>', 2)
                        ->select(['id', 'nama', 'telepon', 'alamat', 'frekuensi_order', 'last_order_date']);
        
        return DataTables::of($query)
            ->addColumn('action', function ($customer) {
                return '<a href="'.route('customer.show', $customer->id).'" class="btn btn-info btn-sm">Detail</a>';
            })
            ->editColumn('telepon', function($customer) {
                return $this->getWhatsAppLink($customer->telepon);
            })
            ->editColumn('last_order_date', function($customer) {
                return $customer->last_order_date ? date('d-m-Y', strtotime($customer->last_order_date)) : '-';
            })
            ->rawColumns(['action', 'telepon'])
            ->make(true);
    }

    public function getNewCustomers()
    {
        $query = Customer::where('frekuensi_order', '=', 1)
                        ->select(['id', 'nama', 'telepon', 'alamat', 'created_at']);
        
        return DataTables::of($query)
            ->addColumn('action', function ($customer) {
                return '<a href="'.route('customer.show', $customer->id).'" class="btn btn-info btn-sm">Detail</a>';
            })
            ->editColumn('telepon', function($customer) {
                return $this->getWhatsAppLink($customer->telepon);
            })
            ->editColumn('created_at', function($customer) {
                return $customer->created_at ? date('d-m-Y', strtotime($customer->created_at)) : '-';
            })
            ->rawColumns(['action', 'telepon'])
            ->make(true);
    }

    public function getLeads()
    {
        $query = Customer::where('frekuensi_order', '=', 0)
                        ->select(['id', 'nama', 'telepon', 'alamat', 'status_follow_up', 'next_follow_up']);
        
        return DataTables::of($query)
            ->addColumn('action', function ($customer) {
                return '<a href="'.route('customer.show', $customer->id).'" class="btn btn-info btn-sm">Detail</a>';
            })
            ->editColumn('telepon', function($customer) {
                return $this->getWhatsAppLink($customer->telepon);
            })
            ->editColumn('status_follow_up', function($customer) {
                return ucfirst($customer->status_follow_up);
            })
            ->editColumn('next_follow_up', function($customer) {
                return $customer->next_follow_up ? date('d-m-Y', strtotime($customer->next_follow_up)) : '-';
            })
            ->rawColumns(['action', 'telepon'])
            ->make(true);
    }

    public function search(Request $request)
    {
        $data = Customer::where('nama', 'LIKE', "%".request('q')."%")->get();
        return response()->json($data);
    }

    public function searchById(Request $request)
    {
        $data = Customer::where('id', 'LIKE', "%".request('id')."%")->get();
        return response()->json($data);
    }

    public function show($id)
    {
        $customer = Customer::with('antrians')->findOrFail($id);
        return view('page.customer.show', compact('customer'));
    }

    public function store(Request $request)
    {
        //Menyimpan no.telp dalam format seperti berikut 081234567890, tanpa spasi. strip, titik, dll
        $telp = preg_replace('/\D/', '', $request->modalTelepon);

        $customer = new Customer;

        $customer->telepon = $telp;

        if($request->modalNama){
            $customer->nama = $request->modalNama;
        }

        if($request->modalAlamat){
            $customer->alamat = $request->modalAlamat;
        }

        if($request->modalInstansi){
            $customer->instansi = $request->modalInstansi;
        }

        if($request->modalInfoPelanggan){
            $customer->infoPelanggan = $request->modalInfoPelanggan;
        }

        $customer->save();

        return response()->json(['success' => 'true', 'message' => 'Data berhasil ditambahkan']);
    }

}
