<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{

    public function chatCompletion(Request $request)
    {
        // Ambil input pengguna dari view
        $userInput = $request->input('content');

        // Filter respons hanya dalam lingkup e-commerce
        $response = $this->filterEcommerceResponse($userInput);

        // Jika pertanyaan pengguna sesuai dengan pola, berikan respons berdasarkan pola
        if ($response !== null) {
            return view('chat', [
                'response' => $response,
                'userInput' => $userInput
            ])->with('title', 'Response');
        }

        // Memulai waktu respon
        $start = microtime(true);

        // Jika pertanyaan pengguna tidak sesuai dengan pola, gunakan ChatGPT untuk menghasilkan respons
        // Data payload untuk permintaan API
        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ["role" => "user", "content" => $userInput]
            ],
            "temperature" => 0.7
        ];

        // Panggil API OpenAI menggunakan Laravel HTTP Client
        $openaiApiKey = env('OPENAI_API_KEY');
        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $openaiApiKey
        ])->post("https://api.openai.com/v1/chat/completions", $data);

        // Ambil hasil dari response
        $responseData = $response->json();

        // Ambil hanya konten dari respon
        $content = $responseData['choices'][0]['message']['content'];

        // Kirim hasil dan input pengguna ke view yang sama
        return view('chat', [
            'response' => $content,
            'userInput' => $userInput
        ])->with('title', 'Response');
    }

    private function filterEcommerceResponse($response)
    {
        // Daftar keyword yang diizinkan dan respons yang beragam
        $keywordResponses = [
            '/(stok|stock|produk|products)/i' => [
                'Apa yang Anda lihat di halaman Products, stok produk Singer masih tersedia. Anda juga bisa mengunjungi online shop kami untuk melihat lebih banyak produk.',
                'Untuk melihat stok produk Singer yang tersedia, silakan lihat di halaman Products. Anda juga bisa mengunjungi online shop kami, karena kami juga menjual spare part mesin jahit.'
            ],
            '/(harga)/i' => [
                'Harga produk yang tertera pada halaman Products sudah termasuk diskon, mungkin harga dapat berubah sewaktu-waktu.',
                'Anda dapat meilhat harga asli dan harga diskon yang tertera pada masing-masing produk mesin jahit Singer.'
            ],
            '/(pelayanan|pelatihan|penggunaan)/i' => [
                'Kami akan membantu pelatihan penggunaan dan perawatan mesin secara GRATIS.',
                'Saat ini pelatihan penggunaan mesin akan kami pandu melalui WhatsApp dan akan kami kirim video tutorial.'
            ],
            '/(pesan|pemesanan|order)/i' => [
                'Untuk saat ini kami menerima pemesanan produk melalui WhatsApp. Atau Anda bisa kunjungi online shope kami.',
                'Silakan klik icon WhatsApp pada produk yang diinginkan, setelah itu hubungi admin untuk melanjutkan pemesanan.'
            ],
            '/(pengiriman|kirim)/i' => [
                'Produk kami siap dikirim ke seluruh Indonesia. Gratis ongkos kirim untuk wilayah DKI Jakarta dan sekitarnya.',
                'Siap dikirim ke seluruh Indonesia. Pengiriman kami bisa menggunakan ojek online atau kurir ekspedisi seperti JNT, JNE dan Sicepat.'
            ],
            '/(garansi|bergaransi)/i' => [
                'Semua produk kami bergaransi selama satu tahun.',
                'Garansi satu tahun. Silakan hubungi admin untuk klaim garansi.'
            ]
        ];

        // Cek apakah respons mengandung keyword/pattern yang diizinkan
        foreach ($keywordResponses as $pattern => $responses) {
            if (preg_match($pattern, $response)) {            
                // Jika respons mengandung keyword/pattern, kirimkan respons yang acak dari daftar respons yang beragam
                $responseIndex = array_rand($responses);
                return $responses[$responseIndex];
            }
        }

        // Jika respons tidak mengandung keyword/pattern yang diizinkan, kembalikan null
        return null;
    }
};