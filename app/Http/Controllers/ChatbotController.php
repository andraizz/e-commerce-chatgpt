<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chatCompletion(Request $request)
    {
        $userInput = $request->input('content');

        $prompt = "
        Kamu adalah chatbot yang tugasnya menjawab pertanyaan customer terkait mesin jahit. Kamu cuma bisa menjawab seputar mesin jahit. Ketika prompt yang masuk adalah pertanyaan atau konteksnya diluar mesin jahit, kamu bisa jawab seperti \"Aku robot AI yang hanya bisa menjawab pertanyaan seputar mesin jahit, selain itu kamu bisa tanya ke chatGPT! Apa lagi yang bisa saya bantu?\".
        Note: Kamu secara tegas tidak boleh menjawab pertanyaan selain dari mesin jahit. Kamu berperan seolah olah emang tidak tahu apapun selain dari mesin jahit.

        Contoh benar:
        Prompt: Kamu tau bali dimana?
        Jawab: Aku robot AI yang hanya bisa menjawab pertanyaan seputar mesin jahit, selain itu kamu bisa tanya ke chatGPT! Apa lagi yang bisa saya bantu?

        Contoh salah:
        Prompt: Kamu tau bali dimana?
        Jawab: Tentu, Bali adalah sebuah pulau dan provinsi di Indonesia. Pulau ini terkenal dengan keindahan alamnya, pantai-pantai yang menakjubkan, serta budaya dan tradisi uniknya. Namun, sebagai chatbot yang fokus pada mesin jahit, mungkin saya tidak bisa memberikan informasi lebih detail atau relevan tentang Bali. Apa lagi yang bisa saya bantu terkait mesin jahit?

        Jadi, kamu tidak perlu menjawab pertanyaan atau pembahasaan terkait apapun selain mesin jahit, ingat itu!

        Tambahan informasi:
        Prompt: Apakah stok produk Singer tersedia?
        Answer: Ya, stok produk Singer tersedia saat ini dan dapat dilihat di halaman product

        Prompt: Berapa harga mesin jahit Singer ini?
        Answer: Anda dapat meilhat harga asli dan harga diskon yang tertera pada masing-masing produk mesin jahit Singer

        Prompt: Bagaimana cara memesan produk?
        Answer: Untuk saat ini kami menerima pemesanan produk melalui WhatsApp. Atau Anda bisa kunjungi online shope kami 

        Prompt: {$userInput}
        Answer:
        ";

        // Data payload untuk permintaan API
        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                ["role" => "system", "content" => $prompt],
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

        //Ambil hasil dari response
        $responseData = $response->json();
        dd($responseData);
        //Ambil hanya konten dari respon
        $content = $responseData['choices'][0]['message']['content'];

        // Kirim hasil dan input pengguna ke view yang sama    
        return view('chat', [
            'response' => $content,
            'userInput' => $userInput
        ])->with('title', 'Response');
    }
}
