<?php

namespace App\Http\Controllers\Auth\Access\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\Location;

class TenantLocationController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }
            return view('auth.tenant.page.location');
        }
    }

    public function getLocationData(Request $request) {
        if ($request->ajax()) {
            $query = Location::query()->where('userId', Auth::user()->id)->latest();

            if ($request->filled('name')) {
                $query->where('location_name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('address')) {
                $query->where('address', 'like', '%' . $request->address . '%');
            }
            if ($request->filled('phone')) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }

            return DataTables::of($query)
                ->addColumn('address', function($location) {
                    $shortenedName = Str::limit($location->address, 28, '...');
                    return $shortenedName;
                })
                ->addColumn('location_types', function($location) {
                    $badgecolor = [
                        'badge-primary',
                        'badge-secondary',
                        'badge-success',
                        'badge-danger',
                        'badge-warning',
                        'badge-info',
                        'badge-dark'
                    ];
                    // $type = $location->locationTypes->pluck('name')->implode(', ');
                    // $badge = '<div class="badge badge-primary">'.$type.'</div>';
                    // return $badge;
                    return $location->locationTypes->map(function ($type) use ($badgecolor) {
                        $randomColor = $badgecolor[array_rand($badgecolor)];

                        return '<div class="badge '.$randomColor .' mr-1">' . e($type->name) . '</div>';
                    })->implode(' ');
                })
                ->addColumn('action', function($location){
                    $locationTypeIds = $location->locationTypes->pluck('id')->toJson();

                    return '<a href="#" id="edit-location-data"
                                data-id="' . $location->id . '"
                                data-name="' . e($location->location_name) . '"
                                data-max_quota="' . e($location->max_ticket_quota) . '"
                                data-address="' . e($location->address) . '"
                                data-phone="' . e($location->phone) . '"
                                data-jenis-lokasi=\'' . e($locationTypeIds) . '\'
                                class="btn btn-sm btn-info"
                                data-toggle="modal"
                                data-target="#modals-edit-slide-in">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>';
                })
                ->rawColumns(['action', 'location_types'])
                ->make(true);
        }
    }

    public function locationDataInsert(Request $request){
        $validatedData = $request->validate([
            'name'              => 'required|string|max:255',
            'max_quota'         => 'required|numeric',
            'address'           => 'required|string|max:255',
            'phone'             => 'required|numeric',
            'jenis_lokasi'      => 'required|array',
        ]);

        $location = Location::create([
            'userId'            => Auth::user()->id,
            'location_name'     => $validatedData['name'],
            'max_ticket_quota'  => $validatedData['max_quota'],
            'address'           => $validatedData['address'],
            'phone'             => $validatedData['phone'],
        ]);

        if (!empty($validatedData['jenis_lokasi'])) {
            $location->locationTypes()->syncWithoutDetaching($validatedData['jenis_lokasi']);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    public function locationDataUpdate(Request $request) {
        $validatedData = $request->validate([
            'id'            => 'required',
            'name'          => 'required|string|max:255',
            'max_quota'     => 'required|numeric',
            'address'       => 'required|string|max:255',
            'phone'         => 'required|numeric',
            'jenis_lokasi'  => 'required|array',
        ]);

        $location = Location::findOrFail($request->id);

        $location->update([
            'location_name'     => $validatedData['name'],
            'max_ticket_quota'  => $validatedData['max_quota'],
            'address'           => $validatedData['address'],
            'phone'             => $validatedData['phone'],
        ]);

        $location->locationTypes()->sync($validatedData['jenis_lokasi']);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

}
