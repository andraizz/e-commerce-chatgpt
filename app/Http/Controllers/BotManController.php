<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');
  
        $botman->hears('{message}', function($botman, $message)
        {
            if ($message == 'start' OR $message == 'mulai'){
                $this->askName($botman);
            }elseif($message == '1' OR $message == 'stok produk'){
                $botman->reply("Semua produk dengan label <b>Ready Stock</b> ready ya kak");
            }elseif($message == '2' OR $message == 'produk unggulan'){
                $botman->reply("Untuk produk unggulan kami dapat dilihat dengan label <b>BEST SELLER</b>");
            }elseif($message == '3' OR $message == 'harga produk'){
                $botman->reply("Harga produk yang tertera adalah harga diskon, mungkin harga dapat berubah sewaktu-waktu");
            }elseif($message == '4' OR $message == 'pelayanan singer'){
                $botman->reply("Kami akan membantu pelatihan penggunaan dan perawatan mesin secara GRATIS dan untuk layanan service kami akan datang ke rumah khusus wilayah DKI Jakarta dan sekitarnya");
            }elseif($message == '5' OR $message == 'cara order'){
                $botman->reply("Untuk saat ini kami menerima order via WhatsApp. Caranya klik icon WhatsApp pada produk yang ingin dipesan, lalu chat admin untuk melanjutkan pemesanan");
            }else{
                $botman->reply("Maaf, saya tidak mengerti maksud Anda :( </br> coba ketik <b>start</b> atau <b>mulai</b>");
            }
        });
  
        $botman->listen();
    }

    public function askName($botman)
    {
        $botman->ask('Halo, ada yang bisa saya bantu?', function(Answer $answer) {
  
            $answer->getText();
            $this->say('Berikut beberapa hal yang sering ditanyakan dan mungkin bisa membantu: </br>
            1. Stok Produk </br> 2. Produk Unggulan </br> 3. Harga Produk </br> 4. Pelayanan Singer </br> 5. Cara Order </br> 6. Info Lengkap');
            $this->say('Silahkan ketik pertanyaan atau nomor sesuai list untuk menjawab pertanyaan Anda');
        });
    }
}