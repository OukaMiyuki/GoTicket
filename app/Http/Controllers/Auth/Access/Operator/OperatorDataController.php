<?php

namespace App\Http\Controllers\Auth\Access\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Packet;

class OperatorDataController extends Controller {
    public function index_packet(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_operator'])) {
                abort(403);
            }
            return view('auth.operator.page.packet');
        }
    }

    public function getPacketData(Request $request){
        $id = Auth::id();
        $locationIds = User::find($id)?->location()->pluck('locations.id')->toArray();
        if ($request->ajax()) {
            $playgroundOwner = Auth::id();
            $query = Packet::with(['location', 'galleries'])->whereIn('locationId', $locationIds);

            if ($request->filled('packet_name')) {
                $query->where('packet_name', 'like', '%' . $request->packet_name. '%');
            }
            if ($request->filled('price')) {
                $price = intval($request->price);
                $query->where('price', 'like', '%' . $price . '%');
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('lokasi')) {
                $query->whereIn('locationId', $request->lokasi);
            }
            $packet = $query->get();

            $badgecolor = [
                'badge-primary',
                'badge-secondary',
                'badge-success',
                'badge-danger',
                'badge-warning',
                'badge-info',
                'badge-dark'
            ];

            return DataTables::of($packet)
                ->addColumn('price', function ($packet) {
                    $price = $packet->price;
                    $harga = number_format($price,2,',','.');
                    return $harga;
                })
                ->addColumn('status', function ($packet) {
                    $badge = "";
                    if($packet->status == 1){
                        $badge = '<div class="badge badge-success mr-1">Aktif</div>';
                    } else if($packet->status == 0){
                        $badge = '<div class="badge badge-danger mr-1">Tidak Aktif</div>';
                    }
                    return $badge;
                })
                ->addColumn('validity_type', function ($packet) use ($badgecolor) {

                    if ($packet->validity_type == "daily") {
                       $string = "Harian";
                    } else if($packet->validity_type == "weekly"){
                        $string = "Mingguan";
                    } else if ($packet->validity_type == "monthly") {
                        $string = "Bulanan";
                    } else if ($packet->validity_type == "yearly") {
                        $string = "Tahunan";
                    }

                    $randomColor = $badgecolor[array_rand($badgecolor)];
                    return '<div class="badge ' . $randomColor . ' mr-1">' . $string . '</div>';
                    return '';
                })
                ->addColumn('gallery_button', function ($packet) {
                    if ($packet->galleries && $packet->galleries->isNotEmpty()) {
                        $galleryImages = $packet->galleries->map(function ($gallery) {
                            return url('storage/' . $gallery->image);
                        })->toArray();

                        return '<button title="Preview image gallery" type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-backdrop="false" data-target="#carouselModal" data-images="' . htmlspecialchars(json_encode($galleryImages)) . '">
                                    <i class="fa-solid fa-images"></i>
                                </button>';
                    }

                    return '';
                })
                ->addColumn('location', function ($packet) use ($badgecolor) {
                    if ($packet->location) {
                        $randomColor = $badgecolor[array_rand($badgecolor)];
                        $shortenedName = Str::limit($packet->location->location_name, 15, '...');
                        return '<div class="badge ' . $randomColor . ' mr-1" title="' . e($packet->location->location_name) . '">' . e($shortenedName) . '</div>';
                    }
                    return '';
                })
                ->addColumn('action', function ($packet) {
                    $editUrl = route('tenant.dashboard.packet.edit', ['packetId' => $packet->id]);
                    $statusUpdateUrl = route('tenant.dashboard.packet.status', ['packetId' => $packet->id]);
                    $deleteUrl = route('tenant.dashboard.packet.delete', ['packetId' => $packet->id]);
                    $btn = "";
                    if($packet->status == 1){
                        $btn = "btn-warning";
                    } else {
                        $btn = "btn-success";
                    }

                    return '<div class="btn-group">
                                            <button type="button" class="btn btn-success">Action</button>
                                            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <h6 class="dropdown-header">Action data</h6>
                                                <a class="dropdown-item" href="'.$editUrl.'">Lihat Data</a>
                                            </div>
                                        </div>';
                })
                ->rawColumns(['price', 'status', 'location', 'action', 'validity_type', 'gallery_button'])
                ->make(true);
        }
    }
}
