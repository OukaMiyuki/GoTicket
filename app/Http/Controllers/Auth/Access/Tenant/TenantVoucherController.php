<?php

namespace App\Http\Controllers\Auth\Access\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Voucher;

class TenantVoucherController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }
            return view('auth.tenant.page.voucher');
        }
    }

    public function getVoucherData(Request $request) {
        if ($request->ajax()) {
            $playgroundOwner = Auth::id();
            $query = Voucher::with(['locations'])->where('userId', $playgroundOwner);

            if ($request->filled('discount_type')) {
                $query->where('discount_type', $request->discount_type );
            }
            if ($request->filled('date_range')) {
                $dateRange = $request->date_range;
                if (str_contains($dateRange, ' to ')) {
                    [$startDate, $endDate] = explode(' to ', $dateRange);
                    $query->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                    });
                }
            }
            if ($request->filled('lokasi')) {
                $query->whereHas('locations', function ($q) use ($request) {
                    $q->whereIn('locationId', $request->lokasi);
                });
            }

            $voucher = $query->get();

            $badgecolor = [
                'badge-primary',
                'badge-secondary',
                'badge-success',
                'badge-danger',
                'badge-warning',
                'badge-info',
                'badge-dark'
            ];

            return DataTables::of($voucher)
                ->addColumn('discount_type', function ($voucher) {
                    if($voucher->discount_type == "percentage"){
                        $dicType = "<strong>Persentase</strong>";
                    } else {
                        $dicType = "<strong>Fixed</strong>";
                    }
                    return $dicType;
                })
                ->addColumn('discount_value', function ($voucher) {
                    if($voucher->discount_type == "percentage"){
                        $discount = "<strong>".$voucher->discount_value."%</strong>";
                    } else {
                        $format = number_format($voucher->discount_value,2,',','.');
                        $discount = "<strong>Rp.".$voucher->discount_value."</strong>";
                    }
                    return $discount;
                })
                ->addColumn('min_spend', function ($voucher) {
                    $min = $voucher->min_spend;
                    $min_spend = number_format($min,2,',','.');
                    return $min_spend;
                })
                ->addColumn('max_discount', function ($voucher) {
                    $max = $voucher->max_discount;
                    $max_discount = number_format($max,2,',','.');
                    return $max_discount;
                })
                ->addColumn('status', function ($voucher) {
                    $badge = "";
                    if($voucher->status == "active"){
                        $badge = '<div class="badge badge-success mr-1">Aktif</div>';
                    } else if($voucher->status == "inactive"){
                        $badge = '<div class="badge badge-danger mr-1">Tidak Aktif</div>';
                    } else if($voucher->status == "expired"){
                        $badge = '<div class="badge badge-secondary mr-1">Tidak Aktif</div>';
                    }
                    return $badge;
                })
                ->addColumn('location', function ($voucher) use ($badgecolor) {
                    if ($voucher->locations->isNotEmpty()) {
                        return $voucher->locations->map(function ($location) use ($badgecolor) {
                            $randomColor = $badgecolor[array_rand($badgecolor)];
                            $shortenedName = Str::limit($location->location_name, 25, '...');
                            return '<div class="badge ' . $randomColor . ' mr-1" title="' . e($location->location_name) . '">' . e($shortenedName) . '</div>';
                        })->implode(' ');
                    }
                    return '';
                })                
                ->addColumn('action', function ($voucher) {
                    $dateRange = $voucher->start_date . ' to ' . $voucher->end_date;
                    $locationTypeIds = $voucher->locations->pluck('id')->toJson();
                    $deleteUrl = route('tenant.dashboard.voucher.delete', ['voucherId' => $voucher->id]);

                    return '
                        <a title="Edit data voucher" href="#"
                            id="edit-voucher-data"
                            data-id="' . $voucher->id . '"
                            data-code="' . e($voucher->code) . '"
                            data-discount_type="' . e($voucher->discount_type) . '"
                            data-discount_value="' . e($voucher->discount_value) . '"
                            data-min_spend="' . e($voucher->min_spend) . '"
                            data-max_discount="' . e($voucher->max_discount) . '"
                            data-dateRange="' . e($dateRange) . '"
                            data-usage_limit="' . e($voucher->usage_limit) . '"
                            data-per_user_limit="' . e($voucher->per_user_limit) . '"
                            data-lokasi=\'' . e($locationTypeIds) . '\'
                            data-status="' . e($voucher->status) . '"
                            class="btn btn-sm btn-info"
                            data-toggle="modal"
                            data-target="#modals-edit-slide-in"
                            class="btn btn-sm btn-info" style="margin:.2rem";>
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a title="Hapus voucher" href="'.$deleteUrl.'"
                            class="btn btn-sm btn-danger" style="margin:.2rem";>
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    ';
                })
                ->rawColumns(['min_spend', 'max_discount', 'status', 'location', 'action', 'discount_type', 'discount_value'])
                ->make(true);
        }
    }

    public function addVoucherDataInsert(Request $request){
        try {
            $validatedData = $request->validate([
                'code'              => 'required|string|max:30|unique:vouchers,code',
                'discount_type'     => 'required|string|in:percentage,fixed',
                'discount_value'    => 'required|numeric|min:1',
                'min_spend'         => 'required|numeric|min:1',
                'max_discount'      => 'required|numeric|min:1',
                'range_date'        => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        $dates = explode(' to ', $value);
                        if (count($dates) !== 2) {
                            $fail('The date range format is invalid.');
                            return;
                        }

                        try {
                            $startDate = Carbon::parse($dates[0]);
                            $endDate = Carbon::parse($dates[1]);

                            if ($startDate->greaterThan($endDate)) {
                                $fail('The start date must be before the end date.');
                            }
                        } catch (\Exception $e) {
                            $fail('Invalid date format.');
                        }
                    }
                ],
                'usage_limit'       => 'required|numeric|min:1',
                'per_user_limit'    => 'required|numeric|min:1',
                'plot_lokasi'       => 'required|array',
            ]);

            $dates = explode(' to ', $validatedData['range_date']);
            $startDate = Carbon::parse($dates[0])->toDateString();
            $endDate = Carbon::parse($dates[1])->toDateString();

            $voucher = Voucher::create([
                'userId'            => Auth::id(),
                'code'              => $validatedData['code'],
                'discount_type'     => $validatedData['discount_type'],
                'discount_value'    => $validatedData['discount_value'],
                'min_spend'         => $validatedData['min_spend'],
                'max_discount'      => $validatedData['max_discount'],
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'usage_limit'       => $validatedData['usage_limit'],
                'per_user_limit'    => $validatedData['per_user_limit'],
            ]);

            if (!empty($validatedData['plot_lokasi'])) {
                $voucher->locations()->syncWithoutDetaching($validatedData['plot_lokasi']);
            }

            return response()->json([
                'success' => true,
                'message' => 'User added successfully!'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add voucher data due to an error, please contact support!'
            ], 500);
        }
    }

    public function addVoucherDataUpdate(Request $request){
        $validatedData = $request->validate([
            'id'            => 'required',
            'code'              => 'required|string|max:30',
            'discount_type'     => 'required|string|in:percentage,fixed',
            'discount_value'    => 'required|numeric|min:1',
            'min_spend'         => 'required|numeric|min:1',
            'max_discount'      => 'required|numeric|min:1',
            'range_date'        => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $dates = explode(' to ', $value);
                    if (count($dates) !== 2) {
                        $fail('The date range format is invalid.');
                        return;
                    }

                    try {
                        $startDate = Carbon::parse($dates[0]);
                        $endDate = Carbon::parse($dates[1]);

                        if ($startDate->greaterThan($endDate)) {
                            $fail('The start date must be before the end date.');
                        }
                    } catch (\Exception $e) {
                        $fail('Invalid date format.');
                    }
                }
            ],
            'usage_limit'       => 'required|numeric|min:1',
            'per_user_limit'    => 'required|numeric|min:1',
            'plot_lokasi'       => 'required|array',
            'status'            => 'required|string|in:active,inactive',
        ]);

        $voucher = Voucher::with(['locations'])->find($request->id);

        if(is_null($voucher)){
            return redirect()->back()->with('error', 'Data not found!');
        } 

        if($voucher->code != $validatedData['code']){
            $voucherCheck = Voucher::where('code', $validatedData['code'])->first();
            if(!is_null($voucherCheck)){
                return redirect()->back()->with('error', 'Duplication data voucher code!');
            }
        }

        $dates = explode(' to ', $validatedData['range_date']);
        $startDate = Carbon::parse($dates[0])->toDateString();
        $endDate = Carbon::parse($dates[1])->toDateString();

        $voucher->update([
            'code'              => $validatedData['code'],
            'discount_type'     => $validatedData['discount_type'],
            'discount_value'    => $validatedData['discount_value'],
            'min_spend'         => $validatedData['min_spend'],
            'max_discount'      => $validatedData['max_discount'],
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'usage_limit'       => $validatedData['usage_limit'],
            'per_user_limit'    => $validatedData['per_user_limit'],
            'status'            => $validatedData['status'],
        ]);

        $voucher->locations()->sync($validatedData['plot_lokasi']);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function addVoucherDataStatusUpdate($id){
        $playgroundOwner = Auth::user()->id;
        $voucher = Voucher::where('userId', $playgroundOwner)->find($id);

        if(is_null($voucher)){
            return redirect()->back()->with('error', 'Data not found!');
        } else {
            $status = 0;

            if($voucher->status == 0){
                $status = 1;
            }

            $voucher->update([
                'status' => $status
            ]);

            return redirect()->back()->with('success', 'Voucher status updated successfully!');
        }
    }

    public function deleteVoucherData($id) {
        $playgroundOwner = Auth::user()->id;
    
        $voucher = Voucher::with(['locations'])
                            ->where('userId', $playgroundOwner)
                            ->where('id', $id)
                            ->first();
    
        try {
            if (is_null($voucher)) {
                return redirect()->back()->with('error', 'Data not found!');
            } else {
                $voucher->locations()->detach();
                $voucher->delete();
    
                return redirect()->back()->with('success', 'Voucher data has been deleted successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Data deletion failed, there's an error when trying to delete data!");
        }
    }
    
}
