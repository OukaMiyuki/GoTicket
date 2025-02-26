<?php

namespace App\Http\Controllers\Auth\Access\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Location;

class TenantEmployeeController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }
            return view('auth.tenant.page.employee');
        }
    }

    public function getEmployeeData(Request $request) {
        if ($request->ajax()) {
            $playgroundOwner = Auth::user()->id;
            $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');
            
            // OPTION 2

            // $tes = User::where('id', Auth::user()->id)->with([
            //     'locations' => function($query){
            //         $query->with(['users' => function ($query){
            //             $query->with(['userDetail']);
            //         }]);
            //     }
            // ])->get();
            
            // \Log::info('Fetched Users: ' . json_encode($tes->toArray(), JSON_PRETTY_PRINT));

            // OPTION 2

            // $query = User::with(['userDetail', 'location'])
            //     ->whereIn('role', ['playground_supervisor', 'playground_operator'])
            //     ->whereHas('location', function($query) use($locationIds) {
            //         $query->whereIn('locationId', $locationIds);
            //     });

            // REDUCE SELECTION TO SAVE MEMORY

            $query = User::select(['id', 'name', 'email', 'phone', 'role', 'status'])
                            ->with([
                                'userDetail:id,userId,jenis_kelamin',
                                'location:id,location_name'
                            ])
                            ->whereIn('role', ['playground_supervisor', 'playground_operator'])
                            ->whereHas('location', function($query) use($locationIds) {
                                $query->whereIn('locationId', $locationIds);
                            });
    
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->filled('phone')) {
                $query->where('phone', 'like', '%' . $request->phone . '%');
            }
            if ($request->filled('jenis_kelamin')) {
                $query->whereHas('userDetail', function ($q) use ($request) {
                    $q->where('jenis_kelamin', $request->jenis_kelamin);
                });
            }
            if ($request->filled('jabatan')) {
                $query->where('role', $request->jabatan);
            }
            if ($request->filled('lokasi')) {
                $query->whereHas('location', function ($q) use ($request) {
                    $q->whereIn('locationId', $request->lokasi);
                });
            }
    
            $users = $query->get();
    
            return DataTables::of($users)
                ->addColumn('jenis_kelamin', function ($user) {
                    return $user->userDetail->jenis_kelamin;
                })
                ->addColumn('status', function ($user) {
                    $badge = "";
                    if($user->status == 1){
                        $badge = '<div class="badge badge-success mr-1">Aktif</div>';
                    } else if($user->status == 0){
                        $badge = '<div class="badge badge-danger mr-1">Tidak Aktif</div>';
                    }
                    return $badge;
                })
                ->addColumn('role', function ($user) {
                    $badge = "";
                    if($user->role == 'playground_supervisor'){
                        $badge = '<div class="badge badge-success mr-1">Supervisor</div>';
                    } else if($user->role == 'playground_operator'){
                        $badge = '<div class="badge badge-primary mr-1">Operator</div>';
                    }
                    return $badge;
                })
                ->addColumn('location', function ($user) {
                    // $badgecolor = [
                    //     'badge-primary',
                    //     'badge-secondary',
                    //     'badge-success',
                    //     'badge-danger',
                    //     'badge-warning',
                    //     'badge-info',
                    //     'badge-dark'
                    // ];
                    // return $user->location->map(function ($location) {
                    //     return '<div class="badge badge-primary mr-1">' . e($location->location_name) . '</div>';
                    // })->implode(' ');
                    $badgecolor = [
                        'badge-primary',
                        'badge-secondary',
                        'badge-success',
                        'badge-danger',
                        'badge-warning',
                        'badge-info',
                        'badge-dark'
                    ];
                    
                    return $user->location->map(function ($location) use ($badgecolor) {
                        $randomColor = $badgecolor[array_rand($badgecolor)];
                        return '<div class="badge ' . $randomColor . ' mr-1">' . e($location->location_name) . '</div>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($user) {
                    $editUrl = route('tenant.dashboard.employee.detail', ['userId' => $user->id]);
                    $statusUpdateUrl = route('tenant.dashboard.employee.status', ['userId' => $user->id]);
                    $btn = "";
                    if($user->status == 1){
                        $btn = "btn-danger";
                    } else {
                        $btn = "btn-success";
                    }
                        
                    return '
                        <a title="Lihat Data Karyawan" href="' . $editUrl . '"
                            class="btn btn-sm btn-info m-1">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                        <a title="Ubah status keaktifan karyawan" href="'.$statusUpdateUrl.'"
                            class="btn btn-sm '.$btn.' m-1">
                            <i class="fa-solid fa-power-off"></i>
                        </a>
                    ';
                })
                ->rawColumns(['action', 'location', 'role', 'status'])
                ->make(true);
        }
    }    

    public function userDataInsert(Request $request) {
        try {
            $validatedData = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|string|max:255|unique:users,email',
                'phone'             => 'required|numeric',
                'jenis_kelamin'     => 'required|string',
                'jabatan'           => 'required|string',
                'plot_lokasi'       => 'required|array',
            ]);
    
            $user = User::create([
                'name'      => $validatedData['name'],
                'email'     => $validatedData['email'],
                'phone'     => $validatedData['phone'],
                'password'  => Hash::make('password'),
                'role'      => $validatedData['jabatan'],
            ]);
    
            if (!empty($validatedData['plot_lokasi'])) {
                $user->location()->syncWithoutDetaching($validatedData['plot_lokasi']);
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
                'message' => 'Failed to add user due to an error, please contact support!'
            ], 500);
        }
    } 

    public function userDataStatusUpdate($id){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $user = User::with(['userDetail', 'location'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $id)
                        ->first();

        if(is_null($user)){
            return redirect()->back()->with('error', 'User not found!');
        } else {
            $status = 0;
            if($user->status == 0){
                $status = 1;
            }

            $user->update([
                'status' => $status
            ]);

            return redirect()->back()->with('success', 'User status updated successfully!');
        }
    }

    public function userDataDetail($id) {
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $user = User::with(['userDetail', 'location'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $id)
                        ->first();
        if(is_null($user)){
            return redirect()->back()->with('error', 'User not found!');
        } else {
            $selectedLocations = $user->location->pluck('id')->toArray();
            return view('auth.tenant.page.employeeDetail', compact('user', 'selectedLocations'));
        }
    }

    public function userDataUpdate(Request $request){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $user = User::with(['userDetail', 'location'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $request->id)
                        ->first();
        if(is_null($user)){
            return redirect()->back()->with('error', 'User not found!');
        } else {
            $user->update([
                'name'      => $request->full_name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'role'      => $request->jabatan,
                'status'    => $request->status,
            ]);

            $user->location()->sync($request->plot_lokasi);

            return redirect()->back()->with('success', 'User account updated successfully!');
        }
    }

    public function userDetailUpdate(Request $request){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $user = User::with(['userDetail', 'location'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $request->id)
                        ->first();
        if(is_null($user)){
            return redirect()->back()->with('error', 'User not found!');
        } else {
            $user->userDetail->update([
                'no_ktp'            => $request->ktp,
                'tempat_lahir'      => $request->tmpt_lahir,
                'tanggal_lahir'     => $request->birth,
                'jenis_kelamin'     => $request->jenis_kelamin,
                'alamat'            => $request->address,
            ]);

            return redirect()->back()->with('success', 'User detail information updated successfully!');
        }
    }
    

}
