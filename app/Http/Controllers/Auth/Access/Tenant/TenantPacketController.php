<?php

namespace App\Http\Controllers\Auth\Access\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;
use App\Models\Location;
use App\Models\Packet;
use App\Models\PacketGallery;

class TenantPacketController extends Controller {
    public function index(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }
            return view('auth.tenant.page.packet');
        }
    }

    public function getPacketData(Request $request) {
        if ($request->ajax()) {
            $playgroundOwner = Auth::id();
            $locationIds = Location::where('userId', $playgroundOwner)->pluck('id')->toArray();
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
            Log::info($request->all());
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

                        Log::info('Gallery Images: ' . json_encode($galleryImages));

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
                                                <a class="dropdown-item" href="'.$editUrl.'">Edit Data</a>
                                                <a class="dropdown-item" href="'.$statusUpdateUrl.'">Update Status</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="'.$deleteUrl.'">Delete</a>
                                            </div>
                                        </div>';
                    // return '
                    //     <a title="Edit data paket" href="' . $editUrl . '"
                    //         class="btn btn-sm btn-info" style="margin:.2rem";>
                    //         <i class="fa-solid fa-pen-to-square"></i>
                    //     </a>
                    //     <a title="Ubah status keaktifan paket" href="'.$statusUpdateUrl.'"
                    //         class="btn btn-sm '.$btn.'" style="margin:.2rem";>
                    //         <i class="fa-solid fa-power-off"></i>
                    //     </a>
                    //     <a title="Hapus paket" href="'.$deleteUrl.'"
                    //         class="btn btn-sm btn-danger" style="margin:.2rem";>
                    //         <i class="fa-solid fa-trash"></i>
                    //     </a>
                    // ';
                })
                ->rawColumns(['price', 'status', 'location', 'action', 'validity_type', 'gallery_button'])
                ->make(true);
        }
    }

    public function addPacketData(){
        if (! Auth::check()) {
            return redirect()->route('login');
        } else {
            $user = Auth::user();
            if ($user && !in_array($user->role, ['playground_owner'])) {
                abort(403);
            }
            return view('auth.tenant.page.packet-add');
        }
    }

    public function addPacketDataInsert(Request $request) {
        $request->validate([
            'packet_name'       => 'required|string|max:255',
            'harga'             => 'required|numeric',
            'currency'          => 'required|string',
            // 'max_ppl'           => 'required|integer',
            'duration'          => 'required|integer',
            'validity'          => 'required|string|in:daily,weekly,monthly,yearly',
            'lokasi'            => 'required|exists:locations,id',
            'detail'            => 'required|string',
            'gallery_files.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Multiple images
        ]);

        $packet = Packet::create([
            'locationId' => $request->lokasi,
            'packet_name' => $request->packet_name,
            'price' => $request->harga,
            'currency' => $request->currency,
            // 'max_people' => $request->max_ppl,
            'duration' => $request->duration,
            'validity_type' => $request->validity,
            'packet_detail' => $request->detail,
            'status' => 1
        ]);

        // if ($request->hasFile('gallery_files')) {
        //     foreach ($request->file('gallery_files') as $file) {
        //         $filename = time() . '_' . $file->getClientOriginalName();
        //         $path = $file->storeAs('media/user-packet/image', $filename, 'public');

        //         PacketGallery::create([
        //             'packetId' => $packet->id,
        //             'title' => now().$request->packet_name,
        //             'image' => $path,
        //             'image_detail' => 'Uploaded image'
        //         ]);
        //     }
        // }

        // if ($request->hasFile('gallery_files')) {
        //     foreach ($request->file('gallery_files') as $file) {
        //         $timestamp = now()->format('YmdHis');
        //         $packetName = Str::slug($request->packet_name, '_');
        //         $extension = $file->getClientOriginalExtension();
        //         $filename = "{$timestamp}_{$packetName}.{$extension}";
        //         $path = $file->storeAs('media/user-packet/image', $filename, 'public');
        //         PacketGallery::create([
        //             'packetId' => $packet->id,
        //             'title' => now()->toDateTimeString() . ' - ' . $request->packet_name,
        //             'image' => $path,
        //             'image_detail' => 'Uploaded image'
        //         ]);
        //     }
        // }

        // if ($request->hasFile('gallery_files')) {
        //     foreach ($request->file('gallery_files') as $index => $file) {
        //         $timestamp = now()->format('YmdHis');
        //         $packetName = Str::slug($request->packet_name, '_');
        //         $filename = "{$timestamp}_{$packetName}_{$index}.webp";

        //         Log::info("Processing file: " . $file->getClientOriginalName());

        //         try {
        //             $image = Image::read($file);

        //             if (!$image) {
        //                 Log::error("Failed to read image: " . $file->getClientOriginalName());
        //                 continue;
        //             }

        //             $image->resize(1024, null, function ($constraint) {
        //                 $constraint->aspectRatio();
        //                 $constraint->upsize();
        //             });

        //             $encodedImage = $image->encode(new WebpEncoder(quality: 80));

        //             if (!$encodedImage) {
        //                 Log::error("Encoding failed for image: " . $file->getClientOriginalName());
        //                 continue;
        //             }

        //             Storage::disk('public')->put("media/user-packet/image/{$filename}", $encodedImage);

        //             PacketGallery::create([
        //                 'packetId' => $packet->id,
        //                 'title' => now()->toDateTimeString() . ' - ' . $request->packet_name,
        //                 'image' => "media/user-packet/image/{$filename}",
        //                 'image_detail' => 'Optimized and converted to WebP'
        //             ]);

        //             Log::info("Successfully uploaded: {$filename}");

        //         } catch (\Exception $e) {
        //             Log::error("Error processing file: " . $file->getClientOriginalName() . " - " . $e->getMessage());
        //         }
        //     }
        // }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $index => $file) {
                $timestamp = now()->format('YmdHis');
                $packetName = Str::slug($request->packet_name, '_');
                $filename = "{$timestamp}_{$packetName}_{$index}.webp";

                Log::info("Processing file: " . $file->getClientOriginalName());

                try {
                    $image = Image::read($file);

                    if (!$image) {
                        Log::error("Failed to read image: " . $file->getClientOriginalName());
                        continue;
                    }

                    $encodedImage = $image->encode(new WebpEncoder(quality: 80));

                    if (!$encodedImage) {
                        Log::error("Encoding failed for image: " . $file->getClientOriginalName());
                        continue;
                    }

                    Storage::disk('public')->put("media/user-packet/image/{$filename}", $encodedImage);

                    PacketGallery::create([
                        'packetId' => $packet->id,
                        'title' => now()->toDateTimeString() . ' - ' . $request->packet_name,
                        'image' => "media/user-packet/image/{$filename}",
                        'image_detail' => 'Optimized and converted to WebP'
                    ]);

                    Log::info("Successfully uploaded: {$filename}");

                } catch (\Exception $e) {
                    Log::error("Error processing file: " . $file->getClientOriginalName() . " - " . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Packet successfully created!',
            'redirect' => route('tenant.dashboard.packet')
        ]);
    }

    public function editPacketData($id){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $packet = Packet::with(['galleries'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $id)
                        ->first();
        if(is_null($packet)){
            return redirect()->back()->with('error', 'Data not found!');
        } else {
            return view('auth.tenant.page.packet-edit', compact('packet'));
        }
    }

    public function editPacketDataUpdate(Request $request){
        $request->validate([
            'id'                => 'required',
            'packet_name'       => 'required|string|max:255',
            'harga'             => 'required|numeric',
            'currency'          => 'required|string',
            // 'max_ppl'           => 'required|integer',
            'duration'          => 'required|integer',
            'validity'          => 'required|string|in:daily,weekly,monthly,yearly',
            'lokasi'            => 'required|exists:locations,id',
            'detail'            => 'required|string',
            'gallery_files.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $packet = Packet::with(['galleries'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $request->id)
                        ->first();

        if(is_null($packet)){
            return redirect()->back()->with('error', 'Data not found!');
        } else {
            $packet->update([
                'locationId' => $request->lokasi,
                'packet_name' => $request->packet_name,
                'price' => $request->harga,
                'currency' => $request->currency,
                // 'max_people' => $request->max_ppl,
                'duration' => $request->duration,
                'validity_type' => $request->validity,
                'packet_detail' => $request->detail,
            ]);

            if ($request->hasFile('gallery_files')) {
                if (is_null($packet->galleries)) {
                    foreach ($request->file('gallery_files') as $index => $file) {
                        $timestamp = now()->format('YmdHis');
                        $packetName = Str::slug($request->packet_name, '_');
                        $filename = "{$timestamp}_{$packetName}_{$index}.webp";

                        Log::info("Processing file: " . $file->getClientOriginalName());

                        try {
                            $image = Image::read($file);

                            if (!$image) {
                                Log::error("Failed to read image: " . $file->getClientOriginalName());
                                continue;
                            }

                            $encodedImage = $image->encode(new WebpEncoder(quality: 80));

                            if (!$encodedImage) {
                                Log::error("Encoding failed for image: " . $file->getClientOriginalName());
                                continue;
                            }

                            Storage::disk('public')->put("media/user-packet/image/{$filename}", $encodedImage);

                            PacketGallery::create([
                                'packetId' => $packet->id,
                                'title' => now()->toDateTimeString() . ' - ' . $request->packet_name,
                                'image' => "media/user-packet/image/{$filename}",
                                'image_detail' => 'Optimized and converted to WebP'
                            ]);

                            Log::info("Successfully uploaded: {$filename}");

                        } catch (\Exception $e) {
                            Log::error("Error processing file: " . $file->getClientOriginalName() . " - " . $e->getMessage());
                        }
                    }
                } else {
                    foreach ($packet->galleries as $gallery) {
                        if (Storage::disk('public')->exists($gallery->image)) {
                            Storage::disk('public')->delete($gallery->image);
                        }

                        $gallery->delete();
                    }

                    foreach ($request->file('gallery_files') as $index => $file) {
                        $timestamp = now()->format('YmdHis');
                        $packetName = Str::slug($request->packet_name, '_');
                        $filename = "{$timestamp}_{$packetName}_{$index}.webp";

                        Log::info("Processing file: " . $file->getClientOriginalName());

                        try {
                            $image = Image::read($file);

                            if (!$image) {
                                Log::error("Failed to read image: " . $file->getClientOriginalName());
                                continue;
                            }

                            $encodedImage = $image->encode(new WebpEncoder(quality: 80));

                            if (!$encodedImage) {
                                Log::error("Encoding failed for image: " . $file->getClientOriginalName());
                                continue;
                            }

                            Storage::disk('public')->put("media/user-packet/image/{$filename}", $encodedImage);

                            PacketGallery::create([
                                'packetId' => $packet->id,
                                'title' => now()->toDateTimeString() . ' - ' . $request->packet_name,
                                'image' => "media/user-packet/image/{$filename}",
                                'image_detail' => 'Optimized and converted to WebP'
                            ]);

                            Log::info("Successfully uploaded: {$filename}");

                        } catch (\Exception $e) {
                            Log::error("Error processing file: " . $file->getClientOriginalName() . " - " . $e->getMessage());
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Packet successfully updated!',
                'redirect' => route('tenant.dashboard.packet')
            ]);
        }
    }

    public function editPacketDataStatusUpdate($id){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $packet = Packet::whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $id)
                        ->first();

        if(is_null($packet)){
            return redirect()->back()->with('error', 'Data not found!');
        } else {
            $status = 0;

            if($packet->status == 0){
                $status = 1;
            }

            $packet->update([
                'status' => $status
            ]);

            return redirect()->back()->with('success', 'Packet status updated successfully!');
        }
    }

    public function deletePacketData($id){
        $playgroundOwner = Auth::user()->id;
        $locationIds = Location::where('userId', $playgroundOwner)->pluck('id');

        $packet = Packet::with(['galleries'])
                        ->whereHas('location', function($query) use($locationIds) {
                            $query->whereIn('locationId', $locationIds);
                        })
                        ->where('id', $id)
                        ->first();

       try{
            if(is_null($packet)){
                return redirect()->back()->with('error', 'Data not found!');
            } else {
                if (!is_null($packet->galleries)) {
                    foreach ($packet->galleries as $gallery) {
                        if (Storage::disk('public')->exists($gallery->image)) {
                            Storage::disk('public')->delete($gallery->image);
                        }

                        $gallery->delete();
                    }
                }

                $packet->delete();

                return redirect()->back()->with('success', 'Packet data has been deleted successfully!');

            }
       } catch(\Exception $e){
            return redirect()->back()->with('error', "Data deletion failed, there's an error when trying to delete data!");
       }
    }
}
