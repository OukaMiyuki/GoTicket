<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationType;

class LocationTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $type = [
            ['name' => 'Wisata Agro', 'detail' => 'Mengunjungi perkebunan, peternakan, atau pertanian untuk belajar dan menikmati hasil alam langsung.'],
            ['name' => 'Wisata Arkeologi', 'detail' => 'Menjelajahi situs bersejarah seperti candi, piramida, dan kota kuno.'],
            ['name' => 'Wisata Bahari', 'detail' => 'Wisata berbasis laut seperti naik kapal pesiar, menyelam, atau menikmati desa nelayan.'],
            ['name' => 'Wisata Cagar Alam', 'detail' => 'Menjelajahi keindahan alam yang masih alami dengan flora dan fauna yang dilindungi.'],
            ['name' => 'Wisata Dark Tourism', 'detail' => 'Wisata ke tempat-tempat bersejarah dengan kisah tragis seperti bekas kamp konsentrasi atau situs bencana.'],
            ['name' => 'Wisata Desa', 'detail' => 'Menikmati suasana pedesaan, budaya lokal, dan aktivitas masyarakat setempat.'],
            ['name' => 'Wisata Edukasi', 'detail' => 'Wisata yang memberikan pengalaman pembelajaran tentang berbagai bidang ilmu dan keterampilan.'],
            ['name' => 'Wisata Edukasi Anak', 'detail' => 'Wisata khusus untuk anak-anak dengan konsep belajar sambil bermain.'],
            ['name' => 'Wisata Edukasi Remaja', 'detail' => 'Wisata yang dirancang untuk remaja dengan aktivitas edukatif dan interaktif.'],
            ['name' => 'Wisata Festival', 'detail' => 'Menghadiri festival budaya, musik, atau kuliner di berbagai daerah.'],
            ['name' => 'Wisata Film dan TV', 'detail' => 'Mengunjungi lokasi syuting film terkenal atau studio produksi.'],
            ['name' => 'Wisata Flora dan Fauna', 'detail' => 'Wisata yang berfokus pada pengamatan tumbuhan dan satwa liar di habitat aslinya.'],
            ['name' => 'Wisata Hutan', 'detail' => 'Wisata di tengah hutan dengan aktivitas seperti trekking, camping, dan observasi satwa liar.'],
            ['name' => 'Wisata Industri', 'detail' => 'Mengunjungi pabrik atau tempat produksi untuk melihat langsung proses pembuatan suatu produk.'],
            ['name' => 'Wisata Keluarga', 'detail' => 'Wisata yang cocok untuk keluarga dengan berbagai aktivitas menyenangkan untuk semua usia.'],
            ['name' => 'Wisata Kebun Binatang', 'detail' => 'Wisata yang menampilkan berbagai jenis satwa dari berbagai belahan dunia dalam lingkungan yang edukatif.'],
            ['name' => 'Wisata Kesehatan dan Kebugaran', 'detail' => 'Wisata yang menawarkan pengalaman relaksasi, spa, yoga, dan terapi kesehatan lainnya.'],
            ['name' => 'Wisata Kuliner', 'detail' => 'Wisata yang berfokus pada eksplorasi makanan khas daerah dan pengalaman memasak lokal.'],
            ['name' => 'Wisata Malam', 'detail' => 'Menikmati keindahan kota di malam hari dengan aktivitas seperti night market, hiburan malam, dan festival lampu.'],
            ['name' => 'Wisata Militer', 'detail' => 'Mengunjungi situs-situs bersejarah yang terkait dengan militer, seperti benteng, museum perang, atau kapal perang.'],
            ['name' => 'Wisata Outbound', 'detail' => 'Wisata dengan aktivitas tim building, seperti flying fox, rafting, dan survival training.'],
            ['name' => 'Wisata Pantai', 'detail' => 'Wisata menikmati keindahan pantai dengan berbagai aktivitas seperti berenang, snorkeling, dan bersantai di tepi laut.'],
            ['name' => 'Wisata Pegunungan', 'detail' => 'Wisata di daerah pegunungan dengan udara sejuk dan pemandangan alam yang menakjubkan.'],
            ['name' => 'Wisata Petualangan', 'detail' => 'Wisata yang menawarkan tantangan dan adrenalin tinggi seperti arung jeram, panjat tebing, dan paralayang.'],
            ['name' => 'Wisata Religi', 'detail' => 'Wisata mengunjungi tempat-tempat ibadah atau situs spiritual untuk tujuan keagamaan dan refleksi diri.'],
            ['name' => 'Wisata Rohani', 'detail' => 'Wisata dengan kegiatan meditasi, refleksi spiritual, dan ketenangan batin.'],
            ['name' => 'Wisata Sejarah', 'detail' => 'Wisata yang mengenalkan sejarah dan budaya dengan mengunjungi situs-situs bersejarah.'],
            ['name' => 'Wisata Seni dan Budaya', 'detail' => 'Wisata yang memperkenalkan seni dan budaya lokal melalui pertunjukan, museum, dan festival seni.'],
            ['name' => 'Wisata Sepeda', 'detail' => 'Bersepeda sambil menikmati pemandangan alam atau kota dengan rute khusus.'],
            ['name' => 'Wisata Sport Tourism', 'detail' => 'Wisata ke acara olahraga besar seperti Olimpiade, Piala Dunia, atau balapan MotoGP.'],
            ['name' => 'Wisata Staycation', 'detail' => 'Berlibur di hotel atau resort tanpa bepergian jauh, biasanya untuk relaksasi.'],
            ['name' => 'Wisata Suburban', 'detail' => 'Wisata di daerah pinggiran kota dengan konsep yang lebih tenang dibanding wisata urban.'],
            ['name' => 'Wisata Survival', 'detail' => 'Wisata ekstrem dengan tantangan bertahan hidup di alam liar.'],
            ['name' => 'Wisata Theme Park', 'detail' => 'Mengunjungi taman hiburan besar seperti Disneyland atau Universal Studios.'],
            ['name' => 'Wisata Underwater', 'detail' => 'Wisata bawah laut seperti menyelam atau menikmati keindahan terumbu karang dengan kapal selam wisata.'],
            ['name' => 'Wisata Urban', 'detail' => 'Menjelajahi kehidupan kota besar dengan atraksi seperti gedung pencakar langit, museum modern, dan pusat perbelanjaan.'],
            ['name' => 'Wisata Voluntourism', 'detail' => 'Wisata yang menggabungkan perjalanan dengan kegiatan sosial seperti konservasi lingkungan dan kegiatan amal.'],
        ];

        foreach ($type as $type) {
            LocationType::create([
                'name' => $type['name'],
                'detail' => $type['detail'],
            ]);
        }
    }
}
